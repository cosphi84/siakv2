<?php



defined('_JEXEC') or die;

use Joomla\CMS\MVC\View\HtmlView;

class SiakViewDosenmks extends HtmlView
{
    public $filterForm;
    public $activeFilters;
    protected $items;
    protected $pagination;
    protected $canDo;
    protected $state;

    public function display($tpl = null)
    {
        $this->items = $this->get('Items');
        $this->pagination = $this->get('Pagination');
        $this->filterForm = $this->get('FilterForm');
        $this->activeFilters = $this->get('ActiveFilters');
        $this->canDo = JHelperContent::getActions('com_siak');
        $this->state = $this->get('State');
        $errors = $this->get('Errors');

        if (count($errors) > 0) {
            throw new Exception(implode("\n", $errors));

            return false;
        }

        SiakHelper::submenuFakultas('Dosenmks');
        if ('modal' != $this->getLayout()) {
            $this->addToolbar();
            $this->sidebar = JHtmlSidebar::render();
        }

        parent::display($tpl);
    }

    protected function addToolbar()
    {
        //$bar = JToolbar::getInstance('toolbar');

        $title = JText::_('COM_SIAK_DOSENMKS_PAGE_TITLE');

        JToolbarHelper::title($title, 'address');

        if ($this->canDo->get('core.create')) {
            JToolbarHelper::addNew('dosenmk.add');
        }
        if ($this->canDo->get('core.edit')) {
            JToolbarHelper::editList('dosenmk.edit');
        }

        if ($this->canDo->get('core.edit.state')) {
            JToolbarHelper::publish('dosenmks.publish');
            JToolbarHelper::unpublish('dosenmks.unpublish');
            JToolbarHelper::trash('dosenmks.trash');
        }
        if ($this->canDo->get('core.delete')) {
            JToolbarHelper::deleteList('JGLOBAL_CONFIRM_DELETE', 'dosenmks.delete', 'JTOOLBAR_DELETE');
        }
        JToolbarHelper::divider();
        if ($this->canDo->get('core.admin') || $this->canDo->get('core.options')) {
            JToolbarHelper::preferences('com_siak');
        }
    }
}
