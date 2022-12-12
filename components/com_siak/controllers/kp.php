<?php

use Joomla\CMS\Date\Date;

defined('_JEXEC') or die;

class SiakControllerKp extends JControllerForm
{
    public function cancel($key = null)
    {
        JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
        parent::cancel($key);
        $this->setRedirect(JRoute::_('index.php?option=com_siak&view=kps', false));

        return true;
    }

    public function save($key = null, $urlVar = null)
    {
        $this->checkToken();

        $app = \JFactory::getApplication();
        $model = $this->getModel();
        $table = $model->getTable();
        $data = $this->input->post->get('jform', [], 'array');
        $files = $this->input->files->get('jform', [], 'array');
        $laporan = $files['file_laporan'];
        $vName = $this->input->getCmd('view');
        $checkin = property_exists($table, $table->getColumnAlias('checked_out'));
        $context = "{$this->option}.edit.{$this->context}";
        $user = JFactory::getUser($data['user_id']);
        // Determine the name of the primary key for the data
        $path = JPATH_ROOT.'/media/com_siak/files/kp/';
        if (empty($key)) {
            $key = $table->getKeyName();
        }

        // To avoid data collisions the urlVar may be different from the primary key.
        if (empty($urlVar)) {
            $urlVar = $key;
        }

        $recordId = $this->input->getInt($urlVar);

        // Populate the row id from the session.
        $data[$key] = $recordId;

        $form = $model->getForm($data, false);

        if (!$form) {
            $app->enqueueMessage($model->getError(), 'error');

            return false;
        }

        if ('kaprodi' == $vName) {
            $fields = $form->getFieldset();
            foreach ($fields as $k => $val) {
                $form->setFieldAttribute($val->getAttribute('name'), 'required', 'false');
            }
        }

        // Send an object which can be modified through the plugin event
        $objData = (object) $data;
        $app->triggerEvent(
            'onContentNormaliseRequestData',
            [$this->option.'.'.$this->context, $objData, $form]
        );
        $data = (array) $objData;

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
        if (empty($validData['id']) or 0 == $validData['id']) {
            $validData['user_id'] = JFactory::getUser()->id;
            $tanggaldaftar = new Date('now');
            $validData['tanggal_daftar'] = $tanggaldaftar->toSql();
        }

        if (0 == $laporan['error'] && $laporan['size'] > 0) {
            // process upload
            jimport('joomla.filesystem.file');
            $fileName = JFile::makeSafe($laporan['name']);
            $ext = '.'.strtolower(JFile::getExt($fileName));
            $namaFile = 'Laporan_KP_'.$user->username.$ext;
            $pathFile = $path.$namaFile;

            if (JFile::exists($pathFile)) {
                $app->enqueueMessage(JText::_('COM_SIAK_ERROR_FILE_KP_EXIST_FILE'), 'error');
                $this->setRedirect(JRoute::_(JURI::getInstance().'&layout=edit', false));

                return false;
            }

            if (!JFile::upload($laporan['tmp_name'], $pathFile)) {
                $app->enqueueMessage(JText::_('COM_SIAK_ERROR_UNABLE_TO_UPLOAD_FILE'), 'error');

                return false;
            }

            $validData['file_laporan'] = $namaFile;
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

        $this->setMessage(\JText::_('JLIB_APPLICATION'.(0 === $recordId && $app->isClient('site') ? '_SUBMIT' : '').'_SAVE_SUCCESS'));

        // Clear the record id and data from the session.
        $this->releaseEditId($context, $recordId);
        $app->setUserState($context.'.data', null);

        $url = 'index.php?option='.$this->option.'&view=kps'
                    .$this->getRedirectToListAppend();

        // Redirect to the list screen.
        $this->setRedirect(\JRoute::_($url, false));

        // Invoke the postSave method to allow for the child class to access the model.
        $this->postSaveHook($model, $validData);

        return true;
    }
}
