<?php

defined('_JEXEC') or exit;

class SiakViewWalis extends JViewLegacy
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

        SiakHelper::submenuFakultas('Walis');

        if (count($errors = $this->get('Errors'))) {
            throw new Exception(implode("\n", $errors), 500);

            return false;
        }
        if ('modal' !== $this->getLayout()) {
            $this->addTolbar();
            $this->sidebar = JHtmlSidebar::render();
        }
        parent::display($tpl);
    }

    protected function addTolbar()
    {
        $canDo = $this->canDo;
        $user = JFactory::getUser();

        $bar = JToolbar::getInstance('toolbar');

        JToolbarHelper::title(JText::_('COM_SIAK_WALIS_PAGE_TITLE'), 'user');

        if ($canDo->get('core.create')) {
            JToolbarHelper::addNew('wali.add');
        }
        if ($canDo->get('core.edit')) {
            JToolbarHelper::editList('wali.edit');
        }

        if ($this->canDo->get('core.edit.state')) {
            JToolbarHelper::publish('walis.publish');
            JToolbarHelper::unpublish('walis.unpublish');
            JToolbarHelper::trash('walis.trash');
        }

        if ($canDo->get('core.delete')) {
            JToolbarHelper::deleteList('JGLOBAL_CONFIRM_DELETE', 'walis.delete', 'JTOOLBAR_DELETE');
        }
        JToolbarHelper::divider();
        if ($canDo->get('core.admin') || $canDo->get('core.options')) {
            JToolbarHelper::preferences('com_siak');
        }
    }
}
