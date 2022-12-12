<?php

defined('_JEXEC') or exit;

class SiakViewSps extends JViewLegacy
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
        $this->filterForm = $this->get('FilterForm');
        $this->activeFilters = $this->get('ActiveFilters');
        $this->state = $this->get('State');

        $mode = $this->get('Mode');

        'mahasiswa' == $mode ? $tpl = 'mahasiswa' : $tpl;

        if (count($errors = $this->get('Errors'))) {
            throw new Exception(implode("\n", $errors), 500);

            return false;
        }

        parent::display($tpl);
    }
}
