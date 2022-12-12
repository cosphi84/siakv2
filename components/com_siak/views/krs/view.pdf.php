<?php
/**
 * @copyright   Copyright (c) 2020 @ Risam C20201161017, All rights reserved.
 *              A Software Engineering Final Projects,
 *              Majors of Electronics Controls Systems,
 *              In Program Study of Electrical Engineering
 *              Technical Faculty, 17 Agustus 1945 Univeristy Cirebon
 *              West Java, Indonesia
 * @license     This work is licensed under the terms of the MIT license. see LICENSE
 */

use Joomla\CMS\Date\Date;

defined('_JEXEC') or exit;
jimport('tcpdf.tcpdf');

class SiakViewKrs extends JViewLegacy
{
    public function display($tpl = null)
    {
        $app = JFactory::getApplication();
        if (!class_exists('TCPDF')) {
            $app->enqueueMessage(JText::sprintf('COM_SIAK_ERROR_LIBRARY_NOT_FOUND', 'TCPDF'), 'error');
            $app->redirect(JRoute::_('index.php?option=com_siak&view=krss'));

            return false;
        }
        $item = $this->get('Data');

        $namaFilePDF = 'KRS_'.$item->npm.'.pdf';
        $errors = $this->get('Errors');
        $params = JComponentHelper::getParams('com_siak');

        if (count($errors) > 0) {
            throw new Exception(implode("\n", $errors), 500);

            return false;
        }

        $tanggalKRS = new Date($item->created_time);

        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        // set document information
        $pdf->SetCreator('SIAK '.$params->get('universitas'));
        $pdf->SetAuthor($params->get('fakultas'));
        $pdf->SetTitle('Kartu Rencana Studi');
        $pdf->SetSubject('KRS');

        // Remove Header n Footer
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);

        // set default monospaced font
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        // set margins
        $pdf->SetMargins(0, 0, 0);

        // set auto page breaks
        $pdf->SetAutoPageBreak(true, PDF_MARGIN_BOTTOM);

        // set image scale factor
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

        // set some language-dependent strings (optional)
        if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
            require_once dirname(__FILE__).'/lang/eng.php';
            $pdf->setLanguageArray($l);
        }

        // ---------------------------------------------------------

        // set font
        $pdf->SetFont('helvetica', '', 10);

        // add a page
        $pdf->AddPage();

        // create Header
        $logo = JPATH_BASE.'/media/com_siak/images/untag-transparent.jpg';
        $pdf->Image($logo, 10, 10, 20, 20);
        $pdf->SetXY(40, 13);
        $pdf->Cell(100, 0, strtoupper($params['universitas']), 0, 1, 'L', 0, '', 0);
        $pdf->SetXY(40, 18);
        $pdf->Cell(100, 0, strtoupper($params['fakultas']), 0, 1, 'L', 0, '', 0);
        $pdf->Line(40, 25, 115, 25, ['width' => 0.5, 'cap' => 'square', 'join' => 'mitter', 'dash' => 0]);

        $pdf->SetXY(71, 35);
        $pdf->SetFont('helvetica', 'B', '14');
        $pdf->Cell(76, 0, 'KARTU RENCANA STUDI', 0, 1, 'C', 0, '', 0);
        $pdf->SetXY(60, 40);
        $pdf->Cell(95, 0, '( K R S )', 0, 1, 'C', 0, '', 0);

        $pdf->Line(75, 33, 143, 33, ['width' => 0.8, 'cap' => 'square', 'join' => 'mitter', 'dash' => 0]);
        $pdf->Line(75, 47, 143, 47, ['width' => 0.8, 'cap' => 'square', 'join' => 'mitter', 'dash' => 0]);
        $pdf->Line(75, 47, 75, 33, ['width' => 0.8, 'cap' => 'square', 'join' => 'mitter', 'dash' => 0]);
        $pdf->Line(143, 47, 143, 33, ['width' => 0.8, 'cap' => 'square', 'join' => 'mitter', 'dash' => 0]);

        // ISI KRS
        $pdf->setFont('helvetica', '', '10');
        //$border_style = ['all' => ['width' => 2, 'cap' => 'square', 'join' => 'miter', 'dash' => 0, 'phase' => 0]];
        //$pdf->SetDrawColor(255, 127, 127);
        $pdf->SetFillColor(255, 255, 255);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->Rect(10, 40, 30, 6, 'DF', );
        $pdf->Text(12, 41, 'MAHASISWA');

        $pdf->setFont('helvetica', '', '10');

        $pdf->SetXY(10, 52);
        $pdf->cell(30, 0, 'Nama Mahasiswa');
        $pdf->cell(32, 0, ': '.$item->mahasiswa);
        $pdf->SetXY(120, 52);
        $pdf->cell(30, 0, 'Semester');
        $pdf->cell(32, 0, ': '.$item->semester);

        $pdf->SetXY(10, 57);
        $pdf->cell(30, 0, 'N P M');
        $pdf->cell(32, 0, ': '.$item->npm);
        $pdf->SetXY(120, 57);
        $pdf->cell(30, 0, 'Prog. Studi');
        $pdf->cell(32, 0, ': '.$item->nama_prodi_alias);

        $pdf->SetXY(10, 62);
        $pdf->cell(30, 0, 'Alamat');
        $pdf->cell(32, 0, ': '.$item->alamat_1);
        $pdf->SetXY(120, 62);
        $pdf->cell(30, 0, 'Nama Dosen Wali');
        $pdf->cell(32, 0, ': '.$item->dosenwali);

        $pdf->SetXY(10, 70);

        $html = '<table border="1" cellspacing="1" cellpadding="2">';
        $html .= '</thead>';
        $html .= '<tr>';
        $html .= '<th rowspan = "2" width="11mm" align="center">No Urut</th>';
        $html .= '<th colspan = "3" width="107mm" align="center">Mata Kuliah</th>';
        // $html .= '<td align="center">Kode MK</td>';
        //$html .= '<td align="center">Nama MK</td>';
        //$html .= '<td>Kredit</td>';
        $html .= '<th rowspan="2" align="center;" width="22mm">Sandi<br>Dosen</th>';
        $html .= '<th rowspan="2" style="display:table-cell; text-align: center; vertical-align: middle;" width="50mm">Hari,<br>Jam Kuliah</th>';
        $html .= '</tr>';
        $html .= '<tr><th width="31mm">Kode MK</th><th width="61mm">Nama MK</th><th width="15mm">Kredit</th></tr>';
        $html .= '</thead>';
        $html .= '<tbody>';
        $i = 1;
        for ($a = 0; $a <= 12; ++$a) {
            if (strlen($item->{$a}->MK) > 20) {
                $textMK = substr($item->{$a}->MK, 0, 20).'...';
            } else {
                $textMK = $item->{$a}->MK;
            }
            $html .= '<tr>'.
                        '<td>'.$i.'</td>'.
                        '<td>'.$item->{$a}->kode.'</td>'.
                        '<td align="left">'.$textMK.'</td>'.
                        '<td>'.$item->{$a}->sks.'</td>'.
                        '<td> </td>'.
                        '<td> </td>';

            $html .= '</tr>';
            ++$i;
        }
        $html .= '</tbody></table>';

        $pdf->writeHTML($html, true, false, false, false, 'C');

        $pdf->SetXY(98, 158);
        $pdf->cell(40, 0, 'Jumlah:');
        $pdf->SetXY(153, 160);
        $pdf->cell(50, 0, 'Cirebon, '.$tanggalKRS->format('d F Y'), 0, 0, 'R');
        $pdf->SetXY(119, 158);
        $pdf->cell(40, 0, $item->ttl_sks);

        $pdf->Line(114, 145, 114, 164, ['width' => 0.5, 'cap' => 'square', 'join' => 'mitter', 'dash' => 0]);
        $pdf->Line(129.3, 145, 129.3, 164, ['width' => 0.5, 'cap' => 'square', 'join' => 'mitter', 'dash' => 0]);
        $pdf->Line(114, 164, 129.3, 164, ['width' => 0.5, 'cap' => 'square', 'join' => 'mitter', 'dash' => 0]);

        $pdf->SetXY(10, 169);
        $pdf->cell(80, 0, 'PKRS Diisi hanya bila ada Perubahan Rencana Studi');
        $pdf->SetXY(162, 169);
        $pdf->cell(10, 0, 'K R S');
        $pdf->SetXY(185, 169);
        $pdf->cell(10, 0, 'PKRS');
        $pdf->SetXY(10, 175);
        $pdf->cell(140, 0, 'Perubahan KRS (PKRS)', 1, 1, 'C');

        $tablePKRS = '<table border="1" cellspacing="1" cellpadding="2" width="141mm">'.
                        '<thead>'.
                            '<tr>'.
                                '<th colspan="2" width="20mm" align="center">Status<br>Perubahan</th>'.
                                '<th colspan="3" width="95mm" align="center">Mata Kuliah</th>'.
                                '<th rowspan="2" width="23mm" align="center">Sandi<br>Dosen</th>'.
                            '</tr>'.
                            '<tr>'.
                                '<th width="10mm" align="center">No</th>'.
                                '<th width="10mm" align="center">G/T/P</th>'.
                                '<th width="31mm" align="center">Kode MK</th>'.
                                '<th width="50mm" align="center">Nama MK</th>'.
                                '<th width="14mm" align="center">Kredit</th>'.
                            '</tr></thead>';
        $tablePKRS .= '<tbody>';
        for ($i = 1; $i <= 3; ++$i) {
            $tablePKRS .= '<tr>';
            $tablePKRS .= '<td width="10mm" align="center">'.$i.'</td>';
            $tablePKRS .= '<td width="10mm" align="center"> </td>';
            $tablePKRS .= '<td width="31mm" align="center"> </td>';
            $tablePKRS .= '<td width="50mm" align="center"> </td>';
            $tablePKRS .= '<td width="14mm" align="center"> </td>';
            $tablePKRS .= '<td width="23mm" align="center"> </td>';
            $tablePKRS .= '</tr>';
        }

        $pdf->SetXY(10, 181);
        $tablePKRS .= '</tbody></table>';
        $pdf->writeHTML($tablePKRS, true, false, false, false, 'C');

        $pdf->SetXY(10.1, 219);
        $pdf->cell(102.2, 0, 'Jumlah', 1, 1, 'R');
        $pdf->Line(126.5, 219, 126.5, 224, ['width' => 0.5, 'cap' => 'square', 'join' => 'mitter', 'dash' => 0]);
        $pdf->Line(112.2, 224, 126.5, 224, ['width' => 0.5, 'cap' => 'square', 'join' => 'mitter', 'dash' => 0]);

        $pdf->SetXY(10, 226);
        $pdf->cell(30, 0, 'G : Ganti Mata Kuliah');
        $pdf->SetXY(100, 226);
        $pdf->cell(30, 0, 'Cirebon, ____________________');
        $pdf->SetXY(10, 230);
        $pdf->cell(30, 0, 'T : Tambah Mata Kuliah');
        $pdf->SetXY(10, 234);
        $pdf->cell(30, 0, 'P : Pengurangan Mata Kuliah');

        $pdf->SetXY(60, 240);
        $pdf->cell(30, 0, 'Total Kredit Akhir : ');
        $pdf->Line(95, 237.5, 95, 247, ['width' => 0.5, 'cap' => 'square', 'join' => 'mitter', 'dash' => 0]);
        $pdf->Line(110, 237.5, 110, 247, ['width' => 0.5, 'cap' => 'square', 'join' => 'mitter', 'dash' => 0]);
        $pdf->Line(95, 237.5, 110, 237.5, ['width' => 0.5, 'cap' => 'square', 'join' => 'mitter', 'dash' => 0]);
        $pdf->Line(110, 247, 95, 247, ['width' => 0.5, 'cap' => 'square', 'join' => 'mitter', 'dash' => 0]);

        // ttd KRS
        $pdf->Line(156, 175, 181, 175, ['width' => 0.5, 'cap' => 'square', 'join' => 'mitter', 'dash' => 0]);
        $pdf->Line(156, 250, 181, 250, ['width' => 0.5, 'cap' => 'square', 'join' => 'mitter', 'dash' => 0]);
        $pdf->Line(156, 175, 156, 250, ['width' => 0.5, 'cap' => 'square', 'join' => 'mitter', 'dash' => 0]);
        $pdf->Line(181, 175, 181, 250, ['width' => 0.5, 'cap' => 'square', 'join' => 'mitter', 'dash' => 0]);
        $pdf->Line(156, 200, 181, 200, ['width' => 0.5, 'cap' => 'square', 'join' => 'mitter', 'dash' => 0]);
        $pdf->Line(156, 225, 181, 225, ['width' => 0.5, 'cap' => 'square', 'join' => 'mitter', 'dash' => 0]);

        // ttd PKRS
        $pdf->Line(182, 175, 201, 175, ['width' => 0.5, 'cap' => 'square', 'join' => 'mitter', 'dash' => 0]);
        $pdf->Line(182, 250, 201, 250, ['width' => 0.5, 'cap' => 'square', 'join' => 'mitter', 'dash' => 0]);
        $pdf->Line(182, 175, 182, 250, ['width' => 0.5, 'cap' => 'square', 'join' => 'mitter', 'dash' => 0]);
        $pdf->Line(201, 175, 201, 250, ['width' => 0.5, 'cap' => 'square', 'join' => 'mitter', 'dash' => 0]);

        $pdf->Line(182, 200, 201, 200, ['width' => 0.5, 'cap' => 'square', 'join' => 'mitter', 'dash' => 0]);
        $pdf->Line(182, 225, 201, 225, ['width' => 0.5, 'cap' => 'square', 'join' => 'mitter', 'dash' => 0]);

        $pdf->SetXY(156, 175);
        $pdf->cell(25, 0, 'Tanda tangan', 1, 0, 'C');
        $pdf->SetXY(182, 175);
        $pdf->cell(19, 0, 'Paraf', 1, 0, 'C');

        $pdf->SetXY(156, 195);
        $pdf->cell(25, 0, 'Mahasiswa', 0, 0, 'C');
        $pdf->SetXY(182, 195);
        $pdf->cell(19, 0, 'Mahasiswa', 0, 0, 'C');

        $pdf->SetXY(156, 200);
        $pdf->cell(25, 0, 'Menyetujui', 0, 0, 'C');
        $pdf->SetXY(182, 200);
        $pdf->cell(19, 0, 'Menyetujui', 0, 0, 'C');

        $pdf->SetXY(156, 220);
        $pdf->cell(25, 0, 'Dosen Wali', 0, 0, 'C');
        $pdf->SetXY(182, 220);
        $pdf->cell(19, 0, 'Dosen Wali', 0, 0, 'C');

        $pdf->SetXY(156, 245);
        $pdf->cell(25, 0, 'BAAK', 0, 0, 'C');
        $pdf->SetXY(182, 245);
        $pdf->cell(19, 0, 'BAAK', 0, 0, 'C');

        $pdf->Line(156, 200, 181, 200, ['width' => 0.5, 'cap' => 'square', 'join' => 'mitter', 'dash' => 0]);
        $pdf->Line(156, 225, 181, 225, ['width' => 0.5, 'cap' => 'square', 'join' => 'mitter', 'dash' => 0]);

        // Page 2 BAAK

        // set font
        $pdf->SetFont('helvetica', '', 10);

        // add a page
        $pdf->AddPage();

        // create Header
        $logo = JPATH_BASE.'/media/com_siak/images/untag-transparent.jpg';
        $pdf->Image($logo, 10, 10, 20, 20);
        $pdf->SetXY(40, 13);
        $pdf->Cell(100, 0, strtoupper($params['universitas']), 0, 1, 'L', 0, '', 0);
        $pdf->SetXY(40, 18);
        $pdf->Cell(100, 0, strtoupper($params['fakultas']), 0, 1, 'L', 0, '', 0);
        $pdf->Line(40, 25, 115, 25, ['width' => 0.5, 'cap' => 'square', 'join' => 'mitter', 'dash' => 0]);

        $pdf->SetXY(71, 35);
        $pdf->SetFont('helvetica', 'B', '14');
        $pdf->Cell(76, 0, 'KARTU RENCANA STUDI', 0, 1, 'C', 0, '', 0);
        $pdf->SetXY(60, 40);
        $pdf->Cell(95, 0, '( K R S )', 0, 1, 'C', 0, '', 0);

        $pdf->Line(75, 33, 143, 33, ['width' => 0.8, 'cap' => 'square', 'join' => 'mitter', 'dash' => 0]);
        $pdf->Line(75, 47, 143, 47, ['width' => 0.8, 'cap' => 'square', 'join' => 'mitter', 'dash' => 0]);
        $pdf->Line(75, 47, 75, 33, ['width' => 0.8, 'cap' => 'square', 'join' => 'mitter', 'dash' => 0]);
        $pdf->Line(143, 47, 143, 33, ['width' => 0.8, 'cap' => 'square', 'join' => 'mitter', 'dash' => 0]);

        // ISI KRS
        $pdf->setFont('helvetica', '', '10');
        //$border_style = ['all' => ['width' => 2, 'cap' => 'square', 'join' => 'miter', 'dash' => 0, 'phase' => 0]];
        $pdf->SetDrawColor(0, 0, 255);
        $pdf->SetFillColor(0, 0, 255);
        $pdf->SetTextColor(255, 255, 255);
        $pdf->Rect(10, 40, 30, 6, 'DF', );
        $pdf->Text(17, 41, 'B A A K');
        $pdf->SetDrawColor(0, 0, 0);
        $pdf->SetFillColor(0, 0, 0);
        $pdf->SetTextColor(0, 0, 0);

        $pdf->setFont('helvetica', '', '10');

        $pdf->SetXY(10, 52);
        $pdf->cell(30, 0, 'Nama Mahasiswa');
        $pdf->cell(32, 0, ': '.$item->mahasiswa);
        $pdf->SetXY(120, 52);
        $pdf->cell(30, 0, 'Semester');
        $pdf->cell(32, 0, ': '.$item->semester);

        $pdf->SetXY(10, 57);
        $pdf->cell(30, 0, 'N P M');
        $pdf->cell(32, 0, ': '.$item->npm);
        $pdf->SetXY(120, 57);
        $pdf->cell(30, 0, 'Prog. Studi');
        $pdf->cell(32, 0, ': '.$item->nama_prodi_alias);

        $pdf->SetXY(10, 62);
        $pdf->cell(30, 0, 'Alamat');
        $pdf->cell(32, 0, ': '.$item->alamat_1);
        $pdf->SetXY(120, 62);
        $pdf->cell(30, 0, 'Nama Dosen Wali');
        $pdf->cell(32, 0, ': '.$item->dosenwali);

        $pdf->SetXY(10, 70);

        $html = '<table border="1" cellspacing="1" cellpadding="2">';
        $html .= '</thead>';
        $html .= '<tr>';
        $html .= '<th rowspan = "2" width="11mm" align="center">No Urut</th>';
        $html .= '<th colspan = "3" width="107mm" align="center">Mata Kuliah</th>';
        // $html .= '<td align="center">Kode MK</td>';
        //$html .= '<td align="center">Nama MK</td>';
        //$html .= '<td>Kredit</td>';
        $html .= '<th rowspan="2" align="center;" width="22mm">Sandi<br>Dosen</th>';
        $html .= '<th rowspan="2" style="display:table-cell; text-align: center; vertical-align: middle;" width="50mm">Hari,<br>Jam Kuliah</th>';
        $html .= '</tr>';
        $html .= '<tr><th width="31mm">Kode MK</th><th width="61mm">Nama MK</th><th width="15mm">Kredit</th></tr>';
        $html .= '</thead>';
        $html .= '<tbody>';
        $i = 1;
        for ($a = 0; $a <= 12; ++$a) {
            if (strlen($item->{$a}->MK) > 20) {
                $textMK = substr($item->{$a}->MK, 0, 20).'...';
            } else {
                $textMK = $item->{$a}->MK;
            }
            $html .= '<tr>'.
                        '<td>'.$i.'</td>'.
                        '<td>'.$item->{$a}->kode.'</td>'.
                        '<td align="left">'.$textMK.'</td>'.
                        '<td>'.$item->{$a}->sks.'</td>'.
                        '<td> </td>'.
                        '<td> </td>';

            $html .= '</tr>';
            ++$i;
        }
        $html .= '</tbody></table>';

        $pdf->writeHTML($html, true, false, false, false, 'C');

        $pdf->SetXY(98, 158);
        $pdf->cell(40, 0, 'Jumlah:');
        $pdf->SetXY(153, 160);
        $pdf->cell(50, 0, 'Cirebon, '.$tanggalKRS->format('d F Y'), 0, 0, 'R');
        $pdf->SetXY(119, 158);
        $pdf->cell(40, 0, $item->ttl_sks);

        $pdf->Line(114, 145, 114, 164, ['width' => 0.5, 'cap' => 'square', 'join' => 'mitter', 'dash' => 0]);
        $pdf->Line(129.3, 145, 129.3, 164, ['width' => 0.5, 'cap' => 'square', 'join' => 'mitter', 'dash' => 0]);
        $pdf->Line(114, 164, 129.3, 164, ['width' => 0.5, 'cap' => 'square', 'join' => 'mitter', 'dash' => 0]);

        $pdf->SetXY(10, 169);
        $pdf->cell(80, 0, 'PKRS Diisi hanya bila ada Perubahan Rencana Studi');
        $pdf->SetXY(162, 169);
        $pdf->cell(10, 0, 'K R S');
        $pdf->SetXY(185, 169);
        $pdf->cell(10, 0, 'PKRS');
        $pdf->SetXY(10, 175);
        $pdf->cell(140, 0, 'Perubahan KRS (PKRS)', 1, 1, 'C');

        $tablePKRS = '<table border="1" cellspacing="1" cellpadding="2" width="141mm">'.
                        '<thead>'.
                            '<tr>'.
                                '<th colspan="2" width="20mm" align="center">Status<br>Perubahan</th>'.
                                '<th colspan="3" width="95mm" align="center">Mata Kuliah</th>'.
                                '<th rowspan="2" width="23mm" align="center">Sandi<br>Dosen</th>'.
                            '</tr>'.
                            '<tr>'.
                                '<th width="10mm" align="center">No</th>'.
                                '<th width="10mm" align="center">G/T/P</th>'.
                                '<th width="31mm" align="center">Kode MK</th>'.
                                '<th width="50mm" align="center">Nama MK</th>'.
                                '<th width="14mm" align="center">Kredit</th>'.
                            '</tr></thead>';
        $tablePKRS .= '<tbody>';
        for ($i = 1; $i <= 3; ++$i) {
            $tablePKRS .= '<tr>';
            $tablePKRS .= '<td width="10mm" align="center">'.$i.'</td>';
            $tablePKRS .= '<td width="10mm" align="center"> </td>';
            $tablePKRS .= '<td width="31mm" align="center"> </td>';
            $tablePKRS .= '<td width="50mm" align="center"> </td>';
            $tablePKRS .= '<td width="14mm" align="center"> </td>';
            $tablePKRS .= '<td width="23mm" align="center"> </td>';
            $tablePKRS .= '</tr>';
        }

        $pdf->SetXY(10, 181);
        $tablePKRS .= '</tbody></table>';
        $pdf->writeHTML($tablePKRS, true, false, false, false, 'C');

        $pdf->SetXY(10.1, 219);
        $pdf->cell(102.2, 0, 'Jumlah', 1, 1, 'R');
        $pdf->Line(126.5, 219, 126.5, 224, ['width' => 0.5, 'cap' => 'square', 'join' => 'mitter', 'dash' => 0]);
        $pdf->Line(112.2, 224, 126.5, 224, ['width' => 0.5, 'cap' => 'square', 'join' => 'mitter', 'dash' => 0]);

        $pdf->SetXY(10, 226);
        $pdf->cell(30, 0, 'G : Ganti Mata Kuliah');
        $pdf->SetXY(100, 226);
        $pdf->cell(30, 0, 'Cirebon, ____________________');
        $pdf->SetXY(10, 230);
        $pdf->cell(30, 0, 'T : Tambah Mata Kuliah');
        $pdf->SetXY(10, 234);
        $pdf->cell(30, 0, 'P : Pengurangan Mata Kuliah');

        $pdf->SetXY(60, 240);
        $pdf->cell(30, 0, 'Total Kredit Akhir : ');
        $pdf->Line(95, 237.5, 95, 247, ['width' => 0.5, 'cap' => 'square', 'join' => 'mitter', 'dash' => 0]);
        $pdf->Line(110, 237.5, 110, 247, ['width' => 0.5, 'cap' => 'square', 'join' => 'mitter', 'dash' => 0]);
        $pdf->Line(95, 237.5, 110, 237.5, ['width' => 0.5, 'cap' => 'square', 'join' => 'mitter', 'dash' => 0]);
        $pdf->Line(110, 247, 95, 247, ['width' => 0.5, 'cap' => 'square', 'join' => 'mitter', 'dash' => 0]);

        // ttd KRS
        $pdf->Line(156, 175, 181, 175, ['width' => 0.5, 'cap' => 'square', 'join' => 'mitter', 'dash' => 0]);
        $pdf->Line(156, 250, 181, 250, ['width' => 0.5, 'cap' => 'square', 'join' => 'mitter', 'dash' => 0]);
        $pdf->Line(156, 175, 156, 250, ['width' => 0.5, 'cap' => 'square', 'join' => 'mitter', 'dash' => 0]);
        $pdf->Line(181, 175, 181, 250, ['width' => 0.5, 'cap' => 'square', 'join' => 'mitter', 'dash' => 0]);
        $pdf->Line(156, 200, 181, 200, ['width' => 0.5, 'cap' => 'square', 'join' => 'mitter', 'dash' => 0]);
        $pdf->Line(156, 225, 181, 225, ['width' => 0.5, 'cap' => 'square', 'join' => 'mitter', 'dash' => 0]);

        // ttd PKRS
        $pdf->Line(182, 175, 201, 175, ['width' => 0.5, 'cap' => 'square', 'join' => 'mitter', 'dash' => 0]);
        $pdf->Line(182, 250, 201, 250, ['width' => 0.5, 'cap' => 'square', 'join' => 'mitter', 'dash' => 0]);
        $pdf->Line(182, 175, 182, 250, ['width' => 0.5, 'cap' => 'square', 'join' => 'mitter', 'dash' => 0]);
        $pdf->Line(201, 175, 201, 250, ['width' => 0.5, 'cap' => 'square', 'join' => 'mitter', 'dash' => 0]);

        $pdf->Line(182, 200, 201, 200, ['width' => 0.5, 'cap' => 'square', 'join' => 'mitter', 'dash' => 0]);
        $pdf->Line(182, 225, 201, 225, ['width' => 0.5, 'cap' => 'square', 'join' => 'mitter', 'dash' => 0]);

        $pdf->SetXY(156, 175);
        $pdf->cell(25, 0, 'Tanda tangan', 1, 0, 'C');
        $pdf->SetXY(182, 175);
        $pdf->cell(19, 0, 'Paraf', 1, 0, 'C');

        $pdf->SetXY(156, 195);
        $pdf->cell(25, 0, 'Mahasiswa', 0, 0, 'C');
        $pdf->SetXY(182, 195);
        $pdf->cell(19, 0, 'Mahasiswa', 0, 0, 'C');

        $pdf->SetXY(156, 200);
        $pdf->cell(25, 0, 'Menyetujui', 0, 0, 'C');
        $pdf->SetXY(182, 200);
        $pdf->cell(19, 0, 'Menyetujui', 0, 0, 'C');

        $pdf->SetXY(156, 220);
        $pdf->cell(25, 0, 'Dosen Wali', 0, 0, 'C');
        $pdf->SetXY(182, 220);
        $pdf->cell(19, 0, 'Dosen Wali', 0, 0, 'C');

        $pdf->SetXY(156, 245);
        $pdf->cell(25, 0, 'BAAK', 0, 0, 'C');
        $pdf->SetXY(182, 245);
        $pdf->cell(19, 0, 'BAAK', 0, 0, 'C');

        $pdf->Line(156, 200, 181, 200, ['width' => 0.5, 'cap' => 'square', 'join' => 'mitter', 'dash' => 0]);
        $pdf->Line(156, 225, 181, 225, ['width' => 0.5, 'cap' => 'square', 'join' => 'mitter', 'dash' => 0]);

        // Page 3 BAUK

        // set font
        $pdf->SetFont('helvetica', '', 10);

        // add a page
        $pdf->AddPage();

        // create Header
        $logo = JPATH_BASE.'/media/com_siak/images/untag-transparent.jpg';
        $pdf->Image($logo, 10, 10, 20, 20);
        $pdf->SetXY(40, 13);
        $pdf->Cell(100, 0, strtoupper($params['universitas']), 0, 1, 'L', 0, '', 0);
        $pdf->SetXY(40, 18);
        $pdf->Cell(100, 0, strtoupper($params['fakultas']), 0, 1, 'L', 0, '', 0);
        $pdf->Line(40, 25, 115, 25, ['width' => 0.5, 'cap' => 'square', 'join' => 'mitter', 'dash' => 0]);

        $pdf->SetXY(71, 35);
        $pdf->SetFont('helvetica', 'B', '14');
        $pdf->Cell(76, 0, 'KARTU RENCANA STUDI', 0, 1, 'C', 0, '', 0);
        $pdf->SetXY(60, 40);
        $pdf->Cell(95, 0, '( K R S )', 0, 1, 'C', 0, '', 0);

        $pdf->Line(75, 33, 143, 33, ['width' => 0.8, 'cap' => 'square', 'join' => 'mitter', 'dash' => 0]);
        $pdf->Line(75, 47, 143, 47, ['width' => 0.8, 'cap' => 'square', 'join' => 'mitter', 'dash' => 0]);
        $pdf->Line(75, 47, 75, 33, ['width' => 0.8, 'cap' => 'square', 'join' => 'mitter', 'dash' => 0]);
        $pdf->Line(143, 47, 143, 33, ['width' => 0.8, 'cap' => 'square', 'join' => 'mitter', 'dash' => 0]);

        // ISI KRS
        $pdf->setFont('helvetica', '', '10');
        //$border_style = ['all' => ['width' => 2, 'cap' => 'square', 'join' => 'miter', 'dash' => 0, 'phase' => 0]];
        $pdf->SetDrawColor(0, 255, 0);
        $pdf->SetFillColor(0, 255, 0);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->Rect(10, 40, 30, 6, 'DF', );
        $pdf->Text(17, 41, 'B A U K');
        $pdf->SetDrawColor(0, 0, 0);
        $pdf->SetFillColor(0, 0, 0);
        $pdf->SetTextColor(0, 0, 0);

        $pdf->setFont('helvetica', '', '10');

        $pdf->SetXY(10, 52);
        $pdf->cell(30, 0, 'Nama Mahasiswa');
        $pdf->cell(32, 0, ': '.$item->mahasiswa);
        $pdf->SetXY(120, 52);
        $pdf->cell(30, 0, 'Semester');
        $pdf->cell(32, 0, ': '.$item->semester);

        $pdf->SetXY(10, 57);
        $pdf->cell(30, 0, 'N P M');
        $pdf->cell(32, 0, ': '.$item->npm);
        $pdf->SetXY(120, 57);
        $pdf->cell(30, 0, 'Prog. Studi');
        $pdf->cell(32, 0, ': '.$item->nama_prodi_alias);

        $pdf->SetXY(10, 62);
        $pdf->cell(30, 0, 'Alamat');
        $pdf->cell(32, 0, ': '.$item->alamat_1);
        $pdf->SetXY(120, 62);
        $pdf->cell(30, 0, 'Nama Dosen Wali');
        $pdf->cell(32, 0, ': '.$item->dosenwali);

        $pdf->SetXY(10, 70);

        $html = '<table border="1" cellspacing="1" cellpadding="2">';
        $html .= '</thead>';
        $html .= '<tr>';
        $html .= '<th rowspan = "2" width="11mm" align="center">No Urut</th>';
        $html .= '<th colspan = "3" width="107mm" align="center">Mata Kuliah</th>';
        // $html .= '<td align="center">Kode MK</td>';
        //$html .= '<td align="center">Nama MK</td>';
        //$html .= '<td>Kredit</td>';
        $html .= '<th rowspan="2" align="center;" width="22mm">Sandi<br>Dosen</th>';
        $html .= '<th rowspan="2" style="display:table-cell; text-align: center; vertical-align: middle;" width="50mm">Hari,<br>Jam Kuliah</th>';
        $html .= '</tr>';
        $html .= '<tr><th width="31mm">Kode MK</th><th width="61mm">Nama MK</th><th width="15mm">Kredit</th></tr>';
        $html .= '</thead>';
        $html .= '<tbody>';
        $i = 1;
        for ($a = 0; $a <= 12; ++$a) {
            if (strlen($item->{$a}->MK) > 20) {
                $textMK = substr($item->{$a}->MK, 0, 20).'...';
            } else {
                $textMK = $item->{$a}->MK;
            }
            $html .= '<tr>'.
                        '<td>'.$i.'</td>'.
                        '<td>'.$item->{$a}->kode.'</td>'.
                        '<td align="left">'.$textMK.'</td>'.
                        '<td>'.$item->{$a}->sks.'</td>'.
                        '<td> </td>'.
                        '<td> </td>';

            $html .= '</tr>';
            ++$i;
        }
        $html .= '</tbody></table>';

        $pdf->writeHTML($html, true, false, false, false, 'C');

        $pdf->SetXY(98, 158);
        $pdf->cell(40, 0, 'Jumlah:');
        $pdf->SetXY(153, 160);
        $pdf->cell(50, 0, 'Cirebon, '.$tanggalKRS->format('d F Y'), 0, 0, 'R');
        $pdf->SetXY(119, 158);
        $pdf->cell(40, 0, $item->ttl_sks);

        $pdf->Line(114, 145, 114, 164, ['width' => 0.5, 'cap' => 'square', 'join' => 'mitter', 'dash' => 0]);
        $pdf->Line(129.3, 145, 129.3, 164, ['width' => 0.5, 'cap' => 'square', 'join' => 'mitter', 'dash' => 0]);
        $pdf->Line(114, 164, 129.3, 164, ['width' => 0.5, 'cap' => 'square', 'join' => 'mitter', 'dash' => 0]);

        $pdf->SetXY(10, 169);
        $pdf->cell(80, 0, 'PKRS Diisi hanya bila ada Perubahan Rencana Studi');
        $pdf->SetXY(162, 169);
        $pdf->cell(10, 0, 'K R S');
        $pdf->SetXY(185, 169);
        $pdf->cell(10, 0, 'PKRS');
        $pdf->SetXY(10, 175);
        $pdf->cell(140, 0, 'Perubahan KRS (PKRS)', 1, 1, 'C');

        $tablePKRS = '<table border="1" cellspacing="1" cellpadding="2" width="141mm">'.
                        '<thead>'.
                            '<tr>'.
                                '<th colspan="2" width="20mm" align="center">Status<br>Perubahan</th>'.
                                '<th colspan="3" width="95mm" align="center">Mata Kuliah</th>'.
                                '<th rowspan="2" width="23mm" align="center">Sandi<br>Dosen</th>'.
                            '</tr>'.
                            '<tr>'.
                                '<th width="10mm" align="center">No</th>'.
                                '<th width="10mm" align="center">G/T/P</th>'.
                                '<th width="31mm" align="center">Kode MK</th>'.
                                '<th width="50mm" align="center">Nama MK</th>'.
                                '<th width="14mm" align="center">Kredit</th>'.
                            '</tr></thead>';
        $tablePKRS .= '<tbody>';
        for ($i = 1; $i <= 3; ++$i) {
            $tablePKRS .= '<tr>';
            $tablePKRS .= '<td width="10mm" align="center">'.$i.'</td>';
            $tablePKRS .= '<td width="10mm" align="center"> </td>';
            $tablePKRS .= '<td width="31mm" align="center"> </td>';
            $tablePKRS .= '<td width="50mm" align="center"> </td>';
            $tablePKRS .= '<td width="14mm" align="center"> </td>';
            $tablePKRS .= '<td width="23mm" align="center"> </td>';
            $tablePKRS .= '</tr>';
        }

        $pdf->SetXY(10, 181);
        $tablePKRS .= '</tbody></table>';
        $pdf->writeHTML($tablePKRS, true, false, false, false, 'C');

        $pdf->SetXY(10.1, 219);
        $pdf->cell(102.2, 0, 'Jumlah', 1, 1, 'R');
        $pdf->Line(126.5, 219, 126.5, 224, ['width' => 0.5, 'cap' => 'square', 'join' => 'mitter', 'dash' => 0]);
        $pdf->Line(112.2, 224, 126.5, 224, ['width' => 0.5, 'cap' => 'square', 'join' => 'mitter', 'dash' => 0]);

        $pdf->SetXY(10, 226);
        $pdf->cell(30, 0, 'G : Ganti Mata Kuliah');
        $pdf->SetXY(100, 226);
        $pdf->cell(30, 0, 'Cirebon, ____________________');
        $pdf->SetXY(10, 230);
        $pdf->cell(30, 0, 'T : Tambah Mata Kuliah');
        $pdf->SetXY(10, 234);
        $pdf->cell(30, 0, 'P : Pengurangan Mata Kuliah');

        $pdf->SetXY(60, 240);
        $pdf->cell(30, 0, 'Total Kredit Akhir : ');
        $pdf->Line(95, 237.5, 95, 247, ['width' => 0.5, 'cap' => 'square', 'join' => 'mitter', 'dash' => 0]);
        $pdf->Line(110, 237.5, 110, 247, ['width' => 0.5, 'cap' => 'square', 'join' => 'mitter', 'dash' => 0]);
        $pdf->Line(95, 237.5, 110, 237.5, ['width' => 0.5, 'cap' => 'square', 'join' => 'mitter', 'dash' => 0]);
        $pdf->Line(110, 247, 95, 247, ['width' => 0.5, 'cap' => 'square', 'join' => 'mitter', 'dash' => 0]);

        // ttd KRS
        $pdf->Line(156, 175, 181, 175, ['width' => 0.5, 'cap' => 'square', 'join' => 'mitter', 'dash' => 0]);
        $pdf->Line(156, 250, 181, 250, ['width' => 0.5, 'cap' => 'square', 'join' => 'mitter', 'dash' => 0]);
        $pdf->Line(156, 175, 156, 250, ['width' => 0.5, 'cap' => 'square', 'join' => 'mitter', 'dash' => 0]);
        $pdf->Line(181, 175, 181, 250, ['width' => 0.5, 'cap' => 'square', 'join' => 'mitter', 'dash' => 0]);
        $pdf->Line(156, 200, 181, 200, ['width' => 0.5, 'cap' => 'square', 'join' => 'mitter', 'dash' => 0]);
        $pdf->Line(156, 225, 181, 225, ['width' => 0.5, 'cap' => 'square', 'join' => 'mitter', 'dash' => 0]);

        // ttd PKRS
        $pdf->Line(182, 175, 201, 175, ['width' => 0.5, 'cap' => 'square', 'join' => 'mitter', 'dash' => 0]);
        $pdf->Line(182, 250, 201, 250, ['width' => 0.5, 'cap' => 'square', 'join' => 'mitter', 'dash' => 0]);
        $pdf->Line(182, 175, 182, 250, ['width' => 0.5, 'cap' => 'square', 'join' => 'mitter', 'dash' => 0]);
        $pdf->Line(201, 175, 201, 250, ['width' => 0.5, 'cap' => 'square', 'join' => 'mitter', 'dash' => 0]);

        $pdf->Line(182, 200, 201, 200, ['width' => 0.5, 'cap' => 'square', 'join' => 'mitter', 'dash' => 0]);
        $pdf->Line(182, 225, 201, 225, ['width' => 0.5, 'cap' => 'square', 'join' => 'mitter', 'dash' => 0]);

        $pdf->SetXY(156, 175);
        $pdf->cell(25, 0, 'Tanda tangan', 1, 0, 'C');
        $pdf->SetXY(182, 175);
        $pdf->cell(19, 0, 'Paraf', 1, 0, 'C');

        $pdf->SetXY(156, 195);
        $pdf->cell(25, 0, 'Mahasiswa', 0, 0, 'C');
        $pdf->SetXY(182, 195);
        $pdf->cell(19, 0, 'Mahasiswa', 0, 0, 'C');

        $pdf->SetXY(156, 200);
        $pdf->cell(25, 0, 'Menyetujui', 0, 0, 'C');
        $pdf->SetXY(182, 200);
        $pdf->cell(19, 0, 'Menyetujui', 0, 0, 'C');

        $pdf->SetXY(156, 220);
        $pdf->cell(25, 0, 'Dosen Wali', 0, 0, 'C');
        $pdf->SetXY(182, 220);
        $pdf->cell(19, 0, 'Dosen Wali', 0, 0, 'C');

        $pdf->SetXY(156, 245);
        $pdf->cell(25, 0, 'BAAK', 0, 0, 'C');
        $pdf->SetXY(182, 245);
        $pdf->cell(19, 0, 'BAAK', 0, 0, 'C');

        $pdf->Line(156, 200, 181, 200, ['width' => 0.5, 'cap' => 'square', 'join' => 'mitter', 'dash' => 0]);
        $pdf->Line(156, 225, 181, 225, ['width' => 0.5, 'cap' => 'square', 'join' => 'mitter', 'dash' => 0]);

        // Page 4 Fakultas

        // set font
        $pdf->SetFont('helvetica', '', 10);

        // add a page
        $pdf->AddPage();

        // create Header
        $logo = JPATH_BASE.'/media/com_siak/images/untag-transparent.jpg';
        $pdf->Image($logo, 10, 10, 20, 20);
        $pdf->SetXY(40, 13);
        $pdf->Cell(100, 0, strtoupper($params['universitas']), 0, 1, 'L', 0, '', 0);
        $pdf->SetXY(40, 18);
        $pdf->Cell(100, 0, strtoupper($params['fakultas']), 0, 1, 'L', 0, '', 0);
        $pdf->Line(40, 25, 115, 25, ['width' => 0.5, 'cap' => 'square', 'join' => 'mitter', 'dash' => 0]);

        $pdf->SetXY(71, 35);
        $pdf->SetFont('helvetica', 'B', '14');
        $pdf->Cell(76, 0, 'KARTU RENCANA STUDI', 0, 1, 'C', 0, '', 0);
        $pdf->SetXY(60, 40);
        $pdf->Cell(95, 0, '( K R S )', 0, 1, 'C', 0, '', 0);

        $pdf->Line(75, 33, 143, 33, ['width' => 0.8, 'cap' => 'square', 'join' => 'mitter', 'dash' => 0]);
        $pdf->Line(75, 47, 143, 47, ['width' => 0.8, 'cap' => 'square', 'join' => 'mitter', 'dash' => 0]);
        $pdf->Line(75, 47, 75, 33, ['width' => 0.8, 'cap' => 'square', 'join' => 'mitter', 'dash' => 0]);
        $pdf->Line(143, 47, 143, 33, ['width' => 0.8, 'cap' => 'square', 'join' => 'mitter', 'dash' => 0]);

        // ISI KRS
        $pdf->setFont('helvetica', '', '10');
        //$border_style = ['all' => ['width' => 2, 'cap' => 'square', 'join' => 'miter', 'dash' => 0, 'phase' => 0]];
        $pdf->SetDrawColor(255, 255, 0);
        $pdf->SetFillColor(255, 255, 0);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->Rect(10, 40, 30, 6, 'DF', );
        $pdf->Text(12, 41, 'FAKULTAS');
        $pdf->SetDrawColor(0, 0, 0);
        $pdf->SetFillColor(0, 0, 0);
        //$pdf->SetTextColor(0, 0, 0);

        $pdf->setFont('helvetica', '', '10');

        $pdf->SetXY(10, 52);
        $pdf->cell(30, 0, 'Nama Mahasiswa');
        $pdf->cell(32, 0, ': '.$item->mahasiswa);
        $pdf->SetXY(120, 52);
        $pdf->cell(30, 0, 'Semester');
        $pdf->cell(32, 0, ': '.$item->semester);

        $pdf->SetXY(10, 57);
        $pdf->cell(30, 0, 'N P M');
        $pdf->cell(32, 0, ': '.$item->npm);
        $pdf->SetXY(120, 57);
        $pdf->cell(30, 0, 'Prog. Studi');
        $pdf->cell(32, 0, ': '.$item->nama_prodi_alias);

        $pdf->SetXY(10, 62);
        $pdf->cell(30, 0, 'Alamat');
        $pdf->cell(32, 0, ': '.$item->alamat_1);
        $pdf->SetXY(120, 62);
        $pdf->cell(30, 0, 'Nama Dosen Wali');
        $pdf->cell(32, 0, ': '.$item->dosenwali);

        $pdf->SetXY(10, 70);

        $html = '<table border="1" cellspacing="1" cellpadding="2">';
        $html .= '</thead>';
        $html .= '<tr>';
        $html .= '<th rowspan = "2" width="11mm" align="center">No Urut</th>';
        $html .= '<th colspan = "3" width="107mm" align="center">Mata Kuliah</th>';
        // $html .= '<td align="center">Kode MK</td>';
        //$html .= '<td align="center">Nama MK</td>';
        //$html .= '<td>Kredit</td>';
        $html .= '<th rowspan="2" align="center;" width="22mm">Sandi<br>Dosen</th>';
        $html .= '<th rowspan="2" style="display:table-cell; text-align: center; vertical-align: middle;" width="50mm">Hari,<br>Jam Kuliah</th>';
        $html .= '</tr>';
        $html .= '<tr><th width="31mm">Kode MK</th><th width="61mm">Nama MK</th><th width="15mm">Kredit</th></tr>';
        $html .= '</thead>';
        $html .= '<tbody>';
        $i = 1;
        for ($a = 0; $a <= 12; ++$a) {
            if (strlen($item->{$a}->MK) > 20) {
                $textMK = substr($item->{$a}->MK, 0, 20).'...';
            } else {
                $textMK = $item->{$a}->MK;
            }
            $html .= '<tr>'.
                        '<td>'.$i.'</td>'.
                        '<td>'.$item->{$a}->kode.'</td>'.
                        '<td align="left">'.$textMK.'</td>'.
                        '<td>'.$item->{$a}->sks.'</td>'.
                        '<td> </td>'.
                        '<td> </td>';

            $html .= '</tr>';
            ++$i;
        }
        $html .= '</tbody></table>';

        $pdf->writeHTML($html, true, false, false, false, 'C');

        $pdf->SetXY(98, 158);
        $pdf->cell(40, 0, 'Jumlah:');
        $pdf->SetXY(153, 160);
        $pdf->cell(50, 0, 'Cirebon, '.$tanggalKRS->format('d F Y'), 0, 0, 'R');
        $pdf->SetXY(119, 158);
        $pdf->cell(40, 0, $item->ttl_sks);

        $pdf->Line(114, 145, 114, 164, ['width' => 0.5, 'cap' => 'square', 'join' => 'mitter', 'dash' => 0]);
        $pdf->Line(129.3, 145, 129.3, 164, ['width' => 0.5, 'cap' => 'square', 'join' => 'mitter', 'dash' => 0]);
        $pdf->Line(114, 164, 129.3, 164, ['width' => 0.5, 'cap' => 'square', 'join' => 'mitter', 'dash' => 0]);

        $pdf->SetXY(10, 169);
        $pdf->cell(80, 0, 'PKRS Diisi hanya bila ada Perubahan Rencana Studi');
        $pdf->SetXY(162, 169);
        $pdf->cell(10, 0, 'K R S');
        $pdf->SetXY(185, 169);
        $pdf->cell(10, 0, 'PKRS');
        $pdf->SetXY(10, 175);
        $pdf->cell(140, 0, 'Perubahan KRS (PKRS)', 1, 1, 'C');

        $tablePKRS = '<table border="1" cellspacing="1" cellpadding="2" width="141mm">'.
                        '<thead>'.
                            '<tr>'.
                                '<th colspan="2" width="20mm" align="center">Status<br>Perubahan</th>'.
                                '<th colspan="3" width="95mm" align="center">Mata Kuliah</th>'.
                                '<th rowspan="2" width="23mm" align="center">Sandi<br>Dosen</th>'.
                            '</tr>'.
                            '<tr>'.
                                '<th width="10mm" align="center">No</th>'.
                                '<th width="10mm" align="center">G/T/P</th>'.
                                '<th width="31mm" align="center">Kode MK</th>'.
                                '<th width="50mm" align="center">Nama MK</th>'.
                                '<th width="14mm" align="center">Kredit</th>'.
                            '</tr></thead>';
        $tablePKRS .= '<tbody>';
        for ($i = 1; $i <= 3; ++$i) {
            $tablePKRS .= '<tr>';
            $tablePKRS .= '<td width="10mm" align="center">'.$i.'</td>';
            $tablePKRS .= '<td width="10mm" align="center"> </td>';
            $tablePKRS .= '<td width="31mm" align="center"> </td>';
            $tablePKRS .= '<td width="50mm" align="center"> </td>';
            $tablePKRS .= '<td width="14mm" align="center"> </td>';
            $tablePKRS .= '<td width="23mm" align="center"> </td>';
            $tablePKRS .= '</tr>';
        }

        $pdf->SetXY(10, 181);
        $tablePKRS .= '</tbody></table>';
        $pdf->writeHTML($tablePKRS, true, false, false, false, 'C');

        $pdf->SetXY(10.1, 219);
        $pdf->cell(102.2, 0, 'Jumlah', 1, 1, 'R');
        $pdf->Line(126.5, 219, 126.5, 224, ['width' => 0.5, 'cap' => 'square', 'join' => 'mitter', 'dash' => 0]);
        $pdf->Line(112.2, 224, 126.5, 224, ['width' => 0.5, 'cap' => 'square', 'join' => 'mitter', 'dash' => 0]);

        $pdf->SetXY(10, 226);
        $pdf->cell(30, 0, 'G : Ganti Mata Kuliah');
        $pdf->SetXY(100, 226);
        $pdf->cell(30, 0, 'Cirebon, ____________________');
        $pdf->SetXY(10, 230);
        $pdf->cell(30, 0, 'T : Tambah Mata Kuliah');
        $pdf->SetXY(10, 234);
        $pdf->cell(30, 0, 'P : Pengurangan Mata Kuliah');

        $pdf->SetXY(60, 240);
        $pdf->cell(30, 0, 'Total Kredit Akhir : ');
        $pdf->Line(95, 237.5, 95, 247, ['width' => 0.5, 'cap' => 'square', 'join' => 'mitter', 'dash' => 0]);
        $pdf->Line(110, 237.5, 110, 247, ['width' => 0.5, 'cap' => 'square', 'join' => 'mitter', 'dash' => 0]);
        $pdf->Line(95, 237.5, 110, 237.5, ['width' => 0.5, 'cap' => 'square', 'join' => 'mitter', 'dash' => 0]);
        $pdf->Line(110, 247, 95, 247, ['width' => 0.5, 'cap' => 'square', 'join' => 'mitter', 'dash' => 0]);

        // ttd KRS
        $pdf->Line(156, 175, 181, 175, ['width' => 0.5, 'cap' => 'square', 'join' => 'mitter', 'dash' => 0]);
        $pdf->Line(156, 250, 181, 250, ['width' => 0.5, 'cap' => 'square', 'join' => 'mitter', 'dash' => 0]);
        $pdf->Line(156, 175, 156, 250, ['width' => 0.5, 'cap' => 'square', 'join' => 'mitter', 'dash' => 0]);
        $pdf->Line(181, 175, 181, 250, ['width' => 0.5, 'cap' => 'square', 'join' => 'mitter', 'dash' => 0]);
        $pdf->Line(156, 200, 181, 200, ['width' => 0.5, 'cap' => 'square', 'join' => 'mitter', 'dash' => 0]);
        $pdf->Line(156, 225, 181, 225, ['width' => 0.5, 'cap' => 'square', 'join' => 'mitter', 'dash' => 0]);

        // ttd PKRS
        $pdf->Line(182, 175, 201, 175, ['width' => 0.5, 'cap' => 'square', 'join' => 'mitter', 'dash' => 0]);
        $pdf->Line(182, 250, 201, 250, ['width' => 0.5, 'cap' => 'square', 'join' => 'mitter', 'dash' => 0]);
        $pdf->Line(182, 175, 182, 250, ['width' => 0.5, 'cap' => 'square', 'join' => 'mitter', 'dash' => 0]);
        $pdf->Line(201, 175, 201, 250, ['width' => 0.5, 'cap' => 'square', 'join' => 'mitter', 'dash' => 0]);

        $pdf->Line(182, 200, 201, 200, ['width' => 0.5, 'cap' => 'square', 'join' => 'mitter', 'dash' => 0]);
        $pdf->Line(182, 225, 201, 225, ['width' => 0.5, 'cap' => 'square', 'join' => 'mitter', 'dash' => 0]);

        $pdf->SetXY(156, 175);
        $pdf->cell(25, 0, 'Tanda tangan', 1, 0, 'C');
        $pdf->SetXY(182, 175);
        $pdf->cell(19, 0, 'Paraf', 1, 0, 'C');

        $pdf->SetXY(156, 195);
        $pdf->cell(25, 0, 'Mahasiswa', 0, 0, 'C');
        $pdf->SetXY(182, 195);
        $pdf->cell(19, 0, 'Mahasiswa', 0, 0, 'C');

        $pdf->SetXY(156, 200);
        $pdf->cell(25, 0, 'Menyetujui', 0, 0, 'C');
        $pdf->SetXY(182, 200);
        $pdf->cell(19, 0, 'Menyetujui', 0, 0, 'C');

        $pdf->SetXY(156, 220);
        $pdf->cell(25, 0, 'Dosen Wali', 0, 0, 'C');
        $pdf->SetXY(182, 220);
        $pdf->cell(19, 0, 'Dosen Wali', 0, 0, 'C');

        $pdf->SetXY(156, 245);
        $pdf->cell(25, 0, 'BAAK', 0, 0, 'C');
        $pdf->SetXY(182, 245);
        $pdf->cell(19, 0, 'BAAK', 0, 0, 'C');

        $pdf->Line(156, 200, 181, 200, ['width' => 0.5, 'cap' => 'square', 'join' => 'mitter', 'dash' => 0]);
        $pdf->Line(156, 225, 181, 225, ['width' => 0.5, 'cap' => 'square', 'join' => 'mitter', 'dash' => 0]);

        // Page 5 Dosen Wali

        // set font
        $pdf->SetFont('helvetica', '', 10);

        // add a page
        $pdf->AddPage();

        // create Header
        $logo = JPATH_BASE.'/media/com_siak/images/untag-transparent.jpg';
        $pdf->Image($logo, 10, 10, 20, 20);
        $pdf->SetXY(40, 13);
        $pdf->Cell(100, 0, strtoupper($params['universitas']), 0, 1, 'L', 0, '', 0);
        $pdf->SetXY(40, 18);
        $pdf->Cell(100, 0, strtoupper($params['fakultas']), 0, 1, 'L', 0, '', 0);
        $pdf->Line(40, 25, 115, 25, ['width' => 0.5, 'cap' => 'square', 'join' => 'mitter', 'dash' => 0]);

        $pdf->SetXY(71, 35);
        $pdf->SetFont('helvetica', 'B', '14');
        $pdf->Cell(76, 0, 'KARTU RENCANA STUDI', 0, 1, 'C', 0, '', 0);
        $pdf->SetXY(60, 40);
        $pdf->Cell(95, 0, '( K R S )', 0, 1, 'C', 0, '', 0);

        $pdf->Line(75, 33, 143, 33, ['width' => 0.8, 'cap' => 'square', 'join' => 'mitter', 'dash' => 0]);
        $pdf->Line(75, 47, 143, 47, ['width' => 0.8, 'cap' => 'square', 'join' => 'mitter', 'dash' => 0]);
        $pdf->Line(75, 47, 75, 33, ['width' => 0.8, 'cap' => 'square', 'join' => 'mitter', 'dash' => 0]);
        $pdf->Line(143, 47, 143, 33, ['width' => 0.8, 'cap' => 'square', 'join' => 'mitter', 'dash' => 0]);

        // ISI KRS
        $pdf->setFont('helvetica', '', '10');
        //$border_style = ['all' => ['width' => 2, 'cap' => 'square', 'join' => 'miter', 'dash' => 0, 'phase' => 0]];
        $pdf->SetDrawColor(255, 0, 0);
        $pdf->SetFillColor(255, 0, 0);
        $pdf->SetTextColor(255, 255, 255);
        $pdf->Rect(10, 40, 30, 6, 'DF', );
        $pdf->Text(12, 41, 'DOSEN WALI');
        $pdf->SetDrawColor(0, 0, 0);
        $pdf->SetFillColor(0, 0, 0);
        $pdf->SetTextColor(0, 0, 0);

        $pdf->setFont('helvetica', '', '10');

        $pdf->SetXY(10, 52);
        $pdf->cell(30, 0, 'Nama Mahasiswa');
        $pdf->cell(32, 0, ': '.$item->mahasiswa);
        $pdf->SetXY(120, 52);
        $pdf->cell(30, 0, 'Semester');
        $pdf->cell(32, 0, ': '.$item->semester);

        $pdf->SetXY(10, 57);
        $pdf->cell(30, 0, 'N P M');
        $pdf->cell(32, 0, ': '.$item->npm);
        $pdf->SetXY(120, 57);
        $pdf->cell(30, 0, 'Prog. Studi');
        $pdf->cell(32, 0, ': '.$item->nama_prodi_alias);

        $pdf->SetXY(10, 62);
        $pdf->cell(30, 0, 'Alamat');
        $pdf->cell(32, 0, ': '.$item->alamat_1);
        $pdf->SetXY(120, 62);
        $pdf->cell(30, 0, 'Nama Dosen Wali');
        $pdf->cell(32, 0, ': '.$item->dosenwali);

        $pdf->SetXY(10, 70);

        $html = '<table border="1" cellspacing="1" cellpadding="2">';
        $html .= '</thead>';
        $html .= '<tr>';
        $html .= '<th rowspan = "2" width="11mm" align="center">No Urut</th>';
        $html .= '<th colspan = "3" width="107mm" align="center">Mata Kuliah</th>';
        // $html .= '<td align="center">Kode MK</td>';
        //$html .= '<td align="center">Nama MK</td>';
        //$html .= '<td>Kredit</td>';
        $html .= '<th rowspan="2" align="center;" width="22mm">Sandi<br>Dosen</th>';
        $html .= '<th rowspan="2" style="display:table-cell; text-align: center; vertical-align: middle;" width="50mm">Hari,<br>Jam Kuliah</th>';
        $html .= '</tr>';
        $html .= '<tr><th width="31mm">Kode MK</th><th width="61mm">Nama MK</th><th width="15mm">Kredit</th></tr>';
        $html .= '</thead>';
        $html .= '<tbody>';
        $i = 1;
        for ($a = 0; $a <= 12; ++$a) {
            if (strlen($item->{$a}->MK) > 20) {
                $textMK = substr($item->{$a}->MK, 0, 20).'...';
            } else {
                $textMK = $item->{$a}->MK;
            }
            $html .= '<tr>'.
                        '<td>'.$i.'</td>'.
                        '<td>'.$item->{$a}->kode.'</td>'.
                        '<td align="left">'.$textMK.'</td>'.
                        '<td>'.$item->{$a}->sks.'</td>'.
                        '<td> </td>'.
                        '<td> </td>';

            $html .= '</tr>';
            ++$i;
        }
        $html .= '</tbody></table>';

        $pdf->writeHTML($html, true, false, false, false, 'C');

        $pdf->SetXY(98, 158);
        $pdf->cell(40, 0, 'Jumlah:');
        $pdf->SetXY(153, 160);
        $pdf->cell(50, 0, 'Cirebon, '.$tanggalKRS->format('d F Y'), 0, 0, 'R');
        $pdf->SetXY(119, 158);
        $pdf->cell(40, 0, $item->ttl_sks);

        $pdf->Line(114, 145, 114, 164, ['width' => 0.5, 'cap' => 'square', 'join' => 'mitter', 'dash' => 0]);
        $pdf->Line(129.3, 145, 129.3, 164, ['width' => 0.5, 'cap' => 'square', 'join' => 'mitter', 'dash' => 0]);
        $pdf->Line(114, 164, 129.3, 164, ['width' => 0.5, 'cap' => 'square', 'join' => 'mitter', 'dash' => 0]);

        $pdf->SetXY(10, 169);
        $pdf->cell(80, 0, 'PKRS Diisi hanya bila ada Perubahan Rencana Studi');
        $pdf->SetXY(162, 169);
        $pdf->cell(10, 0, 'K R S');
        $pdf->SetXY(185, 169);
        $pdf->cell(10, 0, 'PKRS');
        $pdf->SetXY(10, 175);
        $pdf->cell(140, 0, 'Perubahan KRS (PKRS)', 1, 1, 'C');

        $tablePKRS = '<table border="1" cellspacing="1" cellpadding="2" width="141mm">'.
                        '<thead>'.
                            '<tr>'.
                                '<th colspan="2" width="20mm" align="center">Status<br>Perubahan</th>'.
                                '<th colspan="3" width="95mm" align="center">Mata Kuliah</th>'.
                                '<th rowspan="2" width="23mm" align="center">Sandi<br>Dosen</th>'.
                            '</tr>'.
                            '<tr>'.
                                '<th width="10mm" align="center">No</th>'.
                                '<th width="10mm" align="center">G/T/P</th>'.
                                '<th width="31mm" align="center">Kode MK</th>'.
                                '<th width="50mm" align="center">Nama MK</th>'.
                                '<th width="14mm" align="center">Kredit</th>'.
                            '</tr></thead>';
        $tablePKRS .= '<tbody>';
        for ($i = 1; $i <= 3; ++$i) {
            $tablePKRS .= '<tr>';
            $tablePKRS .= '<td width="10mm" align="center">'.$i.'</td>';
            $tablePKRS .= '<td width="10mm" align="center"> </td>';
            $tablePKRS .= '<td width="31mm" align="center"> </td>';
            $tablePKRS .= '<td width="50mm" align="center"> </td>';
            $tablePKRS .= '<td width="14mm" align="center"> </td>';
            $tablePKRS .= '<td width="23mm" align="center"> </td>';
            $tablePKRS .= '</tr>';
        }

        $pdf->SetXY(10, 181);
        $tablePKRS .= '</tbody></table>';
        $pdf->writeHTML($tablePKRS, true, false, false, false, 'C');

        $pdf->SetXY(10.1, 219);
        $pdf->cell(102.2, 0, 'Jumlah', 1, 1, 'R');
        $pdf->Line(126.5, 219, 126.5, 224, ['width' => 0.5, 'cap' => 'square', 'join' => 'mitter', 'dash' => 0]);
        $pdf->Line(112.2, 224, 126.5, 224, ['width' => 0.5, 'cap' => 'square', 'join' => 'mitter', 'dash' => 0]);

        $pdf->SetXY(10, 226);
        $pdf->cell(30, 0, 'G : Ganti Mata Kuliah');
        $pdf->SetXY(100, 226);
        $pdf->cell(30, 0, 'Cirebon, ____________________');
        $pdf->SetXY(10, 230);
        $pdf->cell(30, 0, 'T : Tambah Mata Kuliah');
        $pdf->SetXY(10, 234);
        $pdf->cell(30, 0, 'P : Pengurangan Mata Kuliah');

        $pdf->SetXY(60, 240);
        $pdf->cell(30, 0, 'Total Kredit Akhir : ');
        $pdf->Line(95, 237.5, 95, 247, ['width' => 0.5, 'cap' => 'square', 'join' => 'mitter', 'dash' => 0]);
        $pdf->Line(110, 237.5, 110, 247, ['width' => 0.5, 'cap' => 'square', 'join' => 'mitter', 'dash' => 0]);
        $pdf->Line(95, 237.5, 110, 237.5, ['width' => 0.5, 'cap' => 'square', 'join' => 'mitter', 'dash' => 0]);
        $pdf->Line(110, 247, 95, 247, ['width' => 0.5, 'cap' => 'square', 'join' => 'mitter', 'dash' => 0]);

        // ttd KRS
        $pdf->Line(156, 175, 181, 175, ['width' => 0.5, 'cap' => 'square', 'join' => 'mitter', 'dash' => 0]);
        $pdf->Line(156, 250, 181, 250, ['width' => 0.5, 'cap' => 'square', 'join' => 'mitter', 'dash' => 0]);
        $pdf->Line(156, 175, 156, 250, ['width' => 0.5, 'cap' => 'square', 'join' => 'mitter', 'dash' => 0]);
        $pdf->Line(181, 175, 181, 250, ['width' => 0.5, 'cap' => 'square', 'join' => 'mitter', 'dash' => 0]);
        $pdf->Line(156, 200, 181, 200, ['width' => 0.5, 'cap' => 'square', 'join' => 'mitter', 'dash' => 0]);
        $pdf->Line(156, 225, 181, 225, ['width' => 0.5, 'cap' => 'square', 'join' => 'mitter', 'dash' => 0]);

        // ttd PKRS
        $pdf->Line(182, 175, 201, 175, ['width' => 0.5, 'cap' => 'square', 'join' => 'mitter', 'dash' => 0]);
        $pdf->Line(182, 250, 201, 250, ['width' => 0.5, 'cap' => 'square', 'join' => 'mitter', 'dash' => 0]);
        $pdf->Line(182, 175, 182, 250, ['width' => 0.5, 'cap' => 'square', 'join' => 'mitter', 'dash' => 0]);
        $pdf->Line(201, 175, 201, 250, ['width' => 0.5, 'cap' => 'square', 'join' => 'mitter', 'dash' => 0]);

        $pdf->Line(182, 200, 201, 200, ['width' => 0.5, 'cap' => 'square', 'join' => 'mitter', 'dash' => 0]);
        $pdf->Line(182, 225, 201, 225, ['width' => 0.5, 'cap' => 'square', 'join' => 'mitter', 'dash' => 0]);

        $pdf->SetXY(156, 175);
        $pdf->cell(25, 0, 'Tanda tangan', 1, 0, 'C');
        $pdf->SetXY(182, 175);
        $pdf->cell(19, 0, 'Paraf', 1, 0, 'C');

        $pdf->SetXY(156, 195);
        $pdf->cell(25, 0, 'Mahasiswa', 0, 0, 'C');
        $pdf->SetXY(182, 195);
        $pdf->cell(19, 0, 'Mahasiswa', 0, 0, 'C');

        $pdf->SetXY(156, 200);
        $pdf->cell(25, 0, 'Menyetujui', 0, 0, 'C');
        $pdf->SetXY(182, 200);
        $pdf->cell(19, 0, 'Menyetujui', 0, 0, 'C');

        $pdf->SetXY(156, 220);
        $pdf->cell(25, 0, 'Dosen Wali', 0, 0, 'C');
        $pdf->SetXY(182, 220);
        $pdf->cell(19, 0, 'Dosen Wali', 0, 0, 'C');

        $pdf->SetXY(156, 245);
        $pdf->cell(25, 0, 'BAAK', 0, 0, 'C');
        $pdf->SetXY(182, 245);
        $pdf->cell(19, 0, 'BAAK', 0, 0, 'C');

        $pdf->Line(156, 200, 181, 200, ['width' => 0.5, 'cap' => 'square', 'join' => 'mitter', 'dash' => 0]);
        $pdf->Line(156, 225, 181, 225, ['width' => 0.5, 'cap' => 'square', 'join' => 'mitter', 'dash' => 0]);

        $pdf->Output($namaFilePDF, 'D');
    }
}
