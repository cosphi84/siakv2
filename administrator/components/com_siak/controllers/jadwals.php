<?php

defined('_JEXEC') or exit;

use Joomla\CMS\MVC\Controller\AdminController;
use Joomla\CMS\Router\Route;

class SiakControllerJadwals extends AdminController
{
    public function getModel($name = 'Jadwal', $prefix = 'SiakModel', $config = [])
    {
        return parent::getModel($name, $prefix, $config);
    }

    public function excel()
    {
        $this->checkToken();
        $this->setRedirect(Route::_('index.php?option=com_siak&view=jadwals&format=xlsx', false));
        return true;
    }
}
