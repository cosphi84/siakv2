<?php


defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\MVC\Controller\BaseController;

$controller = BaseController::getInstance('Siakusers');
$controller->execute(Factory::getApplication()->input->get('task'));
$controller->redirect();
