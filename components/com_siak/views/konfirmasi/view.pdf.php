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
defined('_JEXEC') or exit;
jimport('tcpdf.tcpdf');

class SiakViewKonfirmasi extends JViewLegacy
{
    protected $item;

    public function display($tpl = null)
    {
        $this->item = $this->get('Data');
        $params = JComponentHelper::getParams('com_siak');

        if (count($error = $this->get('Errors'))) {
            throw new Exception(implode("\n", $error), 500);

            return false;
        }

        // Buat PDF file : Orientasi Potret, Unit mm, Kertas A4 enc UTF8
        // create new PDF document
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        // set document information
        $pdf->SetCreator('SIAK '.$this->state->params['universitas']);
        $pdf->SetAuthor($this->state->params['fakultas']);
        $pdf->SetTitle('Formulir Her Registrasi');
        $pdf->SetSubject('Herregistrasi');

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
        $pdf->SetFont('times', 'B', 11);

        // add a page
        $pdf->AddPage();

        // create Header
        $logo = JPATH_BASE.'/media/com_siak/images/untag-transparent.jpg';
        $pdf->Image($logo, 22, 18, 23, 23);
        $pdf->SetXY(50, 20);
        $pdf->Cell(100, 0, strtoupper($params->get('universitas')), 0, 1, 'C', 0, '', 0);
        $pdf->SetXY(50, 25);
        $pdf->Cell(100, 0, strtoupper($params->get('kota')), 0, 1, 'C', 0, '', 0);
        $pdf->SetXY(50, 30);
        $pdf->SetFont('times', '', '10');
        $pdf->Cell(100, 0, $params->get('akreditasi'), 0, 1, 'C', 0, '', 0);
        $pdf->SetXY(50, 35);
        $pdf->Cell(100, 0, $params->get('alamat'), 0, 1, 'C', 0, '', 4);
        $pdf->Line(50, 40, 150, 40, ['width' => 0.4, 'cap' => 'square', 'join' => 'mitter', 'dash' => 0]);

        $pdf->SetXY(60, 45);
        $pdf->SetFont('times', 'B', '16');
        $pdf->Cell(95, 0, 'FORMULIR', 0, 1, 'C', 0, '', 0);
        $pdf->SetXY(60, 52);
        $pdf->Cell(95, 0, 'DAFTAR ULANG (HER REGISTRASI)', 0, 1, 'C', 0, '', 4);
        $pdf->Line(60, 59, 155, 59, ['width' => 0.8, 'cap' => 'square', 'join' => 'mitter', 'dash' => 0]);
        // ---------------------------------------------------------

        // Isinya
        $pdf->SetFont('times', '', '10');
        $pdf->SetXY(25, 70);
        $pdf->Cell(0, 0, 'Yang bertanda tangan dibawah ini :');

        $pdf->SetXY(25, 80);
        $pdf->Cell(50, 0, '1. Nama ');
        $pdf->Cell(5, 0, ':');
        $pdf->Cell(0, 0, strtoupper($this->item->mahasiswa));

        $pdf->SetXY(25, 89);
        $pdf->Cell(50, 0, '2. Tempat / Tgl. lahir ');
        $pdf->Cell(5, 0, ':');
        $pdf->Cell(0, 0, strtoupper(ucfirst($this->item->pob)).' / '.JHtml::_('date', $this->item->dob, 'd F Y'));

        $pdf->SetXY(25, 98);
        $pdf->Cell(50, 0, '3. Agama ');
        $pdf->Cell(5, 0, ':');
        $pdf->Cell(0, 0, strtoupper($this->item->agama));

        $pdf->SetXY(25, 107);
        $pdf->Cell(50, 0, '4. N P M ');
        $pdf->Cell(5, 0, ':');
        $pdf->Cell(0, 0, strtoupper($this->item->npm));

        $pdf->SetXY(25, 116);
        $pdf->Cell(50, 0, '5. Tingkat ');
        $pdf->Cell(5, 0, ':');
        $pdf->Cell(0, 0, strtoupper($this->item->semester));

        $pdf->SetXY(25, 125);
        $pdf->Cell(50, 0, '6. Program Studi ');
        $pdf->Cell(5, 0, ':');
        $pdf->Cell(0, 0, strtoupper($this->item->prodi).'( '.$this->item->program_studi.' )');

        $pdf->SetXY(25, 134);
        $pdf->Cell(50, 0, '7. Konsentrasi ');
        $pdf->Cell(5, 0, ': ');
        $pdf->Cell(0, 0, strtoupper($this->item->jurusan).' ( '.$this->item->konsentrasi.' )');

        $pdf->SetXY(25, 143);
        $pdf->Cell(50, 0, '8. Golongan Darah ');
        $pdf->Cell(5, 0, ':');
        $pdf->Cell(0, 0, 'A / B / AB / O ');

        $pdf->SetXY(25, 152);
        $pdf->Cell(50, 0, '9. Status Sipil');
        $pdf->Cell(5, 0, ':');

        $pdf->Cell(0, 0, strtoupper($this->item->status_sipil));

        $pdf->SetXY(25, 161);
        $pdf->Cell(50, 0, '10. No Telepon / HP');
        $pdf->Cell(5, 0, ':');
        $pdf->Cell(0, 0, $this->item->telepon);

        $pdf->SetXY(25, 170);
        $pdf->Cell(50, 0, '11. Alamat Rumah ');
        $pdf->Cell(5, 0, ':');
        $pdf->Cell(0, 0, ucfirst($this->item->alamat_1).', '.$this->item->kelurahan);
        $pdf->SetXY(25, 179);
        $pdf->Cell(55, 0, '');
        $pdf->Cell(0, 0, 'Kec. '.ucfirst($this->item->kecamatan).', Kab / Kota '.ucfirst($this->item->kabupaten));
        $pdf->SetXY(25, 188);
        $pdf->Cell(55, 0, '');
        $pdf->Cell(0, 0, 'Propinsi '.ucfirst($this->item->propinsi).', '.$this->item->kode_pos);

        $text = 'Dengan ini menyatakan mendaftarkan diri sebagai Mahasiswa Universitas 17 Agustus 1945 (UNTAG) Cirebon '.
                'pada tahun Akademik %s, Serta akan mentaati dan mematuhi segala peraturan peraturan yang dikeluarkan '.
                'oleh Fakultas maupun Universitas / Yayasan serta akan mengikuti kegiatan kurikuler.';

        $pdf->SetXY(25, 197);
        $pdf->SetDrawColor(255, 0, 0);
        $pdf->writeHTMLCell(155, 0, '', '', sprintf($text, $this->item->ta), 0, 0, 0, true, 'J');

        $pdf->SetXY(110, 220);
        //$pdf->Cell(100, 0, strtoupper($params->get('kota')), 0, 1, 'C', 0, '', 0);
        $pdf->Cell(60, 0, $params->get('kota').', '.JHtml::_('date', $this->item->create_date, 'd F Y'), 0, 1, C);
        $pdf->SetXY(110, 250);
        $pdf->Cell(60, 0, '( '.$this->item->mahasiswa.' )', 0, 1, C);

        //Close and output PDF document
        $pdf->Output('her-registrasi.pdf', 'D');
    }
}
