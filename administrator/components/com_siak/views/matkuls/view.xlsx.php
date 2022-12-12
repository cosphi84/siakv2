<?php

defined('_JEXEC') or exit;

class SiakViewMatkuls extends JViewLegacy
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
            ->setTitle('Daftar Matakuliah Aktif')
            ->setSubject('Daftar Matakuiah Aktif SIAK')
            ->setDescription('Daftar matakuliah Aktif yang ada di SIAK.')
            ->setKeywords('matakuliah siak')
            ->setCategory('SIAK Matakuliah')
        ;

        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'Record ID')
            ->setCellValue('B1', 'Kode MK')
            ->setCellValue('C1', 'Nama Matakuliah')
            ->setCellValue('D1', 'Bobot SKS')
            ->setCellValue('E1', 'Jenis Matakuiah')
            ->setCellValue('F1', 'Uang MK')
            ->setCellValue('G1', 'Prodi')
            ->setCellValue('H1', 'Jurusan')
        ;

        $col = 2;
        foreach ($data as $j => $item) {
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A'.$col, $item->id)
                ->setCellValue('B'.$col, strtoupper($item->kode))
                ->setCellValue('C'.$col, strtoupper($item->matkul))
                ->setCellValue('D'.$col, $item->sks)
                ->setCellValue('E'.$col, $item->tipe_mk)
                ->setCellValue('F'.$col, $item->uang_mk)
                ->setCellValue('G'.$col, $item->prodi)
                ->setCellValue('H'.$col, $item->jurusan)
            ;
            ++$col;
        }

        // Rename worksheet
        $objPHPExcel->getActiveSheet()->setTitle('Daftar MK');

        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Daftar_MK_SIAK.xlsx"');
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
