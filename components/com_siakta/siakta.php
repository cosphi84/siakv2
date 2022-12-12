<?php
/**
 * @package     Joomla.Siak
 * @subpackage  com_siak TA
 *
 * @copyright   (C) 2022 @ Risam, S.T
 * @license     Limited for FT-UNTAG Cirebon use Only
 */

use Joomla\CMS\Factory;
use Joomla\CMS\MVC\Controller\BaseController;

 defined('_JEXEC') or die;


 $controller = BaseController::getInstance('Siakta');
 $task = Factory::getApplication()->input->get('task', 'display');
 $controller->execute($task);
 $controller->redirect();
