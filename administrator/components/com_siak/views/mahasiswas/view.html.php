<?php

defined('_JEXEC') or exit;

class SiakViewMahasiswas extends JViewLegacy
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

        SiakHelper::submenuFakultas('Mahasiswas');

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

        JToolbarHelper::title(JText::_('COM_SIAK_MAHASISWAS_PAGE_TITLE'), 'users');

        if ($canDo->get('core.create')) {
            JToolbarHelper::custom('mahasiswa.edit', 'apply', 'apply', 'JMODIFY', true);
        }

        if ($canDo->get('core.edit.state')) {
            JToolbarHelper::custom('mahasiswas.confirm', 'publish', 'publish', 'COM_SIAK_CONFIRM', true);
            //JToolbarHelper::custom('mahasiswas.lulus', 'flag', 'flag-3', 'Lulus', true);
        }
        if ($canDo->get('core.delete')) {
            JToolbarHelper::deleteList('JGLOBAL_CONFIRM_DELETE', 'mahasiswas.delete', 'JTOOLBAR_DELETE');
        }
        JToolbarHelper::custom('mahasiswas.excel', 'download', 'download', 'Unduh Excel', false);
        JToolbarHelper::divider();
        if ($canDo->get('core.admin') || $canDo->get('core.options')) {
            JToolbarHelper::preferences('com_siak');
        }
    }
}
