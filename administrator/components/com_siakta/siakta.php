<?php
/**
 * @package     Joomla.Siak
 * @subpackage  com_siakta
 *
 * @copyright   (C) 2022 @ Risam, S.T
 * @license     Limited for FT-UNTAG Cirebon use Only
 */

use Joomla\CMS\Factory;
use Joomla\CMS\MVC\Controller\BaseController;

defined('_JEXEC') or die;

JLoader::register('SiaktaHelper', __DIR__.'/helpers/siakta.php');

$ctr = BaseController::getInstance('Siakta');
$task = Factory::getApplication()->input->get('task', 'display');
$ctr->execute($task);
$ctr->redirect();