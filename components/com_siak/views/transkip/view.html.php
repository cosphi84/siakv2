<?php

defined('_JEXEC') or exit;

class SiakViewTranskip extends JViewLegacy
{
    public $filterForm;
    public $activeFilters;
    protected $items;
    protected $pagination;
    protected $state;
    protected $ttlSKS;

    public function display($tpl = null)
    {
        $this->items = $this->get('Items');
        $this->pagination = $this->get('Pagination');
        $this->activeFilters = $this->get('ActiveFilters');
        $this->filterForm = $this->get('FilterForm');
        $this->state = $this->get('State');
        $errors = $this->get('Errors');
        $app = JFactory::getApplication();
        $user = JFactory::getUser();

        if (count($errors) > 0) {
            throw new Exception(implode('<br />', $errors), 500);

            return false;
        }

        if (!SiakHelper::auth($user)) {
            $app->enqueueMessage(JText::_('COM_SIAK_WRONG_MENU'), 'error');
            $app->redirect(JRoute::_('index.php?option=com_siak&view=dashboard', false));

            return false;
        }

        $doc = JFactory::getDocument();
        $title = JText::_('COM_SIAK_NILAI_PAGE_TITLE');
        $title = JText::sprintf('JPAGETITLE', $title, $app->get('sitename'));
        $doc->setTitle($title);

        parent::display($tpl);
    }
}
