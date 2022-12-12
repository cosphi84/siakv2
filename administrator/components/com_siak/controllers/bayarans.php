<?php

defined('_JEXEC') or exit;

use Joomla\CMS\Date\Date;
use Joomla\Utilities\ArrayHelper;

class SiakControllerBayarans extends JControllerAdmin
{
    public function getModel($name = 'Bayaran', $prefix = 'SiakModel', $config = [])
    {
        return parent::getModel($name, $prefix, $config);
    }

    public function xlsx()
    {
        $this->checkToken();
        $this->setRedirect('index.php?option=com_siak&view=bayarans&format=xlsx');
    }

    public function lunas()
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

                if (!$model->lunas($cids[0])) {
                    $this->setMessage($model->getError(), 'error');
                } else {
                    $this->setMessage(JText::_('COM_SIAK_TAGIHAN_LUNAS'));
                }
            }
        }
        $this->setRedirect('index.php?option=com_siak&view=bayarans');
    }

    public function belumlunas()
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
                //set Lunas => 0 : Belum bayar
                //set Lunas => 1 : belum lunas
                //set Lunas => 2 : Sudah Lunas
                if (!$model->lunas($cids[0], 1)) {
                    $this->setMessage($model->getError(), 'error');
                } else {
                    $this->setMessage(JText::_('COM_SIAK_TAGIHAN_LUNAS'));
                }
            }
        }
        $this->setRedirect('index.php?option=com_siak&view=bayarans');
    }

    public function confirm()
    {
        $this->checkToken();

        $user = JFactory::getUser();
        $cids = (array) $this->input->get('cid', [], 'array');
        $tanggal = new Date('now');
        $timezone = $user->getTimezone();
        $tanggal->setTimezone($timezone);
        $data = [];
        $data['id'] = $cids[0];
        $data['confirm'] = '1';
        $data['confirm_time'] = $tanggal->toSql();
        $data['confirm_user'] = $user->id;
        $data['confirm_note'] = '';
        $model = $this->getModel();
        if (!$model->save($data)) {
            $this->setMessage($model->getError(), 'error');
        } else {
            $this->setMessage(JText::_('COM_SIAK_TAGIHAN_CONFIRMED'));
        }
        $this->setRedirect('index.php?option=com_siak&view=bayarans');
    }
}
