<?php

defined('_JEXEC') or die;

class SiakViewKps extends JViewLegacy
{
    public function display($tpl = null)
    {
        $items = $this->get('Items');
        $errors = $this->get('Errors');
        jimport('phpexcel.library.PHPExcel');

        if (!class_exists('PHPExcel')) {
            $errors[] = 'Class PHP Excel Tidak ada!';
        }

        if (count($errors) > 0) {
            throw new Exception(implode('<br />', $errors), 500);

            return false;
        }

        $objPHPExcel = new PHPExcel();

        $objPHPExcel->getProperties()->setCreator('SIAK')
            ->setLastModifiedBy('SIAK')
            ->setTitle('Daftar KP Mahasiswa')
            ->setSubject('Daftar KP Mahaiswa SIAK')
            ->setDescription('Daftar KP Mahasiswa pada tahun berjalan.')
            ->setKeywords('KP siak')
            ->setCategory('SIAK Mahasiswa')
        ;

        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A2', 'Data Pendaftaran KP Tahun Akademik ')
        ;
        $objPHPExcel->setActiveSheetIndex(0)
            ->mergeCells('A2:M2')
        ;

        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A4', 'Program Studi :')
            ->setCellValue('A5', 'Tahun Akademik :')
            ->setCellValue('D4', $items[0]->prodi)
            ->setCellValue('D5', $items[0]->ta)
        ;

        $objPHPExcel->setActiveSheetIndex(0)
            ->mergeCells('A4:C4')
            ->mergeCells('D4:F4')
            ->mergeCells('A5:C5')
            ->mergeCells('D5:F5')
        ;

        $header = [
            'A7' => 'No',
            'B7' => 'NPM',
            'C7' => 'Nama',
            'D7' => 'Kelas',
            'E7' => 'Periode',
            'F7' => 'No Surat',
            'G7' => 'Tempat KP',
            'H7' => 'Alamat',
            'I7' => 'PIC',
            'J7' => 'Telepon',
            'K7' => 'Tanggal Sidang KP',
            'L7' => 'Judul Laporan KP',
            'M7' => 'Dosen Pembimbing',
        ];

        foreach ($header as $k => $v) {
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue($k, $v)
            ;
        }

        $col = 8;
        $num = 1;
        foreach ($items as $i => $j) {
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A'.$col, $num)
                ->setCellValue('B'.$col, $j->npm)
                ->setCellValue('C'.$col, $j->mahasiswa)
                ->setCellValue('D'.$col, $j->kelas)
                ->setCellValue('E'.$col, JHtml::_('date', $j->start, 'd/m/Y').' s/d '.JHtml::_('date', $j->finish, 'd/m/Y'))
                ->setCellValue('F'.$col, $j->no_surat)
                ->setCellValue('G'.$col, $j->tempatKP)
                ->setCellValue('H'.$col, $j->alamat.', '.$j->kabupaten)
                ->setCellValue('I'.$col, $j->pic)
                ->setCellValue('J'.$col, $j->telepon_pic)
                ->setCellValue('K'.$col, JHtml::_('date', $j->tanggal_seminar, 'd/m/Y'))
                ->setCellValue('L'.$col, $j->judul_laporan)
                ->setCellValue('M'.$col, $j->dosbing)
            ;
            ++$num;
            ++$col;
        }

        // Rename worksheet
        $objPHPExcel->getActiveSheet()->setTitle('Daftar KP');

        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Daftar_KP.xlsx"');
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
