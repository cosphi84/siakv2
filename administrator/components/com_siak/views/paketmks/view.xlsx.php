<?php

defined('_JEXEC') or exit;

class SiakViewPaketmks extends JViewLegacy
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
            ->setTitle('Daftar Paket Matakuliah Aktif')
            ->setSubject('Daftar Paket Matakuiah Aktif SIAK')
            ->setDescription('Daftar paket matakuliah Aktif yang ada di SIAK.')
            ->setKeywords('matakuliah siak')
            ->setCategory('SIAK Matakuliah')
        ;

        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'No')
            ->setCellValue('B1', 'Record ID')
            ->setCellValue('C1', 'Prodi')
            ->setCellValue('D1', 'Jurusan')
            ->setCellValue('E1', 'Semester')
            ->setCellValue('F1', 'Kode MK')
            ->setCellValue('G1', 'Nama Matakuliah')
            ->setCellValue('H1', 'Bobot SKS')
            ->setCellValue('I1', 'Jenis Matakuiah')
        ;

        $col = 2;
        $num = 1;
        foreach ($data as $j => $item) {
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A'.$col, $num)
                ->setCellValue('B'.$col, $item->id)
                ->setCellValue('C'.$col, $item->prodi)
                ->setCellValue('D'.$col, $item->jurusan)
                ->setCellValue('E'.$col, $item->semester)
                ->setCellValue('F'.$col, $item->kodeMK)
                ->setCellValue('G'.$col, $item->namaMK)
                ->setCellValue('H'.$col, $item->sks)
                ->setCellValue('I'.$col, $item->jenisMK)
            ;
            ++$col;
            ++$num;
        }

        // Rename worksheet
        $objPHPExcel->getActiveSheet()->setTitle('Daftar Paket MK');

        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Daftar_Paket_MK_SIAK.xlsx"');
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
