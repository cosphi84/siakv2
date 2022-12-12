<?php
/**
 * @package     Joomla.Siak
 * @subpackage  com_siakta
 *
 * @copyright   (C) 2022 @ Risam, S.T
 * @license     Limited for FT-UNTAG Cirebon use Only
 */

use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\Controller\BaseController;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Uri\Uri;

 defined('_JEXEC') or die;

 /**
  * Siakta controller
  * @since 0.0.1
  */
  class SiaktaController extends BaseController
  {
      public function display($cachable = false, $urlparams = array())
      {
          $app = Factory::getApplication();

          // validate user
          $user = Factory::getUser();
          if ($user->get('guest') == 1) {
              $url = Uri::getInstance();
              $url = base64_encode($url);
              $login_page = Route::_('index.php?option=com_users&view=login&return='.$url, false);
              $this->setRedirect($login_page, Text::_('JERROR_ALERTNOAUTHOR'), 'error');

              return false;
          }

          $doc = Factory::getDocument();
          $vName = $this->input->getCmd('view');
          $vFormat = $doc->getType();
          $lName = $this->input->getCmd('layout', 'default');

          if ($view = $this->getView($vName, $vFormat)) {
              $model = $this->getModel($vName);
          }

          $view->setModel($model, true);
          $view->setLayout($lName);
          $view->document = $doc;
          $view->display();
      }
  }
