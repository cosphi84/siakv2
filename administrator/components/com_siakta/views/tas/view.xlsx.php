<?php
/**
 * @package     Joomla.Siak
 * @subpackage  com_remidials
 *
 * @copyright   (C) 2022 @ Risam, S.T
 * @license     Limited for FT-UNTAG Cirebon use Only
 */
ini_set('display_error', 0);
use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\MVC\View\HtmlView;

defined('_JEXEC') or die();

jimport('phpexcel.library.PHPExcel');

class SiaktaViewTas extends HtmlView
{
    public function display($tpl = null)
    {
        $data = $this->get('Items');
        $errors = $this->get('Errors');

        if (count($errors) > 0) {
            throw new Exception(implode('<br />', $errors), 500);

            return false;
        }

        if (!class_exists('PHPExcel')) {
            throw new Exception('PHPExcel Not Found!', 500);
            return false;
        }

        $excel = new PHPExcel();
        $excel->getProperties()
            ->setCreator('SIAK - Sistem Informasi AKademik')
            ->setLastModifiedBy('SIAK Server')
            ->setTitle('Daftar Tugas Akhir Mahasiswa')
            ->setSubject('Tugas Akhir')
            ->setDescription('Daftar Tugas akhir mahasiswa')
            ->setKeywords('TA, SIAK')
            ->setCategory('SIAK Tugas AKhir');

        $excel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'No')
            ->setCellValue('B1', 'Judul')
            ->setCellValue('C1', 'NPM')
            ->setCellValue('D1', 'Mahasiswa')
            ->setCellValue('E1', 'Prodi')
            ->setCellValue('F1', 'Konsentrasi')
            ->setCellValue('G1', 'Tahun')
            ->setCellValue('H1', 'Dosen Pembimbing 1')
            ->setCellValue('I1', 'Dosen Pembimbing 2')
            ->setCellValue('J1', 'Jadwal Sidang Proposal')
            ->setCellValue('K1', 'Jadwal Sidang Akhir')
            ->setCellValue('L1', 'Ruang Sidang')
            ->setCellValue('M1', 'Penguji 1')
            ->setCellValue('N1', 'Penguji 2')
            ->setCellValue('O1', 'Penguji 3')
            ->setCellValue('P1', 'Penguji 4')
            ->setCellValue('Q1', 'Tanggal Lulus')
            ->setCellValue('R1', 'Yudisium');

    
        $a = 2;
        $b = 1;
        foreach ($data as $i=>$item) {
            
            $excel->setActiveSheetIndex(0)
                ->setCellValue('A'.$a, $b)
                ->setCellValue('B'.$a, $item->title)
                ->setCellValue('C'.$a, Factory::getUser($item->mahasiswa_id)->username)
                ->setCellValue('D'.$a, Factory::getUser($item->mahasiswa_id)->name)
                ->setCellValue('E'.$a, $item->prodi)
                ->setCellValue('F'.$a, $item->jurusan)
                ->setCellValue('G'.$a, $item->tahun)
                ->setCellValue('H'.$a, Factory::getUser($item->dosbing1)->name)
                ->setCellValue('I'.$a, Factory::getUser($item->dosbing2)->name)
                ->setCellValue('J'.$a, HTMLHelper::date($item->sidang_proposal, 'd/m/Y'))
                ->setCellValue('K'.$a, HTMLHelper::date($item->sidang_akhir, 'd/m/Y'))
                ->setCellValue('L'.$a, $item->ruang_sidang)
                ->setCellValue('M'.$a, Factory::getUser($item->penguji1)->name)
                ->setCellValue('N'.$a, Factory::getUser($item->penguji2)->name)
                ->setCellValue('O'.$a, Factory::getUser($item->penguji3)->name)
                ->setCellValue('P'.$a, Factory::getUser($item->penguji4)->name)
                ->setCellValue('Q'.$a, HTMLHelper::date($item->sidang_proposal, 'd/m/Y'))
                ->setCellValue('R'.$a, $item->yudisium);
            $a++;
            $b++;
        }

        $excel->getActiveSheet()->setTitle('TugasAkhir');
        $excel->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Daftar_TugasAKhir.xlsx"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');
 
        // If you're serving to IE over SSL, then the following may be needed
         header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
         header('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
         header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
         header('Pragma: public'); // HTTP/1.0

         $writer = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
        $writer->save('php://output');

        return true;
    }
}
