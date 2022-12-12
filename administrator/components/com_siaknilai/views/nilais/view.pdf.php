<?php
/**
 * @package     Joomla.Siak
 * @subpackage  Siak Nilai
 *
 * @copyright   (C) 2022 @ Risam, S.T
 * @license     Limited for FT-UNTAG Cirebon use Only
 */

defined('_JEXEC') or die();

use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\View\HtmlView;
use Joomla\CMS\Toolbar\ToolbarHelper;
use Joomla\CMS\Helper\ContentHelper;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Uri\Uri;
use Joomla\CMS\Date\Date;

jimport('tcpdf.tcpdf');

class SiaknilaiViewNilais extends HtmlView
{
    public $filterForm;
    public $activeFilters;
    protected $items;
    protected $pagination;
    protected $state;
    protected $canDo;
    protected $mahasiswa;
    protected $TA;
    protected $totalSKS = 0;
    protected $sumBxS = 0;
    protected $ipk = 0;


    public function display($tpl = null)
    {
        $this->items = $this->get('Items');
        $this->pagination = $this->get('Pagination');
        $this->filterForm = $this->get('FilterForm');
        $this->activeFilters = $this->get('ActiveFilters');
        $this->state = $this->get('State');
        $this->mahasiswa = $this->get('Mahasiswa');
        $this->TA = $this->get('TugasAkhir');
        $this->canDo = ContentHelper::getActions('com_siaknilai');
        $app = Factory::getApplication();
        $params = ComponentHelper::getParams('com_siak');
        $namaFilePDF = 'TranskipNilai-'.$this->mahasiswa->username.'.pdf';

        $bulanRomawi = array(
            '01'=>'I',
            '02'=>'II',
            '03'=>'III',
            '04'=>'IV',
            '05'=>'V',
            '06'=>'VI',
            '07'=>'VII',
            '08'=>'VIII',
            '09'=>'IX',
            '10'=>'X',
            '11'=>'XI',
            '12'=>'XII'
        );

        $today = Date::getInstance();
        $noTR = 'No:       /TR/Dekan-FT/UNTAG/'. $bulanRomawi[$today->month]. '/'.$today->year;

        if (!class_exists('TCPDF')) {
            $app->enqueueMessage('Error : Library TCPDF tidak ditemukan!', 'error');
            $app->redirect(JRoute::_('index.php?option=com_siaknilai&view=nilais'));

            return false;
        }

        if (empty($this->state->get('filter.search'))) {
            $app->enqueueMessage('Error: Tidak ada mahasiswa yang dipilih!', 'error');
            $app->redirect('index.php?option=com_siaknilai');
            return false;
        }

        if (count($errors = $this->get('Errors'))) {
            throw new Exception(implode('\n', $errors), 500);
        }

        if (!empty($this->items)) {
            // hitung total SKS
            foreach ($this->items as $i=>$v) {
                $this->totalSKS += $v->sks;
                $this->sumBxS += ($v->sks * $v->nilai_mutu);
            }

            $this->ipk = $this->sumBxS / $this->totalSKS;
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
        $pdf->setFont('times', 'BU', 12);
        $pdf->Cell(0, 0, 'TRANSKRIP NILAI', 0, 1, 'C', 0, '', 0);
        $pdf->setFont('times', '', 10);
        $pdf->setXY(0, 35);
        $pdf->Cell(0, 0, $noTR, 0, 1, 'C', 0, '', 0);

        $pdf->setFont('times', '', 10);
        $pdf->Text(20, 42, 'Nama');
        $pdf->Text(50, 42, ':');
        $pdf->Text(52, 42, $this->mahasiswa->name);

        $pdf->Text(115, 42, 'Program Studi');
        $pdf->Text(145, 42, ':');
        $pdf->Text(147, 42, $this->mahasiswa->programstudi . ' - '. $this->mahasiswa->angkatan);

        $pdf->Text(20, 46, 'NPM');
        $pdf->Text(50, 46, ':');
        $pdf->Text(52, 46, strtoupper($this->mahasiswa->username));

        $dob = '-';
        if ($this->mahasiswa->dob != '') {
            $dob = HTMLHelper::date($this->mahasiswa->dob, 'd-m-Y');
        }

        $pdf->Text(20, 50, 'Tempat Tgl Lahir');
        $pdf->Text(50, 50, ':');
        $pdf->Text(52, 50, $this->mahasiswa->pob.', '.$dob);


        $pdf->Text(20, 54, 'Total SKS ');
        $pdf->Text(50, 54, ':');
        $pdf->Text(52, 54, $this->totalSKS);


        if ($this->TA->tanggal_lulus == '' || $this->TA->tanggal_lulus == '0000-00-00') {
            $ty = '-';
        } else {
            $ty = HTMLHelper::date($this->TA->tanggal_lulus, 'd/m/Y');
        }
        $pdf->Text(115, 50, 'Tanggal Yudisium');
        $pdf->Text(145, 50, ':');
        $pdf->Text(147, 50, $ty);


        $pdf->Text(115, 46, 'IPK ');
        $pdf->Text(145, 46, ':');
        $pdf->Text(147, 46, sprintf('%.2f', $this->ipk));




        $pdf->Text(115, 54, 'Yudisium');
        $pdf->Text(145, 54, ':');
        $pdf->Text(147, 54, $this->TA->yudisium);

        // -------------------

        $mk = array();
        $a=0;
        foreach ($this->items as $i=>$v) {
            $mk[$v->sm][] = array(
                'kodemk'=>$v->kodemk,
                'mk'=>$v->mk,
                'sks'=> $v->sks,
                'nilai' => $v->nilai_angka,

            );
        }
        ksort($mk);

        $vspace = 3.5;
        $y = 61;
        $xKiri = 15;
        $xKanan = 110;

        $pdf->setFont('times', '', 7);

        // gambar tabel

        $isKiri = true;
        // data Y tertinggi untuk referensi awal setiap tabel
        $yMax = 0;
        $yAwal = 61;

        foreach ($mk as $i=>$dataNilai) {
            uasort($dataNilai, fn ($a, $b) => $a['kodemk'] <=> $b['kodemk']);

            // Kita mau gambar tabel kanan atau kiri inih?
            (bool) $isKiri ? $x = $xKiri : $x = $xKanan;

            $pdf->Text($x, $y, 'Semester : '.$i);
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


            foreach ($dataNilai as $key => $a) {
                $pdf->Line(
                    $x,
                    $y,
                    ($x + 90),
                    $y,
                    ['width' => 0.25, 'cap' => 'square', 'join' => 'mitter', 'dash' => 0]
                );

                $pdf->Text($x, $y, $a['kodemk']);

                if (strlen($a['mk']) >= 30) {
                    $pdf->setFontsize(6);
                    $pdf->Text($x + 18, $y, $a['mk']);
                    $pdf->setFontsize(7);
                } else {
                    $pdf->Text($x + 18, $y, $a['mk']);
                }

                $pdf->Text($x + 73, $y, $a['sks']);
                $pdf->Text($x + 83, $y, $a['nilai']);
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


                $pdf->Line(
                    $x + 90,
                    $yTop,
                    $x + 90,
                    $y,
                    ['width' => 0.25, 'cap' => 'square', 'join' => 'mitter', 'dash' => 0]
                );
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

        $pdf->SetFontsize(9);
        $y = $yMax + $vspace;
        $ttlHuruf = strlen($this->TA->title);

        $pdf->Text($x, $y, 'Judul Tugas Akhir :');
        $pdf->setFont('times', 'I', 10);
        //$pdf->Text($x, $y, $ttlHuruf);
        if ($ttlHuruf <= 80) {
            $pdf->Text($x + 27, $y, $this->TA->title);
        } else {
            // ambil string baris 1
            // dapatkan posisi spasi di karakter terkahir sebelum maksimal huruf
            $penggal = strrpos($this->TA->title, " ", (-1 * ($ttlHuruf - 80)));
            //$pdf->Text($x, $y+7, $penggal);
            // ambil string baris 1 smapai posisi penggal
            $string1 = substr($this->TA->title, 0, $penggal);
            $string2 = substr($this->TA->title, $penggal);
            $pdf->Text($x + 27, $y, $string1);
            $y += 5;
            $pdf->Text($x + 27, $y, $string2);
        }

        $dekan = SiaknilaiHelper::getDekan();

        $pdf->setFont('times', '', 10);
        $y += 5;
        $pdf->Text($x, $y, $params->get('kota').', '.HTMLHelper::Date(Date::getInstance(), 'd F Y'));
        $y += $vspace+2;
        $pdf->Text($x, $y, 'Dekan '.ucwords(strtolower($params->get('fakultas'))));
        $y += 20;
        $pdf->setFont('times', 'U', 10);
        //$pdf->Text($x, $y, Factory::getUser($params->get('dekan'))->name);
        $pdf->Text($x, $y, $dekan->name);
        $pdf->setFont('times', '', 10);
        $y += $vspace;
        $pdf->Text($x+5, $y, 'NIK : '. $dekan->nik);
        // ------------------------
        $pdf->Output($namaFilePDF, 'D');
    }
}
