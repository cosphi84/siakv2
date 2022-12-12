<?php

defined('_JEXEC') or exit;

class SiakViewRombels extends JViewLegacy
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
        $this->state = $this->get('State');
        $this->filterForm = $this->get('FilterForm');
        $this->activeFilters = $this->get('ActiveFilters');
        $this->canDo = JHelperContent::getActions('com_siak');
        $errors = $this->get('Errors');

        if (count($errors) > 0) {
            throw new Exception(implode('<br />', $errors), 500);

            return false;
        }

        SiakSubmenu::submenuAkademik('Rombels');

        if ('json' != $this->getLayout()) {
            $this->addToolbar();
            $this->sidebar = JHtmlSidebar::render();
        }

        parent::display($tpl);
    }

    protected function addToolbar()
    {
        JToolbarHelper::title(JText::_('COM_SIAK_ROMBELS_PAGE_TITLE'));

        if ($this->canDo->get('core.edit.state')) {
            JToolbarHelper::publish('rombels.publish');
            JToolbarHelper::unpublish('rombels.unpublish');
            JToolbarHelper::trash('rombels.trash');
        }

        if ($this->canDo->get('core.delete')) {
            JToolbarHelper::deleteList('JGLOBAL_CONFIRM_DELETE', 'rombels.delete', 'JTOOLBAR_DELETE');
        }

        JToolbar::getInstance('toolbar')->appendButton(
            'Popup',
            'download',
            'Unduh Excel',
            'index.php?option=com_siak&view=rombels&format=xlsx',
            550,
            350
        );
        JToolbarHelper::divider();
        if ($this->canDo->get('core.admin') || $this->canDo->get('core.options')) {
            JToolbarHelper::preferences('com_siak');
        }
    }
}
