<?php

defined('_JEXEC') or exit;

class SiakViewMymk extends JViewLegacy
{
    public $activeFilters;
    public $filterForm;
    protected $items;
    protected $pagination;
    protected $user;

    public function display($tpl = null)
    {
        $this->items = $this->get('Items');
        $this->activeFilters = $this->get('ActiveFilters');
        $this->filterForm = $this->get('FilterForm');
        $this->pagination = $this->get('Pagination');
        $errors = $this->get('Errors');

        if (count($errors) > 0) {
            throw new Exception(implode("\n", $errors));

            return false;
        }

        $app = JFactory::getApplication();
        $doc = JFactory::getDocument();
        $params = JComponentHelper::getParams('com_siak');
        $grpDosen = $params->get('grpDosen');
        $this->user = JFactory::getUser();
        $myGroups = $this->user->get('groups');

        if (!in_array($grpDosen, $myGroups)) {
            $app->enqueueMessage(JText::_('COM_SIAK_WARNING_DOSEN_ONLY'), 'error');
            $app->redirect(JRoute::_('index.php?option=com_siak&view=dashboard', false));

            return false;
        }
        $title = JText::_('COM_SIAK_MYMK_PAGE_TITLE');
        $title = JText::sprintf('JPAGETITLE', $title, $app->get('sitename'));
        $doc->setTitle($title);

        parent::display($tpl);
    }
}
