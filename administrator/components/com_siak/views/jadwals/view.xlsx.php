<?php

defined('_JEXEC') or die();

use Joomla\CMS\MVC\View\HtmlView;

class SiakViewJadwals extends HtmlView
{
    public function display($tpl = null)
    {
        $items =  $this->get('Items');
        $errors = $this->get('Errors');

        if (count($errors) > 0) {
            throw new Exception(implode('/n', $errors), 500);
            return false;
        }

        jimport('phpexcel.library.PHPExcel');
        if (!class_exists('PHPExcel')) {
            throw new Exception('Class PHPExcel Not Found!.', 500);
            return false;
        }

        $objPHPExcel = new PHPExcel();

        $objPHPExcel->getProperties()->setCreator('SIAK')
            ->setLastModifiedBy('SIAK')
            ->setTitle('Jadwal KBM')
            ->setSubject('Jadwal KBM')
            ->setDescription('Jadwal Kegiatan Belajar Mengajar FT.')
            ->setKeywords('jadwal')
            ->setCategory('SIAK')
        ;

        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'Hari')
            ->setCellValue('B1', 'Jam')
            ->setCellValue('C1', 'Ruangan')
            ->setCellValue('D1', 'Mata Kuliah')
            ->setCellValue('E1', 'Prodi')
            ->setCellValue('F1', 'Konsentrasi')
            ->setCellValue('G1', 'Kelas Mahasiswa')
            ->setCellValue('H1', 'Semester')
            ->setCellValue('I1', 'Tahun Akademik');

        $col = 2;
        foreach ($items as $key => $item) {
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A'.$col, Siak::hari($item->hari))
                ->setCellValue('B'.$col, $item->jam)
                ->setCellValue('C'.$col, $item->ruangan)
                ->setCellValue('D'.$col, $item->kodeMK . ' - '. $item->matakuliah)
                ->setCellValue('E'.$col, $item->prodi)
                ->setCellValue('F'.$col, $item->konsentrasi)
                ->setCellValue('G'.$col, $item->kelas)
                ->setCellValue('H'.$col, $item->semester)
                ->setCellValue('I'.$col, $item->tahun_ajaran);
            $col++;
        }

        // Rename worksheet
        $objPHPExcel->getActiveSheet()->setTitle('Jadwal KBM');

        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Jadwal-KBM.xlsx"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');

        // If you're serving to IE over SSL, then the following may be needed
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header('Pragma: public'); // HTTP/1.0

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');

        return true;
    }
}
