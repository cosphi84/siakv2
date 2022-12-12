<?php

defined('_JEXEC') or exit;

use Joomla\CMS\Date\Date;
use PHPExcel_Style_Alignment;

jimport('phpexcel.library.PHPExcel');
JLoader::register('Siak', JPATH_COMPONENT_ADMINISTRATOR.'/helpers/siak.php');

class SiakViewBayarans extends JViewLegacy
{
    public function display($tpl = null)
    {
        $data = $this->get('Items');
        $filters = $this->get('ActiveFilters');
        $tanggal = new Date();
        $stsLunas = ['Belum Bayar', 'Belum Lunas', 'Lunas'];
        $stsConfirm = ['Unconfirmed', 'Confirmed'];

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
            ->setTitle('Daftar Pembayaran')
            ->setSubject('Daftar Pembayaran Mahasiswa')
            ->setDescription('Daftar pembayaran Mahasiswa.')
            ->setKeywords('Mahasiswa siak')
            ->setCategory('SIAK Mahasiswa')
        ;

        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'Catatn Konfirmasi Pembayaran Mahasiswa')
            ->setCellValue('A4', 'Tahun Akademik')
            ->setCellValue('A5', 'Tanggal Download')
            ->setCellValue('B4', $filters['ta'])
            ->setCellValue('B5', $tanggal->format('d F Y H:i:s'))
        ;
        $objPHPExcel->setActiveSheetIndex(0)
            ->mergeCells('A1:J1')
            ->mergeCells('B5:E5')
            ->mergeCells('A9:A10')
            ->mergeCells('B9:B10')
            ->mergeCells('C9:C10')
            ->mergeCells('D9:D10')
            ->mergeCells('E9:E10')
            ->mergeCells('F9:F10')
            ->mergeCells('G9:G10')

            ->mergeCells('H9:I9')
            ->mergeCells('J9:L9')

            ->mergeCells('M9:M10')
            ->mergeCells('N9:Q9')
        ;

        $objPHPExcel->getActiveSheet(0)
            ->getStyle('H9')
            ->getAlignment()
            ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
        ;
        $objPHPExcel->getActiveSheet(0)
            ->getStyle('J9')
            ->getAlignment()
            ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
        ;
        $objPHPExcel->getActiveSheet(0)
            ->getStyle('N9')
            ->getAlignment()
            ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
        ;

        $objPHPExcel->getActiveSheet(0)
            ->getStyle('A9')
            ->getAlignment()
            ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
            ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER)
        ;
        $objPHPExcel->getActiveSheet(0)
            ->getStyle('B9')
            ->getAlignment()
            ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
            ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER)
        ;
        $objPHPExcel->getActiveSheet(0)
            ->getStyle('C9')
            ->getAlignment()
            ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
            ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER)
        ;
        $objPHPExcel->getActiveSheet(0)
            ->getStyle('D9')
            ->getAlignment()
            ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
            ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER)
        ;
        $objPHPExcel->getActiveSheet(0)
            ->getStyle('E9')
            ->getAlignment()
            ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
            ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER)
        ;
        $objPHPExcel->getActiveSheet(0)
            ->getStyle('F9')
            ->getAlignment()
            ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
            ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER)
        ;
        $objPHPExcel->getActiveSheet(0)
            ->getStyle('G9')
            ->getAlignment()
            ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
            ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER)
        ;
        $objPHPExcel->getActiveSheet(0)
            ->getStyle('M9')
            ->getAlignment()
            ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
            ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER)
        ;

        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A9', 'No')
            ->setCellValue('B9', 'NPM')
            ->setCellValue('C9', 'Nama')
            ->setCellValue('D9', 'Semester')
            ->setCellValue('E9', 'Tahun Ajaran')
            ->setCellValue('F9', 'No Referensi')
            ->setCellValue('G9', 'Pembayaran')

            ->setCellValue('H9', 'Mata Kuliah')
            ->setCellValue('H10', 'Kode MK')
            ->setCellValue('I10', 'Nama MK')

            ->setCellValue('J9', 'Setoran')
            ->setCellValue('J10', 'Tanggal')
            ->setCellValue('K10', 'Jumlah')
            ->setCellValue('L10', 'Jenis')

            ->setCellValue('M9', 'Status Tagihan')

            ->setCellValue('N9', 'Validasi')
            ->setCellValue('N10', 'Status')
            ->setCellValue('O10', 'Tanggal - Jam')
            ->setCellValue('P10', 'Petugas')
            ->setCellValue('Q10', 'Catatan')

        ;

        $col = 11;      // A7
        $num = 1;
        foreach ($data as $j => $item) {
            $tglSetor = new Date($item->tanggal_bayar);
            $tglConfirm = new Date($item->confirm_time);

            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A'.$col, $num)
                ->setCellValue('B'.$col, $item->npm)
                ->setCellValue('C'.$col, $item->mahasiswa)
                ->setCellValue('D'.$col, $item->semester)
                ->setCellValue('E'.$col, $item->ta)
                ->setCellValue('F'.$col, $item->no_ref)
                ->setCellValue('G'.$col, $item->pembayaran)
                ->setCellValue('H'.$col, $item->kodemk)
                ->setCellValue('I'.$col, $item->mk)
                ->setCellValue('J'.$col, $tglSetor->format('d/m/Y'))
                ->setCellValue('K'.$col, $item->jumlah)
                ->setCellValue('L'.$col, $item->tipe_bayar)
                ->setCellValue('M'.$col, $stsLunas[$item->lunas])
                ->setCellValue('N'.$col, $stsConfirm[$item->confirm])
                ->setCellValue('O'.$col, $tglConfirm->format('d/m/Y H:i:s'))
                ->setCellValue('P'.$col, $item->confirm_user)
                ->setCellValue('Q'.$col, $item->confirm_note)
            ;

            $objPHPExcel->getActiveSheet(0)
                ->getStyle('K'.$col)
                ->getNumberFormat()
                ->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00)
            ;
            $objPHPExcel->getActiveSheet(0)
                ->getStyle('A'.$col)
                ->getAlignment()
                ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
                ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER)
            ;
            ++$col;
            ++$num;
        }
        $style = [
            'borders' => [
                'allborders' => [
                    'style' => PHPExcel_Style_Border::BORDER_MEDIUM,
                ],
            ],
        ];
        $objPHPExcel->getActiveSheet()
            ->getStyle('A9:Q'.$col)
            ->applyFromArray($style)
        ;

        // Rename worksheet
        $objPHPExcel->getActiveSheet()->setTitle('Daftar pembayaran');

        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Daftar_Pembayaran.xlsx"');
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
