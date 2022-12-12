<?php

defined('_JEXEC') or exit;

class SiakControllerRuangan extends JControllerForm
{
    protected $text_prefix = 'COM_SIAK_RUANGAN';

    public function getModel($name = 'Ruangan', $prefix = 'SiakModel', $config = [])
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
            $app->setUserState($context.'data'.$data);
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

                $this->setRedirect(\JRoute::_($url, false));

                break;
        }

        $this->postSaveHook($model, $validData);

        return true;
    }
}
