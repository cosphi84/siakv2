<?php

defined('_JEXEC') or die;

class SiakViewDosens extends JViewLegacy
{
    public $form;
    protected $items;
    protected $pagination;

    public function display($tpl = null)
    {
        $this->items = $this->get('Items');
        $this->form = $this->get('Form');
        $this->pagination = $this->get('Pagination');

        if (count($errors = $this->get('Errors'))) {
            throw new Exception(implode("\n", $errors), 500);

            return false;
        }

        parent::display($tpl);
    }
}
