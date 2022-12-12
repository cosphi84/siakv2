<?php

defined('_JEXEC') or exit;

class SiakViewKrss extends JViewLegacy
{
    public $activeFilters;
    public $filterForm;
    protected $items;
    protected $pagination;
    protected $state;
    protected $canDo;

    public function display($tpl = null)
    {
        $this->items = $this->get('Items');
        $this->pagination = $this->get('Pagination');
        $this->activeFilters = $this->get('ActiveFilters');
        $this->filterForm = $this->get('FilterForm');
        $this->state = $this->get('State');
        $errors = $this->get('Errors');
        $this->canDo = JHelperContent::getActions('com_siak');

        if (count($errors) > 0) {
            throw new Exception(implode("\n", $errors), 500);

            return false;
        }

        SiakSubmenu::submenuAkademik('Krss');

        $this->addTolbar();
        $this->sidebar = JHtmlSidebar::render();

        parent::display($tpl);
    }

    protected function addTolbar()
    {
        $canDo = $this->canDo;

        JToolbarHelper::title(JText::_('COM_SIAK_KRSS_PAGE_TITLE'), 'pencil-2');
        if ($canDo->get('core.edit')) {
            JToolbarHelper::editList('krs.edit');
        }
        //JToolbarHelper::custom('krss.excel', 'download', 'download', 'Unduh Excel', false);
        JToolbarHelper::custom('krss.pdf', 'download', 'download', 'Unduh PDF', true);
        if ($canDo->get('core.delete')) {
            JToolbarHelper::deleteList('JGLOBAL_CONFIRM_DELETE', 'krss.delete', 'JTOOLBAR_DELETE');
        }
        //JToolbarHelper::custom('krss.detail', 'file-2', 'file-2', 'Detail KRS', true);
        JToolbarHelper::divider();
        if ($canDo->get('core.admin') || $canDo->get('core.options')) {
            JToolbarHelper::preferences('com_siak');
        }
    }
}
