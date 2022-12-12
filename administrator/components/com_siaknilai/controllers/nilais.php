<?php

defined('_JEXEC') or die;

use Joomla\CMS\MVC\Controller\AdminController;

class SiaknilaiControllerNilais extends AdminController
{
    public function getModel($name = 'Nilai', $prefix = 'SiaknilaiModel', $config = array('ignore_request'=>true))
    {
        $model = parent::getModel($name, $prefix, $config);
        return $model;
    }


    public function excel()
    {
        $this->checkToken();
        $this->setRedirect('index.php?option=com_siaknilai&view=nilais&format=xlsx');
        return true;
    }

    public function pdf()
    {
        $this->checkToken();
        $this->setRedirect('index.php?option=com_siaknilai&view=nilais&format=pdf');
        return true;
    }

    public function show()
    {
        $this->checkToken();
    }

    public function hide()
    {
        $this->checkToken();
        $this->setRedirect('index.php?option=com_siaknilai&view=nilais&format=xlsx');
        return true;
    }
}
