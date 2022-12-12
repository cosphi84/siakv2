<?php

defined('_JEXEC') or die;

class SiakViewBayaran extends JViewLegacy
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
        $errors = $this->get('Errors');
        $this->filterForm = $this->get('FilterForm');
        $this->activeFilters = $this->get('ActiveFilters');

        $this->state = $this->get('State');

        if (count($errors) > 0) {
            throw new Exception(implode('\n', $errors), 500);

            return false;
        }

        $app = Jfactory::getApplication();
        $doc = JFactory::getDocument();
        $title = JText::_('COM_SIAK_BAYARAN_TITLE_PAGE');
        $title = JText::sprintf('JPAGETITLE', $title, $app->get('sitename'));
        $doc->setTitle($title);
        $doc->addStyleSheet(JURI::root().'media/com_siak/css/siak.css');

        parent::display($tpl);
    }
}
