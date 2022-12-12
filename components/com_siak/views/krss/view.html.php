<?php

defined('_JEXEC') or exit;

class SiakViewKrss extends JViewLegacy
{
    public $activeFilters;
    public $filterForm;
    protected $items;
    protected $pagination;
    protected $state;

    public function display($tpl = null)
    {
        $this->items = $this->get('Items');
        $this->pagination = $this->get('Pagination');
        $this->filterForm = $this->get('FilterForm');
        $this->state = $this->get('State');
        $this->activeFilters = $this->get('ActiveFilters');
        $errors = $this->get('Errors');

        if (count($errors) > 0) {
            throw new Exception(implode("\n", $errors), 500);

            return false;
        }

        $params = JComponentHelper::getParams('com_siak');
        $grpMhs = $params->get('grpMahasiswa');
        $user = \JFactory::getUser();
        $grupku = $user->get('groups');
        $app = \JFactory::getApplication();

        if (in_array($grpMhs, $grupku) && 'wali' == $this->getLayout()) {
            $app->enqueueMessage(JText::_('COM_SIAK_WRONG_MENU'), 'error');
            $app->redirect(JRoute::_('index.php?option=com_siak&view=krss', false));

            return false;
        }

        parent::display($tpl);
    }
}
