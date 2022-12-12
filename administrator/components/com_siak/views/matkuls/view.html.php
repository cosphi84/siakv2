<?php

defined('_JEXEC') or exit;

class SiakViewMatkuls extends JViewLegacy
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

        SiakHelper::submenuFakultas('Matkuls');

        if (count($errors = $this->get('Errors'))) {
            throw new Exception(implode('<br \>', $errors), 500);

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

        JToolbarHelper::title(JText::_('COM_SIAK_MATKULS_PAGE_TITLE'), 'tags');

        if ($canDo->get('core.create')) {
            JToolbarHelper::addNew('matkul.add');
        }
        if ($canDo->get('core.edit')) {
            JToolbarHelper::editList('matkul.edit');
        }

        if ($canDo->get('core.edit.state')) {
            JToolbarHelper::publish('matkuls.publish');
            JToolbarHelper::unpublish('matkuls.unpublish');
            JToolbarHelper::trash('matkuls.trash');
        }
        if ($canDo->get('core.delete')) {
            JToolbarHelper::deleteList('JGLOBAL_CONFIRM_DELETE', 'matkuls.delete', 'JTOOLBAR_DELETE');
        }

        JToolbarHelper::custom('matkuls.excel', 'download', 'download', 'Unduh Excel', false);

        JToolbarHelper::divider();
        if ($canDo->get('core.admin') || $canDo->get('core.options')) {
            JToolbarHelper::preferences('com_siak');
        }
    }
}
