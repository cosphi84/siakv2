<?php
/**
 * @package     Joomla.Siak
 * @subpackage  com_siaknilai
 *
 * @copyright   (C) 2022 @ Risam, S.T
 * @license     Limited for FT-UNTAG Cirebon use Only
 */

use Joomla\CMS\Factory;
use Joomla\CMS\MVC\Controller\BaseController;

defined('_JEXEC') or die;

JLoader::register('SiaknilaiHelper', __DIR__.'/helpers/siaknilai.php');

$ctr = BaseController::getInstance('Siaknilai');
$task = Factory::getApplication()->input->get('task', 'display');
$ctr->execute($task);
$ctr->redirect();
