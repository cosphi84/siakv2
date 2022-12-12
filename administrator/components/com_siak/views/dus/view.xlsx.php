<?php

use Joomla\CMS\Date\Date;

defined('_JEXEC') or exit;

class SiakViewDus extends JViewLegacy
{
    public function display($tpl = null)
    {
        $data = $this->get('Items');
        $filters = $this->get('ActiveFilters');
        $tanggal = new Date();

        $errors = $this->get('Errors');
        jimport('phpexcel.library.PHPExcel');
        JLoader::register('Siak', JPATH_COMPONENT_ADMINISTRATOR.'/helpers/siak.php');

        if (!class_exists('PHPExcel')) {
            $errors[] = 'Class PHP Excel Tidak ada!';
        }

        if (count($errors) > 0) {
            throw new Exception(implode("\n", $errors), 500);

            return false;
        }

        $objPHPExcel = new PHPExcel();

        $objPHPExcel->getProperties()->setCreator('SIAK')
            ->setLastModifiedBy('SIAK')
            ->setTitle('Daftar Mahasiswa')
            ->setSubject('Daftar Mahaiswa SIAK')
            ->setDescription('Daftar Mahasiswa pada tahun berjalan.')
            ->setKeywords('Mahasiswa siak')
            ->setCategory('SIAK Mahasiswa')
        ;

        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'Daftar Mahasiswa')
            ->setCellValue('A4', 'Tahun Akademik')
            ->setCellValue('A5', 'Tanggal Download')
            ->setCellValue('B4', $filters['ta'])
            ->setCellValue('B5', $tanggal->format('d F Y H:i:s'))
        ;
        $objPHPExcel->setActiveSheetIndex(0)
            ->mergeCells('A1:J1')
        ;

        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A9', 'No')
            ->setCellValue('B9', 'NPM')
            ->setCellValue('C9', 'Nama')
            ->setCellValue('D9', 'Semester')
            ->setCellValue('E9', 'Status')
            ->setCellValue('F9', 'Tanggal Dafta Ulang')
            ->setCellValue('G9', 'Prodi')
            ->setCellValue('H9', 'Jurusan')
            ->setCellValue('I9', 'Kelas')
            ->setCellValue('J9', 'Tahun Akademik')
        ;

        $col = 10;      // A7
        $num = 1;
        foreach ($data as $j => $item) {
            $tgl = new Date($item->create_date);
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A'.$col, $num)
                ->setCellValue('B'.$col, $item->npm)
                ->setCellValue('C'.$col, $item->mahasiswa)
                ->setCellValue('D'.$col, $item->semester)
                ->setCellValue('E'.$col, Siak::statusMahasiswa($item->status))
                ->setCellValue('F'.$col, $tgl->format('d/m/Y H:i:s'))
                ->setCellValue('G'.$col, $item->prodi)
                ->setCellValue('H'.$col, $item->jurusan)
                ->setCellValue('I'.$col, $item->kelas)
                ->setCellValue('J'.$col, $item->ta)
            ;
            ++$col;
            ++$num;
        }

        // Rename worksheet
        $objPHPExcel->getActiveSheet()->setTitle('Daftar Mahasiswa');

        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Daftar_Mahasiswa.xlsx"');
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
