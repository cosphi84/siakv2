<?php

defined('_JEXEC') or exit('Direct Access!');

class SiakViewDosens extends JViewLegacy
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

        SiakHelper::submenuFakultas('Dosens');

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

        JToolbarHelper::title(JText::_('COM_SIAK_DOSENS_PAGE_TITLE'), 'users');
        JToolbarHelper::custom('user.edit', 'apply', 'apply', 'JMODIFY', true);
        JToolbarHelper::custom('user.fupdate', 'loop', 'loop', 'COM_SIAK_USERS_FIELD_FUPDATE_LABEL', false);

        if ($canDo->get('core.admin') || $canDo->get('core.options')) {
            JToolbarHelper::preferences('com_siak');
        }
    }
}
