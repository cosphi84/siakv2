<?php

defined('_JEXEC') or exit;

class SiakViewJadwals extends JViewLegacy
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

        if (count($errors = $this->get('Errors'))) {
            throw new Exception(implode("\n", $errors), 500);

            return false;
        }

        parent::display($tpl);
    }
}
