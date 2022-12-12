<?php

defined('_JEXEC') or exit;

class SiakControllerSk extends JControllerForm
{
    protected $text_prefix = 'COM_SIAK_SK';

    public function getModel($name = 'Sk', $prefix = 'SiakModel', $config = [])
    {
        return parent::getModel($name, $prefix, $config);
    }

    public function save($key = null, $urlVar = null)
    {
        $this->checkToken();

        $app = \JFactory::getApplication();
        $model = $this->getModel();
        $table = $model->getTable();
        $data = $this->input->post->get('jform', [], 'array');
        $files = $this->input->files->get('jform', [], 'array');
        if (key_exists('file', $data)) {
            $file = $files['filenew'];
        } else {
            $file = $files['file'];
        }

        $checkin = property_exists($table, $table->getColumnAlias('checked_out'));
        $context = "{$this->option}.edit.{$this->context}";
        $task = $this->getTask();

        if (empty($key)) {
            $key = $table->getKeyName();
        }

        if (empty($urlVar)) {
            $urlVar = $key;
        }

        $recordId = $this->input->getInt($urlVar);

        $data[$key] = $recordId;

        if ('save2copy' === $task) {
            // Check-in the original row.
            if ($checkin && false === $model->checkin($data[$key])) {
                // Check-in failed. Go back to the item and display a notice.
                $this->setError(\JText::sprintf('JLIB_APPLICATION_ERROR_CHECKIN_FAILED', $model->getError()));
                $this->setMessage($this->getError(), 'error');

                $this->setRedirect(
                    \JRoute::_(
                        'index.php?option='.$this->option.'&view='.$this->view_item
                        .$this->getRedirectToItemAppend($recordId, $urlVar),
                        false
                    )
                );

                return false;
            }

            $data[$key] = 0;
            $data['associations'] = [];
            $task = 'apply';
        }

        // Access check.
        if (!$this->allowSave($data, $key)) {
            $this->setError(\JText::_('JLIB_APPLICATION_ERROR_SAVE_NOT_PERMITTED'));
            $this->setMessage($this->getError(), 'error');

            $this->setRedirect(
                \JRoute::_(
                    'index.php?option='.$this->option.'&view='.$this->view_list
                    .$this->getRedirectToListAppend(),
                    false
                )
            );

            return false;
        }

        // Validate the posted data.
        // Sometimes the form needs some posted data, such as for plugins and modules.
        $form = $model->getForm($data, false);

        if (!$form) {
            $app->enqueueMessage($model->getError(), 'error');

            return false;
        }

        // Send an object which can be modified through the plugin event
        $objData = (object) $data;
        $app->triggerEvent(
            'onContentNormaliseRequestData',
            [$this->option.'.'.$this->context, $objData, $form]
        );
        $data = (array) $objData;

        if ($file['error'] > 0 && empty($data['file'])) {
            $app->enqueueMessage(JText::_('COM_SIAK_ERROR_UPLOAD_FILE'), 'error');

            return false;
        }

        if (4 != $file['error'] and $file['size'] > 0) {
            jimport('joomla.filesystem.file');

            $file['name'] = JFile::makeSafe($file['name']);
            if (!isset($file['name'])) {
                $app->enqueueMessage(JText::_('COM_SIAK_ERROR_BADFILENAME'), 'error');

                return false;
            }

            $file['name'] = str_replace(' ', '-', $file['name']);

            $mediaHelper = new JHelperMedia();
            if (!$mediaHelper->canUpload($file)) {
                return false;
            }

            $path = 'media/com_siak/files/sk/';
            $pathName = JPath::clean($path.$file['name']);
            $fullPath = JPATH_ROOT.'/'.$pathName;

            if (!empty($data['file'])) {
                // update file, jadi delet file lama
                JFile::delete(JPATH_ROOT.'/'.JPath::clean($path.$data['file']));
            }

            if (JFile::exists($fullPath)) {
                $app->enqueueMessage(JText::_('COM_SIAK_FILE_UPLOAD_EXIST'), 'warning');
                JFile::delete($fullPath);
            }
            if (!JFile::upload($file['tmp_name'], $fullPath)) {
                // Error in upload
                $app->enqueueMessage(JText::_('COM_SIAK_ERROR_UNABLE_TO_UPLOAD_FILE'), 'error');

                return false;
            }

            $data['file'] = $file['name'];
        }

        $validData = $data;

        // Check for validation errors.
        if (false === $validData) {
            // Get the validation messages.
            $errors = $model->getErrors();

            // Push up to three validation messages out to the user.
            for ($i = 0, $n = count($errors); $i < $n && $i < 3; ++$i) {
                if ($errors[$i] instanceof \Exception) {
                    $app->enqueueMessage($errors[$i]->getMessage(), 'warning');
                } else {
                    $app->enqueueMessage($errors[$i], 'warning');
                }
            }

            $filteredData = $form->filter($data);

            foreach ($form->getFieldset() as $field) {
                if ('Calendar' === $field->type) {
                    $fieldName = $field->fieldname;

                    if (isset($filteredData[$fieldName])) {
                        $data[$fieldName] = $filteredData[$fieldName];
                    }
                }
            }

            // Save the data in the session.
            $app->setUserState($context.'.data', $data);

            // Redirect back to the edit screen.
            $this->setRedirect(
                \JRoute::_(
                    'index.php?option='.$this->option.'&view='.$this->view_item
                    .$this->getRedirectToItemAppend($recordId, $urlVar),
                    false
                )
            );

            return false;
        }

        if (!isset($validData['tags'])) {
            $validData['tags'] = null;
        }

        // Attempt to save the data.
        if (!$model->save($validData)) {
            // Save the data in the session.
            $app->setUserState($context.'.data', $validData);

            // Redirect back to the edit screen.
            $this->setError(\JText::sprintf('JLIB_APPLICATION_ERROR_SAVE_FAILED', $model->getError()));
            $this->setMessage($this->getError(), 'error');

            $this->setRedirect(
                \JRoute::_(
                    'index.php?option='.$this->option.'&view='.$this->view_item
                    .$this->getRedirectToItemAppend($recordId, $urlVar),
                    false
                )
            );

            return false;
        }

        // Save succeeded, so check-in the record.
        if ($checkin && false === $model->checkin($validData[$key])) {
            // Save the data in the session.
            $app->setUserState($context.'.data', $validData);

            // Check-in failed, so go back to the record and display a notice.
            $this->setError(\JText::sprintf('JLIB_APPLICATION_ERROR_CHECKIN_FAILED', $model->getError()));
            $this->setMessage($this->getError(), 'error');

            $this->setRedirect(
                \JRoute::_(
                    'index.php?option='.$this->option.'&view='.$this->view_item
                    .$this->getRedirectToItemAppend($recordId, $urlVar),
                    false
                )
            );

            return false;
        }

        $langKey = $this->text_prefix.(0 === $recordId && $app->isClient('site') ? '_SUBMIT' : '').'_SAVE_SUCCESS';
        $prefix = \JFactory::getLanguage()->hasKey($langKey) ? $this->text_prefix : 'JLIB_APPLICATION';

        $this->setMessage(\JText::_($prefix.(0 === $recordId && $app->isClient('site') ? '_SUBMIT' : '').'_SAVE_SUCCESS'));

        // Redirect the user and adjust session state based on the chosen task.
        switch ($task) {
            case 'apply':
                // Set the record data in the session.
                $recordId = $model->getState($this->context.'.id');
                $this->holdEditId($context, $recordId);
                $app->setUserState($context.'.data', null);
                $model->checkout($recordId);

                // Redirect back to the edit screen.
                $this->setRedirect(
                    \JRoute::_(
                        'index.php?option='.$this->option.'&view='.$this->view_item
                        .$this->getRedirectToItemAppend($recordId, $urlVar),
                        false
                    )
                );

                break;
            case 'save2new':
                // Clear the record id and data from the session.
                $this->releaseEditId($context, $recordId);
                $app->setUserState($context.'.data', null);

                // Redirect back to the edit screen.
                $this->setRedirect(
                    \JRoute::_(
                        'index.php?option='.$this->option.'&view='.$this->view_item
                        .$this->getRedirectToItemAppend(null, $urlVar),
                        false
                    )
                );

                break;
            default:
                // Clear the record id and data from the session.
                $this->releaseEditId($context, $recordId);
                $app->setUserState($context.'.data', null);

                $url = 'index.php?option='.$this->option.'&view='.$this->view_list
                    .$this->getRedirectToListAppend();

                // Check if there is a return value
                $return = $this->input->get('return', null, 'base64');

                if (!is_null($return) && \JUri::isInternal(base64_decode($return))) {
                    $url = base64_decode($return);
                }

                // Redirect to the list screen.
                $this->setRedirect(\JRoute::_($url, false));

                break;
        }

        // Invoke the postSave method to allow for the child class to access the model.
        $this->postSaveHook($model, $validData);

        return true;
    }

    protected function allowAdd($data = [])
    {
        return parent::allowAdd($data);
    }

    protected function allowEdit($data = [], $key = 'id')
    {
        $id = isset($data[$key]) ? $data[$key] : 0;
        if (!empty($id)) {
            return JFactory::getUser()->authorise('core.edit', 'com_siak.sk.'.$id);
        }
    }
}
