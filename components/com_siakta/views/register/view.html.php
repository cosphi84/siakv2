<?php
/**
 * @package     Joomla.Siak
 * @subpackage  Siak TA
 *
 * @copyright   (C) 2022 @ Risam, S.T
 * @license     Limited for FT-UNTAG Cirebon use Only
 */

use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\View\HtmlView;
use Joomla\CMS\Router\Route;

defined('_JEXEC') or die;

class SiaktaViewRegister extends HtmlView
{
    protected $form;

    public function display($tpl = null)
    {
        $app = Factory::getApplication();
        $doc = Factory::getDocument();
        $user = Factory::getUser();
        $usrGroups = $user->get('groups');
        $grpMhs = ComponentHelper::getParams('com_siak')->get('grpMahasiswa');

        if (!in_array($grpMhs, $usrGroups)) {
            $app->enqueueMessage(Text::_('COM_SIAKTA_FORM_ACCES_DEINIED'), 'info');
            $app->redirect(Route::_('index.php?option=com_siakta&view=tas'));

            return false;
        }

        $params = ComponentHelper::getParams('com_siakta');
        if (!$params->get('ta_en')) {
            $tpl = 'off';
            parent::display($tpl);
            return false;
        }

        $this->form = $this->get('Form');

        $errors = $this->get('Errors');

        if (count($errors) > 0) {
            throw new Exception(implode("<br />", $errors), 500);
            return false;
        }

        $title = Text::_('COM_SIAKTA_VIEW_REGISTER_PAGE_TITLE');
        $title = Text::sprintf('JPAGETITLE', $title, $app->get('sitename'));
        $doc->setTitle($title);
        Text::script('COM_SIAKTA_ISIAN_SALAH');

        parent::display($tpl);
    }
}
