<?php
/**
 * @package     Joomla.Siak
 * @subpackage  Siak Nilai
 *
 * @copyright   (C) 2022 @ Risam, S.T
 * @license     Limited for FT-UNTAG Cirebon use Only
 */

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\View\HtmlView;
use Joomla\CMS\Toolbar\ToolbarHelper;
use Joomla\CMS\Helper\ContentHelper;
use Joomla\CMS\Uri\Uri;

defined('_JEXEC') or die();

class SiaknilaiViewNilais extends HtmlView
{
    public $filterForm;
    public $activeFilters;
    protected $items;
    protected $pagination;
    protected $state;
    protected $canDo;
    protected $mahasiswa;
    protected $totalSKS = 0;
    protected $sumBxS = 0;
    protected $ipk = 0;
    protected $TA;

    public function display($tpl = null)
    {
        $this->items = $this->get('Items');
        $this->pagination = $this->get('Pagination');
        $this->filterForm = $this->get('FilterForm');
        $this->activeFilters = $this->get('ActiveFilters');
        $this->state = $this->get('State');
        $this->mahasiswa = $this->get('Mahasiswa');
        $this->TA = $this->get('TugasAkhir');

        $this->canDo = ContentHelper::getActions('com_siaknilai');

        SiaknilaiHelper::subMenuSiak('nilais');

        if (count($errors = $this->get('Errors'))) {
            throw new Exception(implode('\n', $errors), 500);
        }

        if (!empty($this->items)) {
            // hitung total SKS
            foreach ($this->items as $i=>$v) {
                $this->totalSKS += $v->sks;
                $this->sumBxS += ($v->sks * $v->nilai_mutu);
            }

            $this->ipk = $this->sumBxS / $this->totalSKS;
        }

        $this->drawToolbar();
        $doc = Factory::getDocument();
        $doc->addStyleSheet(Uri::root(). 'media/com_siaknilai/css/siaknilai.css');
        parent::display($tpl);
    }

    protected function drawToolbar()
    {
        $cando = $this->canDo;
        $mhs = $this->state->get('filter.search', null);

        empty($mhs) ? $mhs = 'Mahasiswa' : $mhs = 'NPK : '. $mhs;

        ToolbarHelper::title(Text::plural('COM_SIAKNILAI_NILAIS_PAGETITLE', $mhs));

        /*
        if ($cando->get('core.create')) {
            ToolbarHelper::addNew('nilai.add');
        }

        if ($cando->get('core.edit')) {
            ToolbarHelper::editList('nilai.edit');
        }

        if ($cando->get('core.edit.state')) {
            ToolbarHelper::custom('nilais.show', 'publish', 'publish', 'Tampilkan');
            ToolbarHelper::custom('nilais.hide', 'unpublish', 'unpublish', 'Sembunyikan');
            //ToolbarHelper::publishList('nilais.publish');
            //ToolbarHelper::unpublishList('nilais.unpublish');
        }

        if ($cando->get('core.delete')) {
            ToolbarHelper::deleteList('JGLOBAL_CONFIRM_DELETE', 'nilais.delete');
        }
        */
        ToolbarHelper::custom('nilais.excel', 'download', 'download', 'Download Excel', false);
        ToolbarHelper::custom('nilais.pdf', 'download', 'download', 'Download Transkip', false);

        JToolbarHelper::divider();
        if ($cando->get('core.admin') || $cando->get('core.options')) {
            JToolbarHelper::preferences('com_siaknilai');
        }
    }
}
