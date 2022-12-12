<?php

defined('_JEXEC') or exit;

class SiakViewNilais extends JViewLegacy
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

        SiakSubmenu::submenuAkademik('Nilais');

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

        JToolbarHelper::title(JText::_('COM_SIAK_NILAIS_PAGE_TITLE'), 'book');

        if ($canDo->get('core.edit')) {
            JToolbarHelper::editList('nilai.edit');
        }

        JToolbarHelper::custom('nilais.excel', 'download', 'download', 'Unduh Excel', false);
        //JToolbarHelper::custom('nilais.transkrip', 'download', 'download', 'Lihat Transkrip', true);
        JToolbarHelper::custom('nilais.pdf', 'download', 'download', 'Unduh Transkrip', true);
        JToolbarHelper::divider();
        if ($canDo->get('core.admin') || $canDo->get('core.options')) {
            JToolbarHelper::preferences('com_siak');
        }
    }
}
