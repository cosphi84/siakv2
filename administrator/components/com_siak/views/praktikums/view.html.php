<?php

defined('_JEXEC') or exit;

class SiakViewPraktikums extends JViewLegacy
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
        $this->canDo = JHelperContent::getActions('com_siak');
        $this->state = $this->get('State');
        $errors = $this->get('Errors');

        if (count($errors) > 0) {
            throw new Exception(implode('<br />', $errors), 500);

            return false;
        }

        SiakSubmenu::submenuAkademik('Praktikums');

        if ('modal' != $this->getLayout()) {
            $this->addToolbar();
            $this->sidebar = JHtmlSidebar::render();
        }
        parent::display($tpl);
    }

    protected function addToolbar()
    {
        $canDo = $this->canDo;

        JToolbarHelper::title(JText::_('COM_SIAK_PRAKTUKUMS_PAGE_TITLE'), 'list');

        if ($canDo->get('core.edit')) {
            JToolbarHelper::editList('praktikum.edit');
        }

        if ($canDo->get('core.edit.state')) {
            JToolbarHelper::publish('praktikums.publish');
            JToolbarHelper::unpublish('praktikums.unpublish');
            JToolbarHelper::trash('praktikums.trash');
        }

        if ($canDo->get('core.delete')) {
            JToolbarHelper::deleteList('JGLOBAL_CONFIRM_DELETE', 'praktikums.delete', 'JTOOLBAR_DELETE');
        }
        JToolbarHelper::custom('praktikums.excel', 'download', 'download', 'COM_SIAK_DOWNLOAD', false);
        JToolbarHelper::divider();
        if ($canDo->get('core.admin') || $canDo->get('core.options')) {
            JToolbarHelper::preferences('com_siak');
        }
    }
}
