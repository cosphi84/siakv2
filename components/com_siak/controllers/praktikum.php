<?php

use Joomla\CMS\Date\Date;

defined('_JEXEC') or die;

class SiakControllerPraktikum extends JControllerForm
{
    public function cancel($key = null)
    {
        JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
        parent::cancel($key);
        $this->setRedirect(JRoute::_('index.php?option=com_siak&view=praktikums'));

        return true;
    }

    public function save($key = null, $urlVar = null)
    {
        JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

        $app = JFactory::getApplication();
        $input = $app->input;
        $model = $this->getModel('Praktikum');

        $user = JFactory::getUser();
        $npm = $user->username;

        $date = new Date('now');
        $currentUri = JRoute::_('index.php?option=com_siak&view=praktikums', false);
        $data = $input->get('jform', [], 'array');

        $context = "{$this->option}.edit.{$this->context}";
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

            $app->setUserState($context.'.data', $data);

            $this->setRedirect(JURI::getInstance().'&layout=edit');

            return false;
        }
        $validData['user_id'] = $user->id;
        $validData['create_date'] = $date->toSql();
        if (false === $validData) {
            $errors = $model->getErrors();

            for ($i = 0, $n = count($errors); $i < $n && $i < 3; ++$i) {
                if ($errors[$i] instanceof Exception) {
                    $app->enqueueMessage($errors[$i]->getMessage(), 'warning');
                } else {
                    $app->enqueueMessage($errors[$i], 'warning');
                }
            }

            $app->setUserState($context.'.data', $data);
            $this->setRedirect(JRoute::_(JURI::getInstance().'&layout=edit', false));

            return false;
        }

        if (!$model->save($validData)) {
            $app->setUserState($context.'.data', $validData);

            $this->setError(JText::sprintf('JLIB_APPLICATION_ERROR_SAVE_FAILED', $model->getError()));
            $this->setMessage($this->getError(), 'error');
            $this->setRedirect(JRoute::_(JURI::getInstance().'&layout=edit', false));

            return false;
        }

        $app->setUserState($context.'.data', null);

        $this->setRedirect(
            $currentUri,
            JText::_('COM_SIAK_ADD_SUCCESSFUL')
        );

        return true;
    }
}
