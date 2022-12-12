<?php

use Joomla\CMS\Factory;
use Joomla\CMS\MVC\Controller\BaseController;
use Joomla\Utilities\ArrayHelper;

defined('_JEXEC') or exit;

class SiakController extends BaseController
{
    protected $default_view = 'prodis';

    public function display($cachable = false, $urlparams = false)
    {
        $app = Factory::getApplication();
        $view = $app->input->get('view', $this->default_view);
        $filter = $app->input->get('filter', array(), 'array');
        $prodi = ArrayHelper::getValue($filter, 'prodi');

        $app->setUserState('com_siak.'.$view.'.filter.prodi', $prodi);
        $app->setUserState('com_siak.'.$view.'.filter.jurusan', ArrayHelper::getValue($filter, 'jurusan'));
        parent::display($cachable, $urlparams);
    }
}
