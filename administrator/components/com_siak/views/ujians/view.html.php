<?php

defined('_JEXEC') or exit;

use Joomla\CMS\MVC\View\HtmlView;
use Joomla\CMS\Toolbar\Toolbar;
use Joomla\CMS\Toolbar\ToolbarHelper;
use Joomla\CMS\Uri\Uri;

class SiakViewUjians extends HtmlView
{
    public $filterForm;
    public $activeFilters;
    protected $items;
    protected $pagination;
    protected $state;
    protected $canDo;
    protected $db;

    public function display($tpl = null)
    {
        $this->items = $this->get('Items');
        $this->pagination = $this->get('Pagination');
        $this->state = $this->get('State');
        $this->filterForm = $this->get('FilterForm');
        $this->activeFilters = $this->get('ActiveFilters');
        $this->canDo = JHelperContent::getActions('com_siak');

        SiakSubmenu::submenuAkademik('Ujians');

        if (count($errors = $this->get('Errors'))) {
            throw new Exception(implode("\n", $errors), 500);

            return false;
        }

        $this->addToolbar();
        $this->sidebar = JHtmlSidebar::render();

        parent::display($tpl);
    }

    protected function addToolbar()
    {
        $canDo = $this->canDo;

        $bar = Toolbar::getInstance('toolbar');
        ToolbarHelper::title(JText::_('COM_SIAK_VIEW_UJIANS_PAGE_TITLE'), 'database');

        if ($canDo->get('core.create')) {
            ToolbarHelper::addNew('ujian.add');
        }

        if ($canDo->get('core.edit')) {
            ToolbarHelper::editList('ujian.edit');
        }

        if ($canDo->get('core.edit.state')) {
            ToolbarHelper::publish('ujians.publish');
            ToolbarHelper::unpublish('ujians.unpublish');
            ToolbarHelper::trash('ujians.trash');
        }

        if ($canDo->get('core.delete')) {
            ToolbarHelper::deleteList('JGLOBAL_CONFIRM_DELETE', 'ujians.delete', 'JTOOLBAR_DELETE');
        }
        ToolbarHelper::link(Uri::getInstance().'&format=xlsx', 'Download');
        //ToolbarHelper::custom('ujians.xlsx', 'download', 'download', 'Download', false);

        ToolbarHelper::divider();
        if ($canDo->get('core.admin') || $canDo->get('core.options')) {
            ToolbarHelper::preferences('com_siak');
        }
    }
}
