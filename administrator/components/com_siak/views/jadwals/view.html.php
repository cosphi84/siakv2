<?php

defined('_JEXEC') or die;

use Joomla\CMS\MVC\View\HtmlView;

class SiakViewJadwals extends HtmlView
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
        $this->db = JFactory::getDbo();

        SiakSubmenu::submenuAkademik('Jadwals');

        if (count($errors = $this->get('Errors'))) {
            throw new Exception(implode("\n", $errors), 500);

            return false;
        }

        $this->addTolbar();
        $this->sidebar = JHtmlSidebar::render();

        parent::display($tpl);
    }

    protected function addTolbar()
    {
        $canDo = $this->canDo;
        $user = JFactory::getUser();

        $bar = JToolbar::getInstance('toolbar');

        JToolbarHelper::title(JText::_('COM_SIAK_JADWALS_PAGE_TITLE'), 'calendar');

        if ($canDo->get('core.create')) {
            JToolbarHelper::addNew('jadwal.add');
        }

        if ($canDo->get('core.edit')) {
            JToolbarHelper::editList('jadwal.edit');
        }

        if ($canDo->get('core.edit.state')) {
            JToolbarHelper::publish('jadwals.publish');
            JToolbarHelper::unpublish('jadwals.unpublish');
            JToolbarHelper::trash('jadwals.trash');
        }

        if ($canDo->get('core.delete')) {
            JToolbarHelper::deleteList('JGLOBAL_CONFIRM_DELETE', 'jadwals.delete', 'JTOOLBAR_DELETE');
        }

        JToolbarHelper::custom('jadwals.excel', 'download', 'download', 'Download Excel', false);

        JToolbarHelper::divider();
        if ($canDo->get('core.admin') || $canDo->get('core.options')) {
            JToolbarHelper::preferences('com_siak');
        }
    }
}
