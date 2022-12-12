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
use Joomla\CMS\Uri\Uri;

defined('_JEXEC') or die;

class SiaktaViewTa extends HtmlView
{
    protected $item;
    protected $form;


    public function display($tpl = null)
    {
        $app = Factory::getApplication();
        $doc = Factory::getDocument();
        $this->item = $this->get('Item');
        $this->form = $this->get('Form');

        $errors = $this->get('Errors');

        if (count($errors) > 0) {
            throw new Exception(implode("<br />", $errors), 500);
            return false;
        }

        $title = Text::_('COM_SIAKTA_VIEW_DETAIL_PAGE_TITLE');
        $title = Text::sprintf('JPAGETITLE', $title, $app->get('sitename'));
        $doc->setTitle($title);
        $doc->addStyleSheet(Uri::root() . 'media/com_siakta/css/siakta.css', array('version'=>'auto'));

        parent::display($tpl);
    }
}
