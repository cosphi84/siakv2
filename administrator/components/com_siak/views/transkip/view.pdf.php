<?php

defined('_JEXEC') or exit;
use Joomla\CMS\Date\Date;
use Joomla\CMS\HTML\HTMLHelper;

jimport('tcpdf.tcpdf');
JLoader::register('MahasiswaHelper', JPATH_COMPONENT_ADMINISTRATOR.'/helpers/mahasiswa.php');
JLoader::register('TaHelper', JPATH_COMPONENT_ADMINISTRATOR.'/helpers/ta.php');

class SiakViewTranskip extends JViewLegacy
{
    public function display($tpl = null)
    {
        // load Joomla Factory Class
        $app = JFactory::getApplication();
        // Get current user
        $user = JFactory::getUser();

        // make sure we've TCPDF class Library
        if (!class_exists('TCPDF')) {
            $app->enqueueMessage(JText::sprintf('COM_SIAK_ERROR_LIBRARY_NOT_FOUND', 'TCPDF'), 'error');
            $app->redirect(JRoute::_('index.php?option=com_siak&view=transkip'));

            return false;
        }

        // Load transkip data
        $nilai = $this->get('Item');
        $state = $this->get('State');
        $errors = $this->get('Errors');
        $params = $state->params;
        $did = $params->get('dekan');
        $dekan = JFactory::getUser($did)->name;

        // We need trasnform nilai array to nilai[smt][id]->value
        // IP = sumBxS / sumSKS. So we summed all SKS and BxS

        $sumSKS = 0;
        $sumBxS = 0;
        $dataNilai = [];
        foreach ($nilai['nilai'] as $k => $v) {
            if ($v->nilai_mutu >= 1) {
                $sumSKS += $v->sks;
                $sumBxS += $v->BxS;
            } else {
                $v->BxS = '--';
                $v->nilai_mutu = '--';
                $v->nilai_angka = '--';
            }
            $dataNilai[$v->smt][] = $v;
        }

        $ipk = $sumBxS / $sumSKS;

        ksort($dataNilai);
        $smts = array_keys($dataNilai);
        $sumMK = count($nilai['nilai']);
        $mhs = $dataNilai[$smts[0]][0]->user_id;

        if ($mhs > 0) {
            $dataMahasiswa = MahasiswaHelper::getData($mhs);
            $mahasiswa = JFactory::getUser($mhs);
            $namaFilePDF = 'TranskipNilai'.$mahasiswa->username.'.pdf';
            $ta = TaHelpers::getTA($mhs);
        } else {
            $errors[] = 'Data tidak lengkap!';
        }

        // Error handler
        if (count($errors) > 0) {
            throw new Exception(implode('<br \>', $errors), 500);

            return false;
        }

        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        // set document information
        $pdf->SetCreator('SIAK '.$params->get('universitas'));
        $pdf->SetAuthor($params->get('fakultas'));
        $pdf->SetTitle('Transkip Nilai Mahaiswa');
        $pdf->SetSubject('Transkip');

        // Remove Header n Footer
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);

        // set default monospaced font
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        // set margins
        $pdf->SetMargins(0, 5, 5, true);

        // set auto page breaks
        $pdf->SetAutoPageBreak(false, PDF_MARGIN_BOTTOM);

        // set image scale factor
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

        // set some language-dependent strings (optional)
        if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
            require_once dirname(__FILE__).'/lang/eng.php';
            $pdf->setLanguageArray($l);
        }

        // ---------------------------------------------------------

        // set font
        $pdf->setFont('times', 'B', 14);

        // add a page
        $pdf->AddPage();

        // create Header
        $logoUniv = JPATH_ROOT.'/'.$params->get('logoUniv');

        $pdf->Image($logoUniv, 20, 5, 20, 20);
        $pdf->Cell(0, 0, strtoupper($params['fakultas']), 0, 1, 'C', 0, '', 0);
        $pdf->setFontsize(12);
        $pdf->Cell(0, 0, strtoupper($params['universitas']), 0, 1, 'C', 0, '', 0);
        $pdf->setFont('times', '', 10);
        $pdf->Cell(0, 0, strtoupper($params->get('akreditasi')), 0, 1, 'C', 0, '', 0);
        $pdf->Cell(0, 0, 'Alamat : '.$params->get('alamat').', '.$params->get('kota').', '.$params->get('propinsi'), 0, 1, 'C', 0, '', 0);
        $pdf->Line(20, 27, 190, 27, ['width' => 0.5, 'cap' => 'square', 'join' => 'mitter', 'dash' => 0]);

        $pdf->setXY(0, 30);
        $pdf->setFont('times', 'B', 12);
        $pdf->Cell(0, 0, 'TRANSKRIP NILAI', 0, 1, 'C', 0, '', 0);
        $pdf->setFont('times', '', 10);
        $pdf->Text(20, 35, 'Nama');
        $pdf->Text(50, 35, ':');
        $pdf->Text(52, 35, $mahasiswa->name);
        $pdf->Text(115, 35, 'Program Studi');
        $pdf->Text(137, 35, ':');
        $pdf->Text(139, 35, $dataMahasiswa->programstudi);

        $pdf->Text(20, 40, 'NPM');
        $pdf->Text(50, 40, ':');
        $pdf->Text(52, 40, strtoupper($mahasiswa->username));
        $pdf->Text(115, 40, 'Tahun Masuk');
        $pdf->Text(137, 40, ':');
        $pdf->Text(139, 40, $dataMahasiswa->angkatan);
        $pdf->Text(20, 45, 'IPK ');
        $pdf->Text(50, 45, ':');
        $pdf->Text(52, 45, sprintf('%.2f', $ipk));

        $pdf->Text(115, 45, 'Tanggal Lulus');
        $pdf->Text(137, 45, ':');
        empty($ta) ? $tanggalulus = '--/--/----' : $tanggalulus = HTMLHelper::date($tanggalulus, 'd/m/Y');
        $pdf->Text(139, 45, $tanggalulus);

        $pdf->Text(20, 50, 'Total SKS ');
        $pdf->Text(50, 50, ':');
        $pdf->Text(52, 50, $sumSKS);

        $vspace = 3.5;
        $y = 55;
        $xKiri = 15;
        $xKanan = 110;

        $pdf->setFont('times', '', 7);

        // gambar tabel

        $isKiri = true;
        // data Y tertinggi untuk referensi awal setiap tabel
        $yMax = 0;
        $yAwal = 55;

        foreach ($smts as $v) {
            // Kita mau gambar tabel kanan atau kiri inih?
            (bool) $isKiri ? $x = $xKiri : $x = $xKanan;

            $pdf->Text($x, $y, 'Semester : '.$v);
            $y += $vspace;

            $pdf->Line(
                $x,
                $y,
                ($x + 90),
                ($y),
                ['width' => 0.25, 'cap' => 'square', 'join' => 'mitter', 'dash' => 0]
            );
            $yTop = $y;

            //header tabel\
            $pdf->Text($x + 3, $y, 'Kode MK');
            $pdf->Text($x + 35, $y, 'Matakuliah');
            $pdf->Text($x + 72, $y, 'SKS');
            $pdf->Text($x + 82, $y, 'Nilai');

            $y += $vspace;
            $pdf->Line(
                $x,
                $y,
                ($x + 90),
                $y,
                ['width' => 0.25, 'cap' => 'square', 'join' => 'mitter', 'dash' => 0]
            );

            $pdf->setFont('times', '', 7);

            foreach ($dataNilai[$v] as $key => $a) {
                /*
                $pdf->Line(
                    $x,
                    $y,
                    ($x + 90),
                    $y,
                    ['width' => 0.25, 'cap' => 'square', 'join' => 'mitter', 'dash' => 0]
                );
                */
                $pdf->Text($x, $y, $a->kodemk);

                if (strlen($a->mk) >= 30) {
                    $pdf->setFontsize(6);
                    $pdf->Text($x + 18, $y, $a->mk);
                    $pdf->setFontsize(7);
                } else {
                    $pdf->Text($x + 18, $y, $a->mk);
                }

                $pdf->Text($x + 73, $y, $a->sks);
                $pdf->Text($x + 83, $y, $a->nilai_angka);
                $y += $vspace;

                $pdf->Line(
                    $x,
                    $y,
                    ($x + 90),
                    $y,
                    ['width' => 0.25, 'cap' => 'square', 'join' => 'mitter', 'dash' => 0]
                );

                $pdf->Line(
                    $x,
                    $yTop,
                    $x,
                    $y,
                    ['width' => 0.25, 'cap' => 'square', 'join' => 'mitter', 'dash' => 0]
                );

                $pdf->Line(
                    $x + 18,
                    $yTop,
                    $x + 18,
                    $y,
                    ['width' => 0.25, 'cap' => 'square', 'join' => 'mitter', 'dash' => 0]
                );

                $pdf->Line(
                    $x + 70,
                    $yTop,
                    $x + 70,
                    $y,
                    ['width' => 0.25, 'cap' => 'square', 'join' => 'mitter', 'dash' => 0]
                );

                $pdf->Line(
                    $x + 80,
                    $yTop,
                    $x + 80,
                    $y,
                    ['width' => 0.25, 'cap' => 'square', 'join' => 'mitter', 'dash' => 0]
                );

                $pdf->Line(
                    $x + 90,
                    $yTop,
                    $x + 90,
                    $y,
                    ['width' => 0.25, 'cap' => 'square', 'join' => 'mitter', 'dash' => 0]
                );

                /*
                $pdf->Line(
                    $x + 90,
                    $yTop,
                    $x + 90,
                    $y,
                    ['width' => 0.25, 'cap' => 'square', 'join' => 'mitter', 'dash' => 0]
                );
                */
            }

            // ambil Y tertinggi
            $y > $yMax ? $yMax = $y : $yMax;

            // Togle tabel flag
            $isKiri = !$isKiri;

            if (!$isKiri) {
                // tabel kanan
                $y = $yAwal;
            } else {
                // tabel kiri, row berikut
                $yAwal = $yMax + $vspace;
                $y = $yAwal;
            }
        }

        $x = 15;

        $y = $yMax + $vspace;
        $pdf->Text($x, $y, 'Judul Tugas Akhir :');
        $pdf->Text($x + 20, $y, '......................................................');

        $pdf->SetFontsize(10);
        $y += 5;
        $pdf->Text($x, $y, $params->get('kota').', '.HTMLHelper::Date(Date::getInstance(), 'd F Y'));
        $y += $vspace;
        $pdf->Text($x, $y, 'Dekan '.ucwords(strtolower($params->get('fakultas'))));
        $y += 20;
        $pdf->Text($x, $y, $dekan);

        /*
        $pdf->setXY($x, $y);
        $pdf->Cell(
            100,
            0,
            $params['kota'].', '.HTMLHelper::Date(Date::getInstance(), 'd F Y'),
            0,
            1,
            'C',
            0,
            '',
            0
        );

        $pdf->setXY($x, $y += $vspace);
        $pdf->Cell(100, 0, 'Dekan '.ucwords(strtolower($params['fakultas'])), 0, 1, 'C', 0, '', 0);

        $y += 20;
        $pdf->setXY($x, $y);
        $pdf->Cell(100, 0, $dekan, 0, 1, 'C', 0, '', 0);
         */
        $y += $vspace;
        $pdf->SetFont('times', 'I', 7);
        $pdf->Text($x, $y, 'Dicetak oleh : '.JFactory::getUser()->name);
        // put PDF to PHP Stream Dwnload
        $pdf->Output($namaFilePDF, 'D');
    }
}
