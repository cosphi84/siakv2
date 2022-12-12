<?php
/**
 * @package     Joomla.Siak
 * @subpackage  Siakta
 *
 * @copyright   (C) 2022 @ Risam, S.T
 * @license     Limited for FT-UNTAG Cirebon use Only
 */

use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\Controller\FormController;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Session\Session;
use Joomla\CMS\Uri\Uri;

 defined('_JEXEC') or die;

 class SiaktaControllerRegister extends FormController
 {
    public function cancel($key = null)
    {
        parent::cancel($key);
        $this->setRedirect(
            Route::_('index.php?option=com_siak'),
            Text::_('COM_SIAKTA_OPERASI_CANCEL_MSG'),
            'message'
        );
        
    }


    public function save($key = null, $urlVar = null)
    {
        if(!Session::checkToken())
        {
            jexit(Text::_('JINVALID_TOKEN'));
        }

        $app        = Factory::getApplication();
        $model      = $this->getModel();
        $user       = Factory::getUser();
        $usrGroups  = $user->get('groups');
        $grpMhs     = ComponentHelper::getParams('com_siak')->get('grpMahasiswa');

        $currentUrl = (string) Uri::getInstance();


        // Just Mahasiswa
        if(!in_array($grpMhs, $usrGroups))
        {
            $this->setRedirect(
                Route::_('index.php?option=com_siak'),
                Text::_('JGLOBAL_AUTH_ACCESS_DENIED'),
                'error'
            );
        }

        $data = $app->input->get('jform', array(), 'array');
        $context = $this->option.'edit.register';

        $form = $model->getForm($data, false);
        if(!$form)
        {
            $app->enqueueMessage($model->getError(), 'error');
            return false;
        }

        $dataOK = $model->validate($form, $data);
        if($dataOK === false)
        {
            $errors = $model->getErrors();
            for($i = 0, $j = count($errors); $i < $j && $i < 3; $i++){
                if($errors[$i] instanceof Exception){
                    $app->enqueueMessage($errors[$i]->getMessage(), 'warning');
                } else {
                    $app->enqueueMessage($errors[$i], 'warning');
                }
            }
            $app->setUserState($context.'.data', $data);
            $this->setRedirect($currentUrl);
            return false;
        }

        if(!$model->save($dataOK)){
            $app->setUserState($context.'.data', $dataOK);
            $this->setError(JText::sprintf('JLIB_APPLICATION_ERROR_SAVE_FAILED', $model->getError()));
			$this->setMessage($this->getError(), 'error');

			$this->setRedirect($currentUrl);

			return false;
        }

        $app->setUserState($context.'.data', null);

        $this->setRedirect(
            Route::_($currentUrl),
            Text::_('COM_SIAKTA_REGISTER_SUCCESS')
        );

        return true;

    }
 }