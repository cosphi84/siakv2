<?php
/**
 * @package     Joomla.Siak
 * @subpackage  com_siakta
 *
 * @copyright   (C) 2022 @ Risam, S.T
 * @license     Limited for FT-UNTAG Cirebon use Only
 */

use Joomla\CMS\Factory;
use Joomla\CMS\Helper\ContentHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\View\HtmlView;
use Joomla\CMS\Toolbar\ToolbarHelper;

defined('_JEXEC') or die();

class SiaktaViewTa extends HtmlView
{
    protected $form;
    protected $item;

    public function display($tpl = null)
    {
        $this->form = $this->get('Form');
        $this->item = $this->get('Item');

        if (count($errors = $this->get('Errors'))) {
            throw new Exception(implode('\n', $errors), 500);
            return false;
        }

        

        Factory::getApplication()->input->set('hidemainmenu', 1);
        $this->drawToolbar();
        parent::display($tpl);
    }

    protected function drawToolbar()
    {
        $canDo = ContentHelper::getActions('com_siakta');

        empty($this->item->id) ? $title = Text::_('COM_SIAKTA_TA_NEW_ITEM_PAGE_TITLE') : $title = Text::_('COM_SIAKTA_TA_NEW_ITEM_PAGE_TITLE');

        ToolbarHelper::title($title);

        if($canDo->get('core.create')){
            ToolbarHelper::apply('ta.apply');
            ToolbarHelper::save('ta.save');
        }

        if(empty($this->item->id))
        {
            ToolbarHelper::cancel('ta.cancel');
        }else{
            ToolbarHelper::cancel('ta.cancel', 'JTOOLBAR_CLOSE');
        }
    }
}
