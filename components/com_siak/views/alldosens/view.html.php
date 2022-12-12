<?php

defined('_JEXEC') or die;

class SiakViewAlldosens extends JViewLegacy
{
    protected $items;
    protected $pagination;

    public function display($tpl = null)
    {
        $this->items = $this->get('Items');
        $this->pagination = $this->get('Pagination');
        $app = JFactory::getApplication();
        $doc = JFactory::getDocument();
        $errors = $this->get('Errors');

        if (count($errors) > 0) {
            throw new Exception(implode("\n", $errors), 500);

            return false;
        }

        $title = JText::_('COM_SIAK_ALLDOSENS_PAGE_TITLE');
        $title = JText::sprintf(JPAGETITLE, $title, $app->get('sitename'));
        $doc->setTitle($title);

        parent::display($tpl);
    }
}
