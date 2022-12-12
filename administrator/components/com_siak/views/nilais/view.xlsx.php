<?php

defined('_JEXEC') or exit;

class SiakViewNilais extends JViewLegacy
{
    public function display($tpl = null)
    {
        $data = $this->get('Items');
        $errors = $this->get('Errors');
        jimport('phpexcel.library.PHPExcel');

        if (!class_exists('PHPExcel')) {
            $errors[] = 'Class PHP Excel tidak ditemukan';
        }

        if (count($errors) > 0) {
            throw new Exception(implode("\n", $errors), 500);

            return false;
        }

        $excel = new PHPExcel();

        $excel->getProperties()->setCreator('SIAK')
            ->setLastModifiedBy('SIAK')
            ->setTitle('Daftar Nilai Mahasiswa')
            ->setSubject('Daftar Nilai Mahasiswa')
            ->setDescription('Nilai Mahasiswa')
            ->setKeywords('nilai mahasiswa siak')
            ->setCategory('SIAK : Nilai')
        ;

        $excel->setActiveSheetIndex(0)
            ->setCellValue('A2', 'Transkrip Nilai Mahasiswa')
        ;

        $excel->setActiveSheetIndex(0)
            ->setCellValue('A4', 'NPM')
            ->setCellValue('B4', 'Nama Mahasiswa')
            ->setCellValue('C4', 'Matakuliah')
            ->setCellValue('D4', 'Semester')
            ->setCellValue('E4', 'Program Studi')
            ->setCellValue('F4', 'Konsentrasi')
            ->setCellValue('G4', 'Kelas')
            ->setCellValue('H4', 'Nilai')
            ->setCellValue('H5', 'Kehadiran')
            ->setCellValue('I5', 'Tugas Mandiri')
            ->setCellValue('J5', 'UTS')
            ->setCellValue('K5', 'UAS')
            ->setCellValue('L5', 'Akhir')
            ->setCellValue('M5', 'Mutu (Angka)')
            ->setCellValue('N5', 'Mutu (Huruf)')
        ;

        $excel->setActiveSheetIndex(0)
            ->mergeCells('A4:A5')
            ->mergeCells('B4:B5')
            ->mergeCells('C4:C5')
            ->mergeCells('D4:D5')
            ->mergeCells('E4:E5')
            ->mergeCells('F4:F5')
          	->mergeCells('G4:G5')
            ->mergeCells('H4:N4')
        ;

        $col = 6;
        foreach ($data as $i => $j) {
            $excel->setActiveSheetIndex(0)
                ->setCellValue('A'.$col, $j->npm)
                ->setCellValue('B'.$col, $j->mahasiswa)
                ->setCellValue('C'.$col, $j->mk)
                ->setCellValue('D'.$col, $j->semester)
                ->setCellValue('E'.$col, $j->prodi)
                ->setCellValue('F'.$col, $j->jurusan)
                ->setCellValue('G'.$col, $j->kelas)
                ->setCellValue('H'.$col, $j->kehadiran)
                ->setCellValue('I'.$col, $j->tugas)
                ->setCellValue('J'.$col, $j->uts)
                ->setCellValue('K'.$col, $j->uas)
                ->setCellValue('L'.$col, $j->nilai_akhir)
                ->setCellValue('M'.$col, $j->nilai_mutu)
                ->setCellValue('N'.$col, $j->nilai_angka)
            ;
            ++$col;
        }

        $excel->getActiveSheet()->setTitle('Daftar Nilai');
        $excel->setActiveSheetIndex(0);

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Daftar_NILAI.xlsx"');
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

        return true;
    }
}
