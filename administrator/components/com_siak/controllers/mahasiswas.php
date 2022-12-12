<?php

defined('_JEXEC') or exit;

use Joomla\Utilities\ArrayHelper;

class SiakControllerMahasiswas extends JControllerAdmin
{
    public function getModel($name = 'Mahasiswa', $prefix = 'SiakModel', $config = [])
    {
        return parent::getModel($name, $prefix, $config);
    }

    public function confirm()
    {
        $this->checkToken();
        $app = JFactory::getApplication();
        $user = JFactory::getUser();
        $cids = (array) $this->input->get('cid', [], 'array');

        if (count($cids) < 1) {
            $app->enqueueMessage(JText::_('JGLOBAL_NO_ITEM_SELECTED'), 'notice');
        } else {
            if (count($cids) > 0) {
                $model = $this->getModel();
                $cids = ArrayHelper::toInteger($cids);

                if (!$model->publish($cids)) {
                    $this->setMessage($model->getError(), 'error');
                } else {
                    $this->setMessage(JText::plural('COM_SIAK_N_ITEM_CONFIRMED', count($cids)));
                }
            }
        }
        $this->setRedirect('index.php?option=com_siak&view=mahasiswas');
    }

    public function lulus()
    {
        $this->checkToken();
        $app = JFactory::getApplication();
        $user = JFactory::getUser();
        $cids = (array) $this->input->get('cid', [], 'array');

        if (count($cids) < 1) {
            $app->enqueueMessage(JText::_('JGLOBAL_NO_ITEM_SELECTED'), 'notice');
        } else {
            if (count($cids) > 0) {
                $model = $this->getModel();
                $cids = ArrayHelper::toInteger($cids);

                if (!$model->setStatus($cids, '2')) {
                    $this->setMessage($model->getError(), 'error');
                } else {
                    $this->setMessage(JText::plural('COM_SIAK_N_ITEM_LULUS', count($cids)));
                }
            }
        }
        $this->setRedirect('index.php?option=com_siak&view=mahasiswas');
    }

    public function excel()
    {
        $this->checkToken();
        $this->setRedirect('index.php?option=com_siak&view=mahasiswas&format=xlsx');
    }
}
