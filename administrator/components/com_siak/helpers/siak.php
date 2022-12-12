<?php

defined('_JEXEC') or exit;

class SiakHelper extends JHelperContent
{
    public static function submenuFakultas($vName)
    {
        $canDo = JHelperContent::getActions('com_siak');

        if ($canDo->get('core.options')) {
            JHtmlSidebar::addEntry(
                JText::_('COM_SIAK_SIDE_MENU_PRODI'),
                'index.php?option=com_siak&view=prodis',
                'Prodis' == $vName
            );

            JHtmlSidebar::addEntry(
                JText::_('COM_SIAK_SIDE_MENU_JURUSAN'),
                'index.php?option=com_siak&view=jurusans',
                'Jurusans' == $vName
            );

            JHtmlSidebar::addEntry(
                JText::_('COM_SIAK_SIDE_MENU_KELASES'),
                'index.php?option=com_siak&view=kelases',
                'Kelases' == $vName
            );

            JHtmlSidebar::addEntry(
                JText::_('COM_SIAK_SIDE_MENU_BOBOTS'),
                'index.php?option=com_siak&view=bobots',
                'Bobots' == $vName
            );
        }

        JHtmlSidebar::addEntry(
            JText::_('COM_SIAK_SIDE_MENU_RUANGANS'),
            'index.php?option=com_siak&view=ruangans',
            'Ruangans' == $vName
        );

        JHtmlSidebar::addEntry(
            JText::_('COM_SIAK_SIDE_MENU_SKS'),
            'index.php?option=com_siak&view=sks',
            'Sks' == $vName
        );

        JHtmlSidebar::addEntry(
            JText::_('COM_SIAK_SIDE_MENU_SEMESTERS'),
            'index.php?option=com_siak&view=semesters',
            'Semesters' == $vName
        );

        JHtmlSidebar::addEntry(
            JText::_('COM_SIAK_SIDE_MENU_MATKULS'),
            'index.php?option=com_siak&view=matkuls',
            'Matkuls' == $vName
        );

        JHtmlSidebar::addEntry(
            JText::_('COM_SIAK_SIDE_MENU_PAKETMKS'),
            'index.php?option=com_siak&view=paketmks',
            'Paketmks' == $vName
        );
        JHtmlSidebar::addEntry(
            JText::_('COM_SIAK_SIDE_MENU_MAHASISWAS'),
            'index.php?option=com_siak&view=mahasiswas',
            'Mahasiswas' == $vName
        );

        JHtmlSidebar::addEntry(
            JText::_('COM_SIAK_SIDE_MENU_DOSENS'),
            'index.php?option=com_siak&view=dosens',
            'Dosens' == $vName
        );

        JHtmlSidebar::addEntry(
            JText::_('COM_SIAK_SIDE_MENU_WALIS'),
            'index.php?option=com_siak&view=walis',
            'Walis' == $vName
        );

        JHtmlSidebar::addEntry(
            JText::_('COM_SIAK_SIDE_MENU_DOSENMKS'),
            'index.php?option=com_siak&view=dosenmks',
            'Dosenmks' == $vName
        );

        JHtmlSidebar::addEntry(
            JText::_('COM_SIAK_SIDE_MENU_INDUSTRIES'),
            'index.php?option=com_siak&view=industries',
            'Industries' == $vName
        );
    }

    public static function submenuKeuangan($vName)
    {
        JHtmlSidebar::addEntry(
            JText::_('COM_SIAK_SIDE_MENU_BAYARANS'),
            'index.php?option=com_siak&view=bayarans',
            'Bayarans' == $vName
        );

        JHtmlSidebar::addEntry(
            JText::_('COM_SIAK_SIDE_MENU_TUNDABAYAR'),
            'index.php?option=com_siak&view=tundabayar',
            'Tundabayar' == $vName
        );
    }

    public static function submenuAkademik($vName)
    {
        JHtmlSidebar::addEntry(
            JText::_('COM_SIAK_SIDE_MENU_JADWALS'),
            'index.php?option=com_siak&view=jadwals',
            'Jadwals' == $vName
        );

        JHtmlSidebar::addEntry(
            JText::_('COM_SIAK_SIDE_MENU_DAFTAR_ULANGS'),
            'index.php?option=com_siak&view=dus',
            'Dus' == $vName
        );

        JHtmlSidebar::addEntry(
            JText::_('COM_SIAK_SIDE_MENU_KRSS'),
            'index.php?option=com_siak&view=krss',
            'Krss' == $vName
        );

        JHtmlSidebar::addEntry(
            JText::_('COM_SIAK_SIDE_MENU_ROMBELS'),
            'index.php?option=com_siak&view=rombels',
            'Rombels' == $vName
        );

        JHtmlSidebar::addEntry(
            JText::_('COM_SIAK_SIDE_MENU_PRAKTIKUMS'),
            'index.php?option=com_siak&view=praktikums',
            'Praktikums' == $vName
        );

        JHtmlSidebar::addEntry(
            JText::_('COM_SIAK_SIDE_MENU_KPS'),
            'index.php?option=com_siak&view=kps',
            'Kps' == $vName
        );

        /*
        JHtmlSidebar::addEntry(
            JText::_('COM_SIAK_SIDE_MENU_SPS'),
            'index.php?option=com_siak&view=sps',
            'Sps' == $vName
        );

        JHtmlSidebar::addEntry(
            JText::_('COM_SIAK_SIDE_MENU_PROPTAS'),
            'index.php?option=com_siak&view=proptas',
            'Proptas' == $vName
        );

        JHtmlSidebar::addEntry(
            JText::_('COM_SIAK_SIDE_MENU_BERKTAS'),
            'index.php?option=com_siak&view=brektas',
            'Berktas' == $vName
        );

        JHtmlSidebar::addEntry(
            JText::_('COM_SIAK_SIDE_MENU_TAS'),
            'index.php?option=com_siak&view=Tas',
            'Tas' == $vName
        );

        JHtmlSidebar::addEntry(
            JText::_('COM_SIAK_SIDE_MENU_SIDANGTA'),
            'index.php?option=com_siak&view=sidangs',
            'Sidangs' == $vName
        );
        */

        JHtmlSidebar::addEntry(
            JText::_('COM_SIAK_SIDE_MENU_NILAIS'),
            'index.php?option=com_siak&view=nilais',
            'Nilais' == $vName
        );
    }
}

class Siak
{
    /**
     * statusMahasiswa
     * Translasi kode Status Mahasiswa menjasi Human readable.
     *
     * @param int $status Kode Status Mahasiswa
     *
     * @return string Human readable Status
     */
    public static function statusMahasiswa($status = 0)
    {
        $stt = ['2' => 'Lulus',
            '1' => 'Aktif',
            '0' => 'Tidak Aktif',
            '-1' => 'Cuti',
            '-2' => 'Pindah',
            '-3' => 'Drop Out', ];

        return $stt[$status];
    }

    public static function hari($noHari = 1)
    {
        $hari = [
            '1' => 'SUNDAY',
            '2' => 'MONDAY',
            '3' => 'TUESDAY',
            '4' => 'WEDNESDAY',
            '5' => 'THURSDAY',
            '6' => 'FRIDAY',
            '7' => 'SATURDAY',
        ];

        return $hari[$noHari];
    }

    public static function statusKRS($status = 0)
    {
        $krs = [
            '2' => 'Disetujui',
            '1' => 'Dicek',
            '0' => 'Diajukan',
            '-1' => 'Rancangan',
            '-2' => 'Ditolak',
        ];

        return $krs[$status];
    }

    public static function genToken()
    {
        $token = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $serial = '';

        for ($a = 0; $a <= 4; ++$a) {
            for ($b = 0; $b < 3; ++$b) {
                $serial .= $token[rand(0, 35)];
            }
        }

        return $serial;
    }

    public static function fmtRupiah($angka)
    {
        return 'Rp. '.number_format($angka, 2, ',', '.');
    }

    public static function getTA()
    {
        $sekarang = explode('-', date('Y-m'));
        // Jan - Juni => TA = (tahun -1) - tahun.
        // Juli - Des => TA = Tahun - (tahun +1)
        if ($sekarang[1] <= 10) {
            $TA = ($sekarang[0] - 1);
            $TA .= '-'.$sekarang[0];
        } else {
            $TA = $sekarang[0];
            $TA .= '-'.($sekarang[0] + 1);
        }

        return $TA;
    }

    public function getDataMahasiswa($user_id)
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('*');
        $query->from($db->qn('#__siak_biodata'));
        $query->where($db->qn('user_id').' = '.$db->q($user_id));
        $db->setQuery($query);

        $data = $db->loadObject();
        unset($data->id);

        return $data;
    }

    /**
     * @param mixed $needle   Kata yang dicari
     * @param mixed $haystack Paragaraf atau sekumpulan kata
     * @param mixed $i        i untuk Insesitive, default sensitive
     * @param mixed $word     W untuk pencarian Word, default string
     */
    public static function FindString($needle, $haystack, $i = '', $word = '')
    {   // $i should be "" or "i" for case insensitive
        if ('W' == strtoupper($word)) {   // if $word is "W" then word search instead of string in string search.
            if (preg_match("/\\b{$needle}\\b/{$i}", $haystack)) {
                return true;
            }
        } else {
            if (preg_match("/{$needle}/{$i}", $haystack)) {
                return true;
            }
        }

        return false;
        // Put quotes around true and false above to return them as strings instead of as bools/ints.
    }

    /**
     * Upload Berkas SIAK.
     *
     * @param string $owner      Nama File yang akan di upload
     * @param string $typeBerkas Type berkas yang di upload (Nama folder di media/files)
     * @param bool   $overide    True => hapus file lama jika ada, false, biarkan file lama dan tambah no urut di belakangnya. default False
     * @param bool   $kompress   True jika file adalah foto dan ingin di kompress, false, engga. default true
     * @param array  $ukuranBaru ukuran baru jiak di kompress (W dan H)
     *
     * @return string Nama File, false on Error
     */
    public static function uploadDokumen($owner, $typeBerkas, $overide = true, $kompress = false, $ukuranBaru = ['W' => '1028', 'H' => '573'])
    {
        $app = JFactory::getApplication();
        jimport('joomla.filesystem.file');
        jimport('joomla.filesystem.folder');

        $files = $app->input->files->get('jform', [], 'array');
        $file = $files[$typeBerkas]['file'];

        if (0 < $file['error']) {
            $app->enqueueMessage(JText::_('COM_SIAK_INDUSTRI_ERROR_UPLOAD_FILE_NOT_PROCESSED'), 'warning');

            return false;
        }

        //$filename = JFile::makeSafe($file['name']);
        $fileExt = strtolower(JFile::getExt($file['name']));

        $owner = strtolower($owner);
        $owner = preg_replace('/[^a-z0-9 -]+/', '', $owner);
        $owner = str_replace(' ', '-', $owner);

        $path = JPATH_ROOT.'/media/com_siak/files/'.$typeBerkas;

        if (!JFolder::exists($path)) {
            $app->enqueueMessage('Upload Folder Tujuan tidak ditemukan', 'error');

            return false;
        }

        if ((bool) $overide) {
            $namaFile = $owner.'.'.$fileExt;
        } else {
            $namaFile = $owner.'_'.time().'.'.$fileExt;
        }

        $newFile = $path.'/'.$namaFile;

        if (JFile::exists($newFile)) {
            JFile::delete($newFile);
        }

        if (!JFile::upload($file['tmp_name'], $newFile)) {
            $app->enqueueMessage(JText::_('COM_SIAK_ERROR_UNABLE_TO_UPLOAD_FILE'), 'error');

            return false;
        }

        if ('image' == $file['type'] && $kompress) {
            $image = new JImage($newFile);
            $prop = JImage::getImageFileProperties($newFile);
            $arah = $image->getOrientation();
            if ('portrait' == $arah) {
                $image->rotate(90, -1, false);
            }

            $newImg = $image->resize($ukuranBaru['W'], $ukuranBaru['H'], false);
            $mime = $prop->mime;

            if ('image/jpeg' == $mime) {
                $type = IMAGETYPE_JPEG;
            } elseif ('image/png' == $mime) {
                $type = IMAGETYPE_PNG;
            } elseif ('image/gif' == $mime) {
                $type = IMAGETYPE_GIF;
            }
            $newImg->toFile($newFile, $mime);
        }

        return $namaFile;
    }

    public static function deleteBerkas($namaFile, $tipe)
    {
        $app = JFactory::getApplication();
        jimport('joomla.filesystem.file');
        jimport('joomla.filesystem.folder');

        $path = JPATH_ROOT.'/media/com_siak/files/'.$tipe;
        $file = $path.'/'.$namaFile;

        if (!JFile::exists($file)) {
            return false;
        }

        if (!JFile::delete($file)) {
            $app->enqueueMessage(JText::_('COM_SIAK_FILE_CANNOT_DELETED'), 'error');

            return false;
        }

        return true;
    }

    public static function getNilaiMutu($nilai = 0)
    {
        $result = [];

        switch ($nilai) {
            case $nilai >= 75:
                $result['angka'] = '4';
                $result['huruf'] = 'A';

                break;

            case $nilai <= 74.99 && $nilai >= 65.00:
                $result['angka'] = '3';
                $result['huruf'] = 'B';

                break;

            case $nilai <= 64.99 && $nilai >= 50.00:
                $result['angka'] = '2';
                $result['huruf'] = 'C';

                break;

            case $nilai <= 49.99 && $nilai >= 35:
                $result['angka'] = '1';
                $result['huruf'] = 'D';

                break;

            default:
            $result['angka'] = '0';
            $result['huruf'] = 'E';

            break;
        }

        return $result;
    }
}
