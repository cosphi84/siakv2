<?php

defined('_JEXEC') or exit;

jimport('tcpdf.tcpdf');
JLoader::register('TrasnkripHelper', JPATH_COMPONENT.'/helpers/trasnkrip.php');
JLoader::register('MahasiswaHelper', JPATH_COMPONENT_ADMINISTRATOR.'/helpers/mahasiswa.php');

use Joomla\CMS\Date\Date;
use Joomla\CMS\HTML\HTMLHelper;

class SiakViewTranskip extends JViewLegacy
{
    public function display($tpl = null)
    {
        // load Joomla Factory Class
        $app = JFactory::getApplication();
        // Get current user
        $user = JFactory::getUser();
        $namaFilePDF = 'TranskipNilai'.$user->username.'.pdf';

        // make sure we've TCPDF class Library
        if (!class_exists('TCPDF')) {
            $app->enqueueMessage(JText::sprintf('COM_SIAK_ERROR_LIBRARY_NOT_FOUND', 'TCPDF'), 'error');
            $app->redirect(JRoute::_('index.php?option=com_siak&view=transkip'));

            return false;
        }

        // Load transkip data
        $data = $this->get('Items');
        $state = $this->get('State');
        $params = $state->params;

        // Error handler
        $errors = $this->get('Errors');
        if (count($errors) > 0) {
            throw new Exception(implode('<br \>', $errors), 500);

            return false;
        }

        $nilai = [];
        $sumSKS = 0;
        $sumBxS = 0;

        foreach ($data as $k => $v) {
            if ('MURNI' != $v->status && $v->nilai_mutu < $v->nilai_remid_mutu) {
                $v->nilai_akhir = $v->nilai_akhir_remid;
                $v->nilai_angka = $v->nilai_remid_angka;
                $v->nilai_mutu = $v->nilai_remid_mutu;
            }
            unset($v->nilai_akhir_remid, $v->nilai_remid_mutu, $v->nilai_remid_angka);
            $v->BxS = $v->sks * $v->nilai_mutu;
            $sumBxS += $v->BxS;
            $sumSKS += $v->sks;

            $lunas = TrasnkripHelper::getPaymentStatus(
                $user->id,
                $v->sid
            );
            if (!$lunas) {
                $v->nilai_akhir = '-';
                $v->nilai_angka = '-';
                $v->nilai_mutu = '-';
                $v->BxS = '-';
            }
            if (empty($nilai['mhs'])) {
                $nilai['mhs'] = MahasiswaHelper::getData($v->user_id);
            }
            $nilai['nilai'][] = $v;
        }
        ksort($nilai, SORT_NUMERIC);
        $nilai['ipk'] = $sumBxS / $sumSKS;
        $nilai['sumSKS'] = $sumSKS;

        unset($data);

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
        $pdf->SetMargins(0, 5, 5);

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

        // posisi X dan Y
        $xKiri = 20;
        $xKanan = 115;
        $y = 35;
        $spasi = 4;

        // create Header
        $logoUniv = JPATH_BASE.'/'.$params->get('logoUniv');
        $pdf->Image($logoUniv, 20, 5, 20, 20);
        $pdf->Cell(0, 0, strtoupper($params['fakultas']), 0, 1, 'C', 0, '', 0);
        $pdf->setFontsize(12);
        $pdf->Cell(0, 0, strtoupper($params['universitas']), 0, 1, 'C', 0, '', 0);
        $pdf->setFont('times', '', 10);
        $pdf->Cell(0, 0, strtoupper($params->get('akreditasi')), 0, 1, 'C', 0, '', 0);
        $pdf->Cell(
            0,
            0,
            'Alamat : '.$params->get('alamat').', '.$params->get('kota').', '.$params->get('propinsi'),
            0,
            1,
            'C',
            0,
            '',
            0
        );
        $pdf->Line(20, 27, 190, 27, ['width' => 0.5, 'cap' => 'square', 'join' => 'mitter', 'dash' => 0]);

        $pdf->setXY(0, 30);
        $pdf->setFontsize(12);
        $pdf->Cell(0, 0, 'KEMAJUAN NILAI MAHASISWA', 0, 1, 'C', 0, '', 0);
        $y += 5;
        $pdf->setFontsize(10);
        $pdf->Text($xKiri, $y, 'Nama');
        $pdf->Text($xKiri + 30, $y, ':');
        $pdf->Text($xKiri + 32, $y, strtoupper($user->name));

        $pdf->Text($xKanan, $y, 'Program Studi');
        $pdf->Text($xKanan + 30, $y, ':');
        $pdf->Text($xKanan + 32, $y, $nilai['mhs']->programstudi);

        $y += $spasi;
        $pdf->Text($xKiri, $y, 'NPM');
        $pdf->Text($xKiri + 30, $y, ':');
        $pdf->Text($xKiri + 32, $y, $user->username);
        $pdf->Text($xKanan, $y, 'Konsentrasi');
        $pdf->Text($xKanan + 30, $y, ':');
        $pdf->Text($xKanan + 32, $y, $nilai['mhs']->konsentrasi);

        $y += $spasi;
        $pdf->Text($xKiri, $y, 'IPK ');
        $pdf->Text($xKiri + 30, $y, ':');
        $pdf->Text($xKiri + 32, $y, sprintf('%.2f', $nilai['ipk']));
        $pdf->Text($xKanan, $y, 'Jumlah SKS');
        $pdf->Text($xKanan + 30, $y, ':');
        $pdf->Text($xKanan + 32, $y, $sumSKS);
        $y += $spasi;

        $pdf->setFontsize(7);

        $spasi = 3.5;
        $a = 1;
        $xKanan = 110;
        $xKiri = 15;
        $isKiri = true;
        $ttlMK = count($nilai['nilai']);
        $y += 7;
        // tabel Header

        // kiri
        $pdf->Line(
            $xKiri,
            $y,
            ($xKiri + 90),
            ($y),
            ['width' => 0.25, 'cap' => 'square', 'join' => 'mitter', 'dash' => 0]
        );
        $pdf->Text($xKiri, $y, 'Matakuliah');
        $pdf->Text($xKiri + 51, $y, 'SKS');
        $pdf->Text($xKiri + 65, $y, 'Nilai');
        $pdf->Text($xKiri + 80, $y, 'Angka');

        // kanan

        $pdf->Line(
            $xKanan,
            $y,
            ($xKanan + 90),
            ($y),
            ['width' => 0.25, 'cap' => 'square', 'join' => 'mitter', 'dash' => 0]
        );
        $pdf->Text($xKanan, $y, 'Matakuliah');
        $pdf->Text($xKanan + 51, $y, 'SKS');
        $pdf->Text($xKanan + 65, $y, 'Nilai');
        $pdf->Text($xKanan + 80, $y, 'Angka');

        $y += $spasi;
        $yTop = $y;
        $pdf->Line(
            $xKiri,
            $y,
            ($xKiri + 90),
            ($y),
            ['width' => 0.25, 'cap' => 'square', 'join' => 'mitter', 'dash' => 0]
        );

        $pdf->Line(
            $xKanan,
            $y,
            ($xKanan + 90),
            ($y),
            ['width' => 0.25, 'cap' => 'square', 'join' => 'mitter', 'dash' => 0]
        );

        // isi tabel kiri
        for ($i = 0; $i < $ttlMK / 2; ++$i) {
            if (strlen($nilai['nilai'][$i]->mk) >= 25) {
                $pdf->SetFontsize(6);
                $pdf->Text($xKiri, $y, strtoupper($nilai['nilai'][$i]->mk));
                $pdf->SetFontsize(7);
            } else {
                $pdf->Text($xKiri, $y, strtoupper($nilai['nilai'][$i]->mk));
            }
            $pdf->Text($xKiri + 52, $y, $nilai['nilai'][$i]->sks);
            $pdf->Text($xKiri + 67, $y, $nilai['nilai'][$i]->nilai_angka);
            $pdf->Text($xKiri + 82, $y, $nilai['nilai'][$i]->nilai_mutu);

            $y += $spasi;

            $pdf->Line(
                $xKiri,
                $y,
                ($xKiri + 90),
                ($y),
                ['width' => 0.25, 'cap' => 'square', 'join' => 'mitter', 'dash' => 0]
            );
        }

        $pdf->Line(
            $xKiri,
            $yTop - $spasi,
            ($xKiri),
            ($y),
            ['width' => 0.25, 'cap' => 'square', 'join' => 'mitter', 'dash' => 0]
        );

        $pdf->Line(
            $xKiri + 45,
            $yTop - $spasi,
            $xKiri + 45,
            ($y),
            ['width' => 0.25, 'cap' => 'square', 'join' => 'mitter', 'dash' => 0]
        );
        $pdf->Line(
            $xKiri + 60,
            $yTop - $spasi,
            $xKiri + 60,
            ($y),
            ['width' => 0.25, 'cap' => 'square', 'join' => 'mitter', 'dash' => 0]
        );
        $pdf->Line(
            $xKiri + 75,
            $yTop - $spasi,
            $xKiri + 75,
            ($y),
            ['width' => 0.25, 'cap' => 'square', 'join' => 'mitter', 'dash' => 0]
        );
        $pdf->Line(
            $xKiri + 90,
            $yTop - $spasi,
            $xKiri + 90,
            ($y),
            ['width' => 0.25, 'cap' => 'square', 'join' => 'mitter', 'dash' => 0]
        );

        // tabel kanan
        $y = $yTop;

        for ($i = ($ttlMK / 2); $i <= $ttlMK; ++$i) {
            if (strlen($nilai['nilai'][$i]->mk) >= 25) {
                $pdf->SetFontsize(6);
                $pdf->Text($xKanan, $y, strtoupper($nilai['nilai'][$i]->mk));
                $pdf->SetFontsize(7);
            } else {
                $pdf->Text($xKanan, $y, strtoupper($nilai['nilai'][$i]->mk));
            }
            $pdf->Text($xKanan + 52, $y, $nilai['nilai'][$i]->sks);
            $pdf->Text($xKanan + 67, $y, $nilai['nilai'][$i]->nilai_angka);
            $pdf->Text($xKanan + 82, $y, $nilai['nilai'][$i]->nilai_mutu);

            $y += $spasi;

            $pdf->Line(
                $xKanan,
                $y,
                ($xKanan + 90),
                ($y),
                ['width' => 0.25, 'cap' => 'square', 'join' => 'mitter', 'dash' => 0]
            );
        }

        $pdf->Line(
            $xKanan,
            $yTop - $spasi,
            ($xKanan),
            ($y),
            ['width' => 0.25, 'cap' => 'square', 'join' => 'mitter', 'dash' => 0]
        );

        $pdf->Line(
            $xKanan + 45,
            $yTop - $spasi,
            $xKanan + 45,
            ($y),
            ['width' => 0.25, 'cap' => 'square', 'join' => 'mitter', 'dash' => 0]
        );
        $pdf->Line(
            $xKanan + 60,
            $yTop - $spasi,
            $xKanan + 60,
            ($y),
            ['width' => 0.25, 'cap' => 'square', 'join' => 'mitter', 'dash' => 0]
        );
        $pdf->Line(
            $xKanan + 75,
            $yTop - $spasi,
            $xKanan + 75,
            ($y),
            ['width' => 0.25, 'cap' => 'square', 'join' => 'mitter', 'dash' => 0]
        );
        $pdf->Line(
            $xKanan + 90,
            $yTop - $spasi,
            $xKanan + 90,
            ($y),
            ['width' => 0.25, 'cap' => 'square', 'join' => 'mitter', 'dash' => 0]
        );

        $y += 30;

        $pdf->SetFont('times', 'I', 8);
        $pdf->Text($xKiri, $y, 'Di cetak mandiri dari SIAK '.ucwords(strtolower($params->get('fakultas'))));
        $y += $spasi;
        $pdf->Text($xKiri, $y, 'Pada '.HTMLHelper::date(Date::getInstance(), 'd F Y H:i:s'));

        // put PDF to PHP Stream Dwnload
        $pdf->Output($namaFilePDF, 'D');
    }
}
