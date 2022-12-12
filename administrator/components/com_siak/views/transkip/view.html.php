<?php

defined('_JEXEC') or exit;

class SiakViewTranskip extends JViewLegacy
{
    public function display($tpl = null)
    {
        $item = $this->get('Item');
        $error = $this->get('Errors');
        if (count($error) > 0) {
            throw new Exception(implode('<br />', $error), 500);

            return false;
        }

        var_dump($item['nilai']);
    }
}
