<?php

defined('_JEXEC') or exit;

class SiakViewIndustries extends JViewLegacy
{
    public $activeFlters;
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
        $this->canDo = JHelperContent::getActions('com_siak');
        $errors = $this->get('Errors');
        if (count($errors) > 0) {
            throw new Exception(implode('<br /', $errors), 500);

            return false;
        }

        SiakHelper::submenuFakultas('Industries');

        if ('modal' !== $this->getLayout()) {
            $this->addToolbar();
            $this->sidebar = JHtmlSidebar::render();
        }

        parent::display($tpl);
    }

    protected function addToolbar()
    {
        $canDo = $this->canDo;
        $bar = JToolbar::getInstance('toolbar');

        JToolbarHelper::title(JText::_('COM_SIAK_INDUSTRIES_PAGE_TITLE'), 'database');

        if ($canDo->get('core.create')) {
            JToolbarHelper::addNew('industri.add');
        }

        if ($canDo->get('core.edit')) {
            JToolbarHelper::editList('industri.edit');
        }

        if ($canDo->get('core.edit.state')) {
            JToolbarHelper::publish('industries.publish');
            JToolbarHelper::unpublish('industries.unpublish');
        }
        JToolbarHelper::custom('industries.excel', 'download', 'download', 'Unduh Excel', false);
        if ($canDo->get('core.delete')) {
            JToolbarHelper::trash('industries.trash');
            JToolbarHelper::deleteList('JGLOBAL_CONFIRM_DELETE', 'industries.delete', 'JTOOLBAR_DELETE');
        }

        JToolbarHelper::divider();
        if ($canDo->get('core.admin') || $canDo->get('core.options')) {
            JToolbarHelper::preferences('com_siak');
        }
    }
}
