<?php

defined('_JEXEC') or exit;

class SiakControllerUser extends JControllerForm
{
    protected $text_prefix = 'COM_SIAK_USER';

    public function getModel($name = 'User', $prefix = 'SiakModel', $config = [])
    {
        return parent::getModel($name, $prefix, $config);
    }

    public function fupdate()
    {
        $this->checkToken();
        $model = $this->getModel();
        if ($model->resetBio()) {
            $msg = JText::_('COM_SIAK_USER_RESET_BIO_DONE');
        } else {
            $msg = JText::_('COM_SIAK_USER_RESET_BIO_FAIL');
        }

        $this->setRedirect('index.php?option=com_siak&view=dosens', $msg, 'info');
    }

    public function cancel($key = null)
    {
        $this->checkToken();
        $context = "{$this->option}.edit.{$this->context}";
        $uid = \JFactory::getApplication()->getUserState($context.'.userID');

        $model = $this->getModel();
        $table = $model->getTable();
        $context = "{$this->option}.edit.{$this->context}";

        if (empty($key)) {
            $key = $table->getKeyName();
        }

        $recordId = (int) $model->getRecordID($uid);

        // Attempt to check-in the current record.
        if ($recordId && property_exists($table, 'checked_out') && false === $model->checkin($recordId)) {
            // Check-in failed, go back to the record and display a notice.
            $this->setError(\JText::sprintf('JLIB_APPLICATION_ERROR_CHECKIN_FAILED', $model->getError()));
            $this->setMessage($this->getError(), 'error');

            $this->setRedirect(
                \JRoute::_(
                    'index.php?option='.$this->option.'&view='.$this->view_item
                    .$this->getRedirectToItemAppend($recordId, $key),
                    false
                )
            );

            return false;
        }

        // Clean the session data and redirect.
        $this->releaseEditId($context, $recordId);
        \JFactory::getApplication()->setUserState($context.'.data', null);

        $grupMhs = JComponentHelper::getParams('com_siak')->get('grpMahasiswa');
        $user = JFactory::getUser($uid)->get('groups');
        $view = 'dosens';
        if (in_array($grupMhs, $user)) {
            $view = 'mahasiswas';
        }

        $url = 'index.php?option='.$this->option.'&view='.$view
            .$this->getRedirectToListAppend();

        // Check if there is a return value
        $return = $this->input->get('return', null, 'base64');

        if (!is_null($return) && \JUri::isInternal(base64_decode($return))) {
            $url = base64_decode($return);
        }

        // Redirect to the list screen.
        $this->setRedirect(\JRoute::_($url, false));

        return true;
    }

    public function edit($key = null, $urlVar = null)
    {
        $this->checkToken();
        $app = \JFactory::getApplication();
        $app->allowCache(false);

        $model = $this->getModel();
        $table = $model->getTable();
        $cid = $app->input->get('cid', [], 'array');
        $context = "{$this->option}.edit.{$this->context}";

        if (empty($key)) {
            $key = $table->getKeyName();
        }

        if (empty($urlVar)) {
            $urlVar = $key;
        }

        $recordId = (int) (count($cid) ? $cid[0] : $this->input->getInt($urlVar));
        $checkin = property_exists($table, $table->getColumnAlias('checked_out'));

        $user_id = $model->getUid($recordId);

        $app->setUserState($context.'.userID', $user_id);

        $grupMhs = JComponentHelper::getParams('com_siak')->get('grpMahasiswa');
        $user = JFactory::getUser($user_id)->get('groups');
        $view = 'dosens';
        if (in_array($grupMhs, $user)) {
            $view = 'mahasiswas';
        }
        // Access check.
        if (!$this->allowEdit([$key => $recordId], $key)) {
            $this->setError(\JText::_('JLIB_APPLICATION_ERROR_EDIT_NOT_PERMITTED'));
            $this->setMessage($this->getError(), 'error');

            $this->setRedirect(
                \JRoute::_(
                    'index.php?option='.$this->option.'&view='.$view
                    .$this->getRedirectToListAppend(),
                    false
                )
            );

            return false;
        }

        // Attempt to check-out the new record for editing and redirect.
        if ($checkin && !$model->checkout($recordId)) {
            $this->setError(\JText::sprintf('JLIB_APPLICATION_ERROR_CHECKOUT_FAILED', $model->getError()));
            $this->setMessage($this->getError(), 'error');

            $this->setRedirect(
                \JRoute::_(
                    'index.php?option='.$this->option.'&view='.$view
                    .$this->getRedirectToItemAppend($recordId, $urlVar),
                    false
                )
            );

            return false;
        }

        // Check-out succeeded, push the new record id into the session.
        $this->holdEditId($context, $recordId);
        $app->setUserState($context.'.data', null);

        $this->setRedirect(
            \JRoute::_(
                'index.php?option='.$this->option.'&view=user'
                    .$this->getRedirectToItemAppend($recordId, $urlVar),
                false
            )
        );

        return true;
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
        $currentUri = (string) JUri::getInstance();
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
        $context = "{$this->option}.edit.{$this->context}";

        if (!$this->allowSave($data, $key)) {
            $this->setError(\JText::_('JLIB_APPLICATION_ERROR_SAVE_NOT_PERMITTED'));
            $this->setMessage($this->getError(), 'error');

            $this->setRedirect($currentUri);

            return false;
        }

        $form = $model->getForm($data, false);
        if (!$form) {
            $app->enqueueMessage($model->getError(), 'error');

            return false;
        }

        $validData = $model->validate($form, $data);
        if (false === $validData) {
            $errors = $model->getErrors();
            for ($i = 0, $n = count($errors); $i < $n && $i < 3; ++$i) {
                if ($errors[$i] instanceof Exception) {
                    $app->enqueueMessage($errors[$i]->getMessage(), 'warning');
                } else {
                    $app->enqueueMessage($errors[$i], 'warning');
                }
            }
            $app->setUserState($context.'data', $data);
            $this->setRedirect($currentUri);

            return false;
        }
        $validData['created_user'] = $user->id;
        $validData['created_time'] = date('Y-m-d HL:i:s');

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

        $grupMhs = JComponentHelper::getParams('com_siak')->get('grpMahasiswa');
        $user = JFactory::getUser($validData['user_id'])->get('groups');
        $view = 'dosens';
        if (in_array($grupMhs, $user)) {
            $view = 'mahasiswas';
        }

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
                        'index.php?option='.$this->option.'&view='.$view
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
                        'index.php?option='.$this->option.'&view='.$view
                        .$this->getRedirectToItemAppend(null, $urlVar),
                        false
                    )
                );

                break;
            default:
                // Clear the record id and data from the session.
                $this->releaseEditId($context, $recordId);
                $app->setUserState($context.'.data', null);

                $url = 'index.php?option='.$this->option.'&view='.$view
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
