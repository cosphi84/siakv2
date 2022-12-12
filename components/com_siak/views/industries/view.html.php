<?php

defined('_JEXEC') or exit;

class SiakViewIndustries extends JViewLegacy
{
    public $activeFlters;
    public $filterForm;
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
            throw new Exception(implode('<br /', $errors), 500);

            return false;
        }

        parent::display($tpl);
    }
}
