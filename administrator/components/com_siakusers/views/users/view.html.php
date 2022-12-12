<?php


defined('_JEXEC') or die;
use Joomla\CMS\MVC\View\HtmlView;
use Joomla\CMS\Helper\ContentHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Toolbar\ToolbarHelper;

class SiakusersViewUsers extends HtmlView
{
    public $filterForm;
    public $activeFilters;
    protected $items;
    protected $state;
    protected $pagination;

    public function display($tpl = null)
    {
        $this->items = $this->get('Items');
        $this->state = $this->get('State');
        $this->activeFilters = $this->get('ActiveFilters');
        $this->filterForm = $this->get('FilterForm');
        $this->pagination = $this->get('Pagination');

        if (count($errors = $this->get('Errors'))) {
            throw new Exception(implode("\n", $errors), 500);
        }

        $modeText = "mahasiswa";

        $this->state->get('mode') == 0 ? $modeText : $modeText = "pegawai";
        SiakusersHelper::addSubmenu($modeText);
        $this->addToolbar();
        parent::display($tpl);
    }

    protected function addToolbar(): void
    {
        $canDo = ContentHelper::getActions('com_siakusers');
        $canDoUser = ContentHelper::getActions('com_users');
        $modeText = "Mahasiswa";

        $this->state->get('mode') == 0 ? $modeText : $modeText = "Pegawai";
        ToolbarHelper::title(Text::sprintf('COM_SIAKUSERS_USERS_PAGE_TITLE', $modeText), 'users');

        if ($canDoUser->get('core.create')) {
            ToolbarHelper::addNew('user.add');
        }

        if ($canDo->get('core.edit') || $canDo->get('core.edit.state')) {
            ToolbarHelper::editList('user.edit');
            if ($this->state->get('mode') == 0) {
                ToolbarHelper::custom('user.lulus', 'flag-3', 'flag-3', 'Lulus', true);
            } else {
                ToolbarHelper::custom('user.lulus', 'flag-3', 'flag-3', 'Resign', true);
            }
            ToolbarHelper::custom('user.block', 'expired', 'expired', 'Blokir', false);
        }
    }
}
