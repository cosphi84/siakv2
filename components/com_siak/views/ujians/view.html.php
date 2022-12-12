<?php

defined('_JEXEC') or exit;

class SiakViewUjians extends JViewLegacy
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

        $app = \JFactory::getApplication();
        $doc = \JFactory::getDocument();
        $title = JText::_('COM_SIAK_UJIANS_PAGE_TITLE');
        $title = JText::sprintf('JPAGETITLE', $title, $app->get('sitename'));
        $doc->setTitle($title);
        parent::display($tpl);
    }
}
