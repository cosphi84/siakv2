<?php

defined('_JEXEC') or exit;

class SiakViewKrs extends JViewLegacy
{
    public function display($tpl = null)
    {
        $this->items = $this->get('Items');

        if (count($errors = $this->get('Errors'))) {
            echo new JResponseJson($errors, JText::_('JERROR_AN_ERROR_HAS_OCCURRED'), true);

            return false;
        }

        echo new JResponseJson($this->items);
    }
}
