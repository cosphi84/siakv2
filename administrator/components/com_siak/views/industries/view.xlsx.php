<?php

use Joomla\CMS\Date\Date;

defined('_JEXEC') or exit;
jimport('phpexcel.library.PHPExcel');
JLoader::register('Siak', JPATH_COMPONENT_ADMINISTRATOR.'/helpers/siak.php');
class SiakViewIndustries extends JViewLegacy
{
    public function display($tpl = null)
    {
        $data = $this->get('Items');
        $errors = $this->get('Errors');

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
            ->setTitle('Daftar Industri Rekanan')
            ->setSubject('Daftar Industri Rekanan SIAK')
            ->setDescription('Daftar Industri Rekanan.')
            ->setKeywords('Industri Rekanan siak')
            ->setCategory('SIAK Fakultas')
        ;

        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'Daftar Rekanan Industri')
        ;
        $objPHPExcel->setActiveSheetIndex(0)
            ->mergeCells('A1:J1')
        ;

        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A3', 'No')
            ->setCellValue('B3', 'Nama')
            ->setCellValue('C3', 'Alamat')
            ->setCellValue('D3', 'Kota / Kab')
            ->setCellValue('E3', 'Propinsi')
            ->setCellValue('F3', 'Telepon')
            ->setCellValue('G3', 'Email')
            ->setCellValue('H3', 'PIC')
            ->setCellValue('I3', 'Jabatan PIC')
            ->setCellValue('J3', 'Tlp Pic')
            ->setCellValue('K3', 'Dokumen Kerjasama')
            ->setCellValue('L3', 'Mulai')
            ->setCellValue('M3', 'Berakhir')
            ->setCellValue('N3', 'Tanggal Input')
            ->setCellValue('O3', 'Di input Oleh')
        ;

        $col = 4;      // A7
        $num = 1;
        foreach ($data as $j => $item) {
            $tgl = new Date($item->create_date);
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A'.$col, $num)
                ->setCellValue('B'.$col, $item->nama)
                ->setCellValue('C'.$col, $item->alamat)
                ->setCellValue('D'.$col, $item->kabupaten)
                ->setCellValue('E'.$col, $item->propinsi)
                ->setCellValue('F'.$col, $item->telepon)
                ->setCellValue('G'.$col, $item->email)
                ->setCellValue('H'.$col, $item->pic)
                ->setCellValue('I'.$col, $item->jabatan_pic)
                ->setCellValue('J'.$col, $item->telepon_pic)
                ->setCellValue('K'.$col, $item->no_dokumen_kerjasama)
                ->setCellValue('L'.$col, $item->tanggal_kerjasama)
                ->setCellValue('M'.$col, $item->tanggal_berakhir)
                ->setCellValue('N'.$col, $tgl->format('d F Y'))
                ->setCellValue('O'.$col, $item->dibuat_oleh)
            ;
            ++$col;
            ++$num;
        }

        // Rename worksheet
        $objPHPExcel->getActiveSheet()->setTitle('Daftar Industri');

        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Daftar_Rekanan.xlsx"');
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
