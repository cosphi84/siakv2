<?php

use Joomla\CMS\Date\Date;
use Joomla\CMS\MVC\Controller\FormController;

defined('_JEXEC') or die;

class SiakControllerInputnilai extends FormController
{
    public function cancel($key = null)
    {
        $this->checkToken();
        $this->setRedirect(\JRoute::_('index.php?option=com_siak&view=mymk', false), JText::_('COM_SIAK_ADD_CANCELLED'));

        return false;
    }

    public function save($key = null, $urlVar = null)
    {
        $this->checkToken();

        $app = JFactory::getApplication();
        $data = $app->input->get('jform', [], 'array');
        $user = JFactory::getUser();
        $tanggal = new Date();
        $uri = JRoute::_('index.php?option=com_siak&view=mymk', false);

        foreach ($data as $key => $val) {
            $data[$key]['id'] = $key;
            $data[$key]['dosen'] = $user->id;
            $data[$key]['created_by'] = $user->id;
            $data[$key]['created_date'] = $tanggal->toSql();
        }

        $model = $this->getModel();

        if (!$model->save($data)) {
            $app->enqueueMessage($model->getError(), 'error');
            $app->redirect($uri);

            return;
        }

        $app->enqueueMessage('Simpan Data suksess!');
        $app->redirect($uri);

        return true;
    }
}
