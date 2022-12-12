<?php

defined('_JEXEC') or exit;

class SiakViewPaketmks extends JViewLegacy
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

        SiakHelper::submenuFakultas('Paketmks');

        if (count($errors = $this->get('Errors'))) {
            throw new Exception(implode("\n", $errors), 500);

            return false;
        }
        if ('modal' !== $this->getLayout()) {
            $this->addTolbar();
            $this->sidebar = JHtmlSidebar::render();
        }

        if ('detail' == $this->getLayout()) {
            JFactory::getDocument()->setTitle(JText::plural('COM_SIAK_PAKET_MK_DETAIL_PAGE_TITLE', $this->items[0]->semester));
        }

        parent::display($tpl);
    }

    protected function addTolbar()
    {
        $canDo = $this->canDo;
        $user = JFactory::getUser();

        $bar = JToolbar::getInstance('toolbar');

        JToolbarHelper::title(JText::_('COM_SIAK_PAKET_MK_PAGE_TITLE'), 'tags');

        if ($canDo->get('core.create')) {
            JToolbarHelper::addNew('paketmk.add');
        }
        if ($canDo->get('core.edit')) {
            JToolbarHelper::editList('paketmk.edit');
        }

        if ($canDo->get('core.delete')) {
            JToolbarHelper::deleteList('JGLOBAL_CONFIRM_DELETE', 'paketmks.delete', 'JTOOLBAR_DELETE');
        }
        JToolbarHelper::custom('paketmks.excel', 'download', 'download', 'Unduh Excel', false);
        JToolbarHelper::divider();
        if ($canDo->get('core.admin') || $canDo->get('core.options')) {
            JToolbarHelper::preferences('com_siak');
        }
    }
}
