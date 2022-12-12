<?php

defined('_JEXEC') or exit;

class SiakViewBayarans extends JViewLegacy
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
        $this->activeFilters = $this->get('ActiveFilters');
        $this->filterForm = $this->get('FilterForm');
        $this->state = $this->get('State');
        $this->canDo = JHelperContent::getActions('com_siak');
        $errors = $this->get('Errors');

        if (count($errors) > 0) {
            throw new Exception(implode('<br />', $errors), 500);

            return false;
        }

        SiakHelper::submenuKeuangan('Bayarans');

        if ('modal' !== $this->getLayout()) {
            $this->addTolbar();
            $this->sidebar = JHtmlSidebar::render();
        }
        parent::display($tpl);
    }

    protected function addTolbar()
    {
        $canDo = $this->canDo;
        JToolbarHelper::title(JText::_('COM_SIAK_BAYARANS_PAGE_TITLE'), 'credit');

        if ($canDo->get('core.edit') || $canDo->get('core.edit.state')) {
            JToolbarHelper::editList('bayaran.edit');
            JToolbarHelper::custom('bayarans.confirm', 'bookmark', 'bookmark', 'COM_SIAK_CONFIRM', true);
            JToolbarHelper::custom('bayarans.belumlunas', 'unpublish', 'unpublish', 'COM_SIAK_BELUM_LUNAS', true);
            JToolbarHelper::custom('bayarans.lunas', 'publish', 'publish', 'COM_SIAK_LUNAS', true);
        }

        JToolbarHelper::custom('bayarans.xlsx', 'download', 'download', 'COM_SIAK_DOWNLOAD', false);
        if ($canDo->get('core.manage')) {
            JToolbarHelper::deleteList('JGLOBAL_CONFIRM_DELETE', 'bayarans.delete', 'JTOOLBAR_DELETE');
        }
        JToolbarHelper::divider();
        if ($canDo->get('core.admin') || $canDo->get('core.options')) {
            JToolbarHelper::preferences('com_siak');
        }
    }
}
