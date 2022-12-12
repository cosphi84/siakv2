<?php
/**
 * @package     Joomla.Siak
 * @subpackage  Siak Nilai
 *
 * @copyright   (C) 2022 @ Risam, S.T
 * @license     Limited for FT-UNTAG Cirebon use Only
 */

defined('_JEXEC') or die();

use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\View\HtmlView;
use Joomla\CMS\Toolbar\ToolbarHelper;
use Joomla\CMS\Helper\ContentHelper;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Uri\Uri;

jimport('phpexcel.library.PHPExcel');

class SiaknilaiViewNilais extends HtmlView
{
    public $filterForm;
    public $activeFilters;
    protected $items;
    protected $pagination;
    protected $state;
    protected $canDo;
    protected $mahasiswa;
    protected $TA;
    protected $totalSKS = 0;
    protected $sumBxS = 0;
    protected $ipk = 0;


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
        $app = Factory::getApplication();
        $params = ComponentHelper::getParams('com_siak');
        $namaFilePDF = 'TranskipNilai-'.$this->mahasiswa->username.'.pdf';
        if (!class_exists('PHPExcel')) {
            $app->enqueueMessage('Error : Library Excel tidak ditemukan!', 'error');
            $app->redirect(JRoute::_('index.php?option=com_siaknilai&view=nilais'));

            return false;
        }

        if (empty($this->state->get('filter.search'))) {
            $app->enqueueMessage('Error: Tidak ada mahasiswa yang dipilih!', 'error');
            $app->redirect('index.php?option=com_siaknilai');
            return false;
        }

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

        $excel = new PHPExcel();

        $excel->getProperties()->setCreator('SIAK')
            ->setLastModifiedBy('SIAK')
            ->setTitle('Daftar Nilai Mahasiswa')
            ->setSubject('Daftar Nilai Mahasiswa')
            ->setDescription('Nilai Mahasiswa')
            ->setKeywords('nilai mahasiswa siak')
            ->setCategory('SIAK : Nilai')
        ;

        $excel->setActiveSheetIndex(0)
            ->setCellValue('A2', 'Mahasiswa :')
            ->setCellValue('B2', $this->mahasiswa->name)
            ->setCellValue('A3', 'Total SKS :')
            ->setCellValue('B3', $this->totalSKS)
            ->setCellValue('A4', 'IP / IPK :')
            ->setCellValue('B4', $this->ipk)
        ;

        $excel->setActiveSheetIndex(0)
            ->setCellValue('A6', 'No')
            ->setCellValue('B6', 'Semester')
            ->setCellValue('C6', 'Kode MK')
            ->setCellValue('D6', 'Matakuliah')
            ->setCellValue('E6', 'SKS')
            ->setCellValue('F6', 'Angka')
            ->setCellValue('G6', 'Nilai')
        ;

        $col = 7;
        $a = 1;
        foreach ($this->items as $i => $j) {
            $excel->setActiveSheetIndex(0)
                ->setCellValue('A'.$col, $a)
                ->setCellValue('B'.$col, $j->sm)
                ->setCellValue('C'.$col, $j->kodemk)
                ->setCellValue('D'.$col, $j->mk)
                ->setCellValue('E'.$col, $j->sks)
                ->setCellValue('F'.$col, $j->nilai_mutu)
                ->setCellValue('G'.$col, $j->nilai_angka)
            ;
            ++$col;
            $a++;
        }

        $excel->getActiveSheet()->setTitle('Daftar Nilai');
        $excel->setActiveSheetIndex(0);

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Daftar_NILAI.xlsx"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');

        // If you're serving to IE over SSL, then the following may be needed
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header('Pragma: public'); // HTTP/1.0

        $objWriter = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
        $objWriter->save('php://output');

        return true;

        
    }
}
