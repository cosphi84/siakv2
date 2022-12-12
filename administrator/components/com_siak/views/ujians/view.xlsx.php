<?php

defined('_JEXEC') or die;
JLoader::discover('PhpSpreadsheet', JPATH_LIBRARIES.'/PhpSpreadsheet');

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\View\HtmlView;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class SiakViewUjians extends HtmlView
{
    public function display($tpl = null)
    {
        $data = $this->get('Items');
        $errors = $this->get('Errors');


        if (count($errors) > 0) {
            throw new Exception(implode('/n', $errors), 500);
            return false;
        }

        $excel = new Spreadsheet();
        //JLoader::register('Spreadsheet', JPATH_COMPONENT_ADMINISTRATOR.'/libraries/PhpSpreadsheet/Spreadsheet.php');
        if (!class_exists('Spreadsheet')) {
            throw new Exception('Class Spreadhset Not Found!.', 500);
            return false;
        }

        $excel = new PHPExcel();

        $excel->getProperties()->setCreator('SIAK')
            ->setLastModifiedBy('SIAK')
            ->setTitle('Jadwal Ujian')
            ->setSubject('Jadwal Ujian Mahasiswa')
            ->setDescription('Jadwal Ujian')
            ->setKeywords('jadwal')
            ->setCategory('SIAK - Jadwal Ujian')
        ;

        $excel->setActiveSheetIndex(0)
            ->setCellValue('A2', 'Jadwal Ujian')
        ;

        $excel->setActiveSheetIndex(0)
            ->setCellValue('A4', 'Ruangan')
            ->setCellValue('B4', 'Hari')
            ->setCellValue('C4', 'Tanggal')
            ->setCellValue('D4', 'Waktu')
            ->setCellValue('E4', 'Program Studi')
            ->setCellValue('F4', 'Konsentrasi')
            ->setCellValue('G4', 'Semester')
            ->setCellValue('H4', 'Kelas Mahasiswa')
            ->setCellValue('I4', 'Kode MK')
            ->setCellValue('J4', 'Matakuliah')
            ->setCellValue('K4', 'Dosen MK')
            ->setCellValue('L4', 'Pengawas')
            ->setCellValue('M4', 'Record ID SIAK')
            ->setCellValue('N4', 'Tahun Akademik');

        $col = 5;
        foreach ($data as $k=>$item) {
            $excel->setActiveSheetIndex(0)
                ->setCellValue('A'.$col, $item->ruangan)
                ->setCellValue('B'.$col, Text::_($item->hari))
                ->setCellValue('C'.$col, HTMLHelper::date($item->tanggal, 'd.m.Y'))
                ->setCellValue('D'.$col, $item->jam_mulai.' s/d '. $item->jam_akhir)
                ->setCellValue('E'.$col, $item->prodi)
                ->setCellValue('F'.$col, $item->konsentrasi)
                ->setCellValue('G'.$col, $item->semester)
                ->setCellValue('H'.$col, $item->kelas)
                ->setCellValue('I'.$col, $item->kodemk)
                ->setCellValue('J'.$col, $item->matakuliah)
                ->setCellValue('K'.$col, $item->dosen)
                ->setCellValue('L'.$col, $item->pengawas)
                ->setCellValue('M'.$col, $item->id)
                ->setCellValue('N'.$col, $item->tahun_ajaran);
            ++$col;
        }

        $excel->getActiveSheet()->setTitle('Jadwal Ujian');
        $excel->setActiveSheetIndex(0);

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Jadwal-Ujian.xlsx"');
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
    }
}
