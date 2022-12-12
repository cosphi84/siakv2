<?php

defined('_JEXEC') or exit('Direct Access!');

class SiakViewDashboard extends JViewLegacy
{
    protected $modules;

    public function display($tpl = null)
    {
        JToolbarHelper::title(JText::_('COM_SIAK_DASHBOARD_PAGE_TITLE'), 'home-2 cpanel');

        parent::display($tpl);
    }
}
