<?php
/**
 * @package     Joomla.Siak
 * @subpackage  Siak TA
 *
 * @copyright   (C) 2022 @ Risam, S.T
 * @license     Limited for FT-UNTAG Cirebon use Only
 */

use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\View\HtmlView;
use Joomla\CMS\Toolbar\ToolbarHelper;
use Joomla\CMS\Helper\ContentHelper;

defined('_JEXEC') or die();

class SiaktaViewTas extends HtmlView
{
    public $filterForm;
    public $activeFilters;
    protected $items;
    protected $pagination;
    protected $state;
    protected $canDo;

    public function display($tpl = null)
    {
        $this->items = $this->get('Items');
        $this->pagination = $this->get('Pagination');
        $this->filterForm = $this->get('FilterForm');
        $this->activeFilters = $this->get('ActiveFilters');
        $this->state = $this->get('State');
        $this->canDo = ContentHelper::getActions('com_siakta');

        SiaktaHelper::subMenuSiak('tas');

        if (count($errors = $this->get('Errors'))) {
            throw new Exception(implode('\n', $errors), 500);
        }
        $this->drawToolbar();

        parent::display($tpl);
    }

    protected function drawToolbar()
    {
        $cando = $this->canDo;

        ToolbarHelper::title(Text::_('COM_SIAKTA_TAS_PAGETITLE'));

        if ($cando->get('core.create')) {
            ToolbarHelper::addNew('ta.add');
        }

        if ($cando->get('core.edit')) {
            ToolbarHelper::editList('ta.edit');
        }

        if ($cando->get('core.edit.state')) {
            ToolbarHelper::publishList('tas.publish');
            ToolbarHelper::unpublishList('tas.unpublish');
        }

        if ($cando->get('core.delete')) {
            ToolbarHelper::deleteList('JGLOBAL_CONFIRM_DELETE', 'tas.delete');
        }

        ToolbarHelper::custom('tas.download', 'download', 'download', 'Download', false);

        JToolbarHelper::divider();
        if ($cando->get('core.admin') || $cando->get('core.options')) {
            JToolbarHelper::preferences('com_siakta');
        }
    }
}
