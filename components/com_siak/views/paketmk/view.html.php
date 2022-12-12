<?php

defined('_JEXEC') or die;

class SiakViewPaketmk extends JViewLegacy
{
    public $filterForm;
    public $activeFilters;
    protected $items;
    protected $pagination;

    public function display($tpl = null)
    {
        $this->items = $this->get('Items');
        $this->pagination = $this->get('Pagination');
        $this->filterForm = $this->get('FilterForm');
        $this->activeFilters = $this->get('ActiveFilters');

        if (count($errors = $this->get('Errors')) > 0) {
            throw new Exception(implode('\n', $errors), 500);

            return false;
        }

        $app = JFactory::getApplication();
        $doc = JFactory::getDocument();
        $title = JText::_('COM_SIAK_PAKETMK_TITLE_PAGE');
        $title = JText::sprintf('JPAGETITLE', $title, $app->get('sitename'));
        $doc->setTitle($title);

        parent::display($tpl);
    }
}
