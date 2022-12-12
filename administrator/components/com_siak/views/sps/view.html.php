<?php

defined('_JEXEC') or exit;

class SiakViewSps extends JViewLegacy
{
    public $filterForm;
    public $activeFilters;

    protected $items;
    protected $pagination;
    protected $state;
    protected $canDo;

    public function display($tpl = null)
    {
        $this->items = $this->get('Items');
        $this->pagination = $this->get('Pagination');
        $this->state = $this->get('State');
        $this->activeFilters = $this->get('ActiveFilters');
        $this->filterForm = $this->get('FilterForm');
        $this->canDo = JHelperContent::getActions('com_siak');
        $errrors = $this->get('Errors');

        if (count($errrors) > 0) {
            throw new Exception(implode('<br />', $errrors), 500);

            return false;
        }

        SiakSubmenu::submenuAkademik('Sps');
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

        JToolbarHelper::title(JText::_('COM_SIAK_SPS_PAGE_TITLE'), 'folder-plus ');

        if ($canDo->get('core.create')) {
            JToolbarHelper::addNew('sp.add');
        }

        if ($canDo->get('core.edit')) {
            JToolbarHelper::editList('sp.edit');
        }

        if ($canDo->get('core.edit.state')) {
            JToolbarHelper::publish('sps.publish');
            JToolbarHelper::unpublish('sps.unpublish');
            JToolbarHelper::trash('sps.trash');
        }

        if ($canDo->get('core.delete')) {
            JToolbarHelper::deleteList('JGLOBAL_CONFIRM_DELETE', 'sps.delete', 'JTOOLBAR_DELETE');
        }
        JToolbarHelper::divider();
        if ($canDo->get('core.admin') || $canDo->get('core.options')) {
            JToolbarHelper::preferences('com_siak');
        }
    }
}
