<?php

defined('_JEXEC') or exit;

class SiakViewSks extends JViewLegacy
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

        SiakHelper::submenuFakultas('Sks');

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

        JToolbarHelper::title(JText::_('COM_SIAK_SKS_PAGE_TITLE'), 'shield');

        if ($canDo->get('core.create')) {
            JToolbarHelper::addNew('sk.add');
        }

        if ($canDo->get('core.edit')) {
            JToolbarHelper::editList('sk.edit');
        }

        if ($canDo->get('core.edit.state')) {
            JToolbarHelper::publish('sks.publish');
            JToolbarHelper::unpublish('sks.unpublish');
            JToolbarHelper::trash('sks.trash');
        }

        if ($canDo->get('core.delete')) {
            JToolbarHelper::deleteList('JGLOBAL_CONFIRM_DELETE', 'sks.delete', 'JTOOLBAR_DELETE');
        }
        JToolbarHelper::divider();
        if ($canDo->get('core.admin') || $canDo->get('core.options')) {
            JToolbarHelper::preferences('com_siak');
        }
    }
}
