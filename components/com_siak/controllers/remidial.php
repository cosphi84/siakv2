<?php

defined('_JEXEC') or exit;

use Joomla\CMS\Date\Date;

class SiakControllerRemidial extends JControllerForm
{
    public function cancel($key = null)
    {
        JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
        parent::cancel($key);
        $this->setRedirect(JRoute::_('index.php?option=com_siak&view=sps'));

        return true;
    }

    public function save($key = null, $urlVar = null)
    {
        JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

        $app = JFactory::getApplication();
        $input = $app->input->get('jform', [], 'array');
        $model = $this->getModel();
        $form = $model->getForm($input, false);
        $uri = (string) JUri::getInstance();
        $date = new Date('now');

        if (!$form) {
            $app->enqueueMessage($model->getError(), 'error');

            return false;
        }

        $validData = $model->validate($form, $input);
        if (false === $validData) {
            $errors = $model->getErrors();

            for ($i = 0, $n = count($errors); $i < $n && $i < 3; ++$i) {
                if ($errors[$i] instanceof Exception) {
                    $app->enqueueMessage($errors[$i]->getMessage(), 'warning');
                } else {
                    $app->enqueueMessage($errors[$i], 'warning');
                }
            }

            $this->setRedirect($uri);

            return false;
        }

        $validData['input_nilai_by'] = JFactory::getUser()->id;
        $validData['input_nilai_time'] = $date->toSql();
        $validData['state'] = 2;
        // Attempt to save the data.
        if (!$model->save($validData)) {
            $this->setError(JText::sprintf('JLIB_APPLICATION_ERROR_SAVE_FAILED', $model->getError()));
            $this->setMessage($this->getError(), 'error');

            $this->setRedirect($uri);

            return false;
        }

        $this->setRedirect(
            JRoute::_('index.php?option=com_siak&view=sps'),
            'Input Nilai Remidial berhasil'
        );

        return true;
    }
}
