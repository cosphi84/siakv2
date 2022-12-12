<?php

defined('_JEXEC') or die('Mati di view');

/**
 * @internal
 * @coversNothing
 */
class SiakViewTest extends JViewLegacy
{
    public function display($tpl = null)
    {
        echo 'hallo';
        parent::display($tpl);
    }
}
