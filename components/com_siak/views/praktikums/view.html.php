<?php

defined('_JEXEC') or die;

class SiakViewPraktikums extends JViewLegacy
{
    public $form;
    // public $activeFilters;
    protected $items;
    protected $pagination;

    public function display($tpl = null)
    {
        $this->items = $this->get('Items');
        //$this->activeFilters = $this->get('ActiveFilters');
        $this->form = $this->get('Form');
        $this->pagination = $this->get('Pagination');

        $errors = $this->get('Errors');
        if (count($errors) > 0) {
            throw new Exception(implode("\n", $errors), 500);

            return false;
        }

        $app = \JFactory::getApplication();
        $doc = \JFactory::getDocument();
        $title = JText::_('COM_SIAK_PRAKTIKUMS_PAGE_TITLE');
        $title = JText::sprintf('JPAGETITLE', $title, $app->get('sitename'));
        $doc->setTitle($title);
        parent::display($tpl);
    }
}
