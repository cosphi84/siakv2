<?php

use Joomla\CMS\Date\Date;

defined('_JEXEC') or exit;

class SiakControllerKonfirmasi extends JControllerAdmin
{
    public function cancel($key = null)
    {
        $this->checkToken();
        $this->setRedirect(\JRoute::_('index.php?option=com_siak&view=konfirmasiku', false), JText::_('COM_SIAK_ADD_CANCELLED'));

        return false;
    }

    public function save($key = null, $urlvar = null)
    {
        $this->checkToken();
        $app = JFactory::getApplication();
        $data = $app->input->get('jform', [], 'array');
        $currentUri = JUri::getInstance();

        $model = $this->getModel('konfirmasi');
        $form = $model->getForm($data, false);

        if (!$form) {
            $app->enqueueMessage($model->getError(), 'error');

            return false;
        }

        $dataOK = $model->validate($form, $data);
        $dataOK['user_id'] = JFactory::getUSer()->id;
        $sekarang = new Date();
        $dataOK['create_date'] = $sekarang->toSql();

        if (!$model->save($dataOK)) {
            $app->setUserState('com_siak.mahasiswa.status', $data);

            $this->setError(JText::sprintf('JLIB_APPLICATION_ERROR_SAVE_FAILED', $model->getError()));
            $this->setMessage($this->getError(), 'error');

            $this->setRedirect($currentUri);

            return false;
        }

        $app->setUserState('com_siak.mahasiswa.status', null);
        $app->redirect(JRoute::_('index.php?option=com_siak&view=konfirmasiku'), JText::_('COM_SIAK_ADD_SUCCESSFUL'));

        return true;
    }
}
