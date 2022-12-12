<?php

defined('_JEXEC') or exit;

class SiakViewMatkuls extends JViewLegacy
{
    public $filterForm;
    public $activeFilters;
    protected $items;
    protected $pagination;
    protected $state;

    public function display($tpl = null)
    {
        $this->items = $this->get('Items');
        $this->pagination = $this->get('Pagination');
        $this->state = $this->get('State');
        $this->filterForm = $this->get('FilterForm');
        $this->activeFilters = $this->get('ActiveFilters');

        if (count($errors = $this->get('Errors')) > 0) {
            throw new Exception(implode("\n", $errors), 500);

            return false;
        }

        parent::display($tpl);
    }
}
