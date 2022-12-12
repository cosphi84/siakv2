<?php

defined('_JEXEC') or exit;

//JLoader::register('Siak', JPATH_COMPONENT_ADMINISTRATOR.'/helpers/siak.php');
JLoader::register('Dosen', JPATH_COMPONENT_ADMINISTRATOR.'/helpers/dosen.php');

class SiakControllerUjian extends JControllerForm
{
    protected $text_prefix = 'COM_SIAK_UJIAN';
    //protected $view_list = 'ujians';
    //protected $view_item = 'ujian';

    public function getModel($name = 'Ujian', $prefix = 'SiakModel', $config = [])
    {
        return parent::getModel($name, $prefix, $config);
    }

    public function save($key = null, $urlVar = null)
    {
        $this->checkToken();
        $app = \JFactory::getApplication();
        $input = $app->input;
        $context = "{$this->option}.edit.{$this->context}";
        $user = \JFactory::getUser();
        $model = $this->getModel();
        $table = $model->getTable();
        $context = "{$this->option}.edit.{$this->context}";
        $task = $this->getTask();

        if (!$user->authorise('core.manage', 'com_siak')) {
            $app->enqueueMessage(JText::_('JERROR_ALERTNOAUTHOR'), 'error');
            $app->setHeader('status', 403, true);

            return;
        }

        if (empty($key)) {
            $key = $table->getKeyName();
        }

        if (empty($urlVar)) {
            $urlVar = $key;
        }

        $data = $input->get('jform', [], 'array');

        $recordId = $this->input->getInt($urlVar);

        // Populate the row id from the session.
        $data[$key] = $recordId;

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

        // Test whether the data is valid.
        $validData = $model->validate($form, $data);

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

            /**
             * We need the filtered value of calendar fields because the UTC normalision is
             * done in the filter and on output. This would apply the Timezone offset on
             * reload. We set the calendar values we save to the processed date.
             */
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

        if (!isset($validData[$key])) {
            $validData['input_by'] = JFactory::getUser()->id;
        }
        $validData['dosen'] = Dosen::getDosenByMk($validData['matakuliah'], $validData['kelas'], $validData['tahun_ajaran']);

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
}
