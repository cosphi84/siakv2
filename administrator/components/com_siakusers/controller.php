<?php

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\MVC\Controller\BaseController;
use Joomla\Utilities\ArrayHelper;

class SiakusersController extends BaseController
{
    protected $default_view = 'dashboard';

    public function display($cachable = false, $urlparams = array())
    {
        $app = Factory::getApplication();
        $mode = $app->input->get('mode', 0);
        $filter = $app->input->get('filter', array(), 'array');
        $prodi = ArrayHelper::getValue($filter, 'prodi_id');
        $app->setUserState('com_siakusers.mode', $mode);
        $app->setUserState('com_siakusers.filter.prodi_id', $prodi);

        parent::display($cachable, $urlparams);
    }
}
