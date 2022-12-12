<?php
/**
 * @package     Joomla.Siak
 * @subpackage  com_siakta
 *
 * @copyright   (C) 2022 @ Risam, S.T
 * @license     Limited for FT-UNTAG Cirebon use Only
 */

use Joomla\CMS\MVC\Controller\AdminController;

defined('_JEXEC') or die;

class SiaktaControllerTas extends AdminController
{
    public function getModel($name = 'Ta', $prefix = 'SiaktaModel', $config = array())
    {
        return parent::getModel($name, $prefix, $config);
    }

    public function download()
    {
        $this->checkToken();
        $this->setRedirect('index.php?option=com_siakta&view=tas&format=xlsx');
        return true;
    }
}
