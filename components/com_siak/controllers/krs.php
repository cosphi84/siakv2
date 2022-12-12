<?php

defined('_JEXEC') or exit;

use Joomla\CMS\Date\Date;

JLoader::register('SiakHelper', JPATH_COMPONENT.'/helpers/siak.php');

class SiakControllerKrs extends JControllerForm
{
    public function cancel($key = null)
    {
        JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
        parent::cancel($key);
        $this->setRedirect(JRoute::_('index.php?option=com_siak&view=krss', false));

        return true;
    }

    public function save($key = null, $urlVar = null)
    {
        JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
        $app = JFactory::getApplication();
        $model = $this->getModel('krs');
        $table = $model->getTable();

        $data = $this->input->post->get('jform', [], 'array');
        $siak = new SiakHelper();
        $vName = $this->input->getCmd('view');
        $checkin = property_exists($table, $table->getColumnAlias('checked_out'));
        $context = "{$this->option}.edit.{$this->context}";

        if (empty($key)) {
            $key = $table->getKeyName();
        }

        if (empty($urlVar)) {
            $urlVar = $key;
        }

        $recordId = $this->input->getInt($urlVar);
        $data[$key] = $recordId;
        empty($data['confirm_dw']) ? $data['confirm_dw'] = '-1' : $data['confirm_dw'];

        $form = $model->getForm($data, false);
        if (!$form) {
            $app->enqueueMessage($model->getError(), 'error');

            return false;
        }

        if ('wali' == $vName) {
            $fields = $form->getFieldset();
            foreach ($fields as $k => $val) {
                $form->setFieldAttribute($val->getAttribute('name'), 'required', 'false');
            }
        }

        $validData = $model->validate($form, $data);

        if (false === $validData) {
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

            $app->setUserState($context.'.data', $data);
            $this->setRedirect(
                \JRoute::_(
                    'index.php?option='.$this->option.'&view=krss&'
                    .$this->getRedirectToItemAppend($recordId, $urlVar),
                    false
                )
            );

            return false;
        }

        if (0 == $data['id']) {
            $tanggal = new Date();
            $validData['user_id'] = JFactory::getUser()->id;
            $validData['created_time'] = $tanggal->toSql();
            $validData['created_by'] = $validData['user_id'];
            $validData['confirm_dw'] = 0;
            $validData['dosen_wali'] = $siak->getDosenWali($validData['user_id']);
        }

        if (!$model->save($validData)) {
            // Save the data in the session.
            $app->setUserState($context.'.data', $validData);

            // Redirect back to the edit screen.
            $this->setError(\JText::sprintf('JLIB_APPLICATION_ERROR_SAVE_FAILED', $model->getError()));
            $this->setMessage($this->getError(), 'error');

            $this->setRedirect(
                \JRoute::_(
                    'index.php?option=com_siak&view=krs&layout=mahasiswa',
                    false
                )
            );

            return false;
        }

        if ($checkin && false === $model->checkin($validData[$key])) {
            // Save the data in the session.
            $app->setUserState($context.'.data', $validData);

            // Check-in failed, so go back to the record and display a notice.
            $this->setError(\JText::sprintf('JLIB_APPLICATION_ERROR_CHECKIN_FAILED', $model->getError()));
            $this->setMessage($this->getError(), 'error');

            $this->setRedirect(
                \JRoute::_(
                    'index.php?option='.$this->option.'&view=krss&'
                    .$this->getRedirectToItemAppend($recordId, $urlVar),
                    false
                )
            );

            return false;
        }

        //$this->setMessage(\JText::_('JLIB_APPLICATION'.(0 === $recordId && $app->isClient('site') ? '_SUBMIT' : '').'_SAVE_SUCCESS'));

        // Clear the record id and data from the session.
        $this->releaseEditId($context, $recordId);
        $app->setUserState($context.'.data', null);
        0 == $recordId ? $recordId = $model->getID($validData) : $recordId;
        $url = 'index.php?option='.$this->option.'&view=krs&layout=mk&id='.$recordId;
        // Redirect to the list screen.
        $this->setRedirect(\JRoute::_($url, false));

        // Invoke the postSave method to allow for the child class to access the model.
        $this->postSaveHook($model, $validData);

        return true;
    }

    public function savemk($key = null, $urlVar = null)
    {
        JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
        $app = \JFactory::getApplication();
        $model = $this->getModel('krs');
        $table = $model->getTable();

        $data = $this->input->post->get('jform', [], 'array');

        if (empty($key)) {
            $key = $table->getKeyName();
        }

        if (empty($urlVar)) {
            $urlVar = $key;
        }

        $recordId = $this->input->getInt($urlVar);
        $data[$key] = $recordId;

        if (!$model->saveMK($data['mk'], $data['id'])) {
            // Redirect back to the edit screen.
            $this->setError(\JText::sprintf('COM_SIAK_KRS_SAVE_MKS_ERROR', $model->getError()));
            $this->setMessage($this->getError(), 'error');

            $this->setRedirect(
                \JRoute::_(
                    'index.php?option='.$this->option.'&view=krss',
                    false
                )
            );

            return false;
        }

        if (!$model->save($data)) {
            // Redirect back to the edit screen.
            $this->setError(\JText::sprintf('JLIB_APPLICATION_ERROR_SAVE_FAILED', $model->getError()));
            $this->setMessage($this->getError(), 'error');

            $this->setRedirect(
                \JRoute::_(
                    'index.php?option='.$this->option.'&view=krss',
                    false
                )
            );

            return false;
        }
        $this->setMessage(\JText::_('COM_SIAK_TASK_SUCCESSFULL'));
        $this->setRedirect(
            \JRoute::_(
                'index.php?option='.$this->option.'&view=krss',
                false
            )
        );
    }

    public function deletemk($key = null)
    {
        //JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
        echo new JResponseJson($key);
    }
}
