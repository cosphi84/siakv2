<?php

defined('_JEXEC') or exit;

class SiakViewRemidial extends JViewLegacy
{
    protected $item;
    protected $form;

    public function display($tpl = null)
    {
        $this->item = $this->get('Item');
        $this->form = $this->get('Form');

        $doc = JFactory::getDocument();
        $doc->addScriptOptions('com_siak', SiakHelper::getStandarNilai());

        if (count($errors = $this->get('Errors')) > 0) {
            throw new Exception(implode('<br />', $errors), '500');

            return false;
        }

        parent::display($tpl);
    }
}
