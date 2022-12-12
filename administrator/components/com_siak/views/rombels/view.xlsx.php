<?php

defined('_JEXEC') or exit;

use Joomla\CMS\Date\Date;

class SiakViewRombels extends JViewLegacy
{
    public function display($tpl = null)
    {
        $data = $this->get('Items');
        $errors = $this->get('Errors');
        jimport('phpexcel.library.PHPExcel');

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
            ->setTitle('Datar Absensi Mahasiswa')
            ->setSubject('SIAK Rombels')
            ->setDescription('Daftar absensi Mahasiswa pada Mata Kuliah')
            ->setKeywords('Absensi siak')
            ->setCategory('SIAK')
        ;

        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A2', 'Mata Kuliah :')
            ->setCellValue('A3', 'Tanggal :')
            ->setCellValue('B2', $data[0]->MK)
            ->setCellValue('B3', JHtml::_('date', new Date(), 'd F Y'))
        ;

        $header = [
            'A6' => 'No',
            'B6' => 'NPM',
            'C6' => 'Nama Mahasiswa',
            'D6' => 'Kelas',
        ];
        foreach ($header as $key => $val) {
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($key, $val);
        }

        $col = 7;
        $num = 1;
        foreach ($data as $j => $item) {
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A'.$col, $num)
                ->setCellValue('B'.$col, $item->npm)
                ->setCellValue('C'.$col, $item->mahasiswa)
                ->setCellValue('D'.$col, $item->kelas)
            ;
            ++$col;
            ++$num;
        }

        // Rename worksheet
        $objPHPExcel->getActiveSheet()->setTitle('Absensi');

        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Absensi_Mahasiswa.xlsx"');
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
