<?php
/**
 * @package     Joomla.Siak
 * @subpackage  com_siakta
 *
 * @copyright   (C) 2022 @ Risam, S.T
 * @license     Limited for FT-UNTAG Cirebon use Only
 */

use Joomla\CMS\MVC\Controller\FormController;

 defined('_JEXEC') or die();

 class SiaktaControllerTa extends FormController
 {
     public function getModel($name = 'Ta', $prefix = 'SiaktaModel', $config = array('ignore_request' => true))
     {
         return parent::getModel($name, $prefix, $config);
     }
 }
