<?php

class SiakHelper
{
    public $Biodata;
    public $isMahasiswa = false;
    public $Status;

    public $jurusan = [];

    public function __construct()
    {
        $user = JFactory::getUser();
        $uid = $user->id;
        $groups = $user->get('groups');
        $mhs = JComponentHelper::getParams('com_siak')->get('grpMahasiswa');

        if (in_array($mhs, $groups)) {
            $this->isMahasiswa = true;
            $this->loadStatus($uid);
        }
        //$this->loadBiodata($uid);
        $this->getNonJurusanID();
    }

    /**
     * getTA.
     * Return String Tahun Akademik Now.
     */
    public static function getTA()
    {
        $sekarang = explode('-', date('Y-m'));
        // Jan - Juni => TA = (tahun -1) - tahun.
        // Juli - Des => TA = Tahun - (tahun +1)
        if ($sekarang[1] <= 6) {
            $TA = ($sekarang[0] - 1);
            $TA .= '-'.$sekarang[0];
        } else {
            $TA = $sekarang[0];
            $TA .= '-'.($sekarang[0] + 1);
        }

        return $TA;
    }

    public static function loadBiodata($id)
    {
        $db = Jfactory::getDbo();
        $q = $db->getQuery(true);
        $app = JFactory::getApplication();
        $q->select('a.*')
            ->from($db->qn('#__siak_user', 'a'))
            ->where($db->qn('user_id').' = '.(int) $id)
        ;
        $q->select('b.title as tipeUser')
            ->join('LEFT', $db->qn('#__siak_jenis_user', 'b').' ON b.id = a.tipe_user')
        ;

        try {
            $db->setQuery($q);
            $result = $db->LoadObject();
        } catch (\Throwable $th) {
            $app->enqueueMessage($th->getMessage(), 'error');

            return false;
        }

        return $result;
    }

    /**
     * auth Cek akses SIAK.
     *
     * @param mixed $user  User Id yang akan di test
     * @param mixed $akses Kelompok yang di ijinkan (Mahasiswa, dosen, kaprodi, management);
     *
     * @return bool true jika di ijinkan, false, jika di tolak
     */
    public static function auth($user, $akses = 'mahasiswa')
    {
        $groups = $user->get('groups');
        $akses = 'grp'.ucfirst($akses);
        $grp = JComponentHelper::getParams('com_siak')->get($akses);

        if (!in_array($grp, $groups)) {
            return false;
        }

        return true;
    }

    /**
     * getDosenWali.
     *
     * @param int $mahasiswa User ID Mahasiswa
     *
     * @return int UserID Dosen Wali
     */
    public static function getDosenWali($mahasiswa)
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('dw.id')
            ->from($db->qn('#__siak_dosen_wali', 'dw'))
        ;
        $query->join('LEFT', $db->qn('#__siak_user', 'us').' ON dw.angkatan = us.angkatan AND dw.prodi=us.prodi AND dw.kelas=us.kelas');
        $query->where($db->qn('us.user_id').' = '.(int) $mahasiswa);

        $db->setQuery($query);

        try {
            $result = $db->loadResult();
        } catch (\Throwable $th) {
            \JFactory::enqueueMessage($th->getMessage());

            return false;
        }

        return $result;
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

    public static function getStandarNilai()
    {
        $nilai = [];
        $nilai['A'] = ['atas' => '100.00', 'bawah' => '75.00'];
        $nilai['B'] = ['atas' => '74.99', 'bawah' => '65.00'];
        $nilai['C'] = ['atas' => '64.99', 'bawah' => '50.00'];
        $nilai['D'] = ['atas' => '49.99', 'bawah' => '35.00'];
        $nilai['E'] = ['atas' => '34.99', 'bawah' => '0'];

        return json_encode($nilai);
    }

    private function loadStatus($id)
    {
        $db = Jfactory::getDbo();
        $q = $db->getQuery(true);
        $app = JFactory::getApplication();
        $q->select('*')
            ->from($db->qn('#__siak_mhs_status'))
            ->where($db->qn('user_id').' = '.(int) $id)
        ;
        $q->order($db->qn('id').' DESC');

        try {
            $db->setQuery($q);
            $result = $db->LoadObject();
        } catch (\Throwable $th) {
            $app->enqueueMessage($th->getMessage(), 'error');

            return false;
        }

        $this->Status = $result;

        return true;
    }

    /**
     * getNonJurusanID.
     *
     * @param mixed $podi ID prodi yang di lokkup non jurusannya
     *
     * @return int ID Prodi
     */
    private function getNonJurusanID()
    {
        $db = JFactory::getDbo();
        $q = $db->getQuery(true);
        $q->select(['j.id as jurusan_id', 'p.id as prodi_id'])->from($db->qn('#__siak_jurusan', 'j'));
        $q->join('LEFT', $db->qn('#__siak_prodi', 'p').' ON p.id = j.prodi');
        $q->where($db->qn('j.title').' LIKE '.$db->q('Non%'));
        $db->setQuery($q);

        try {
            $result = $db->loadObjectList();
        } catch (\Throwable $th) {
            JFactory::getApplication()->enqueueMessage($th->getMessage(), 'error');

            return false;
        }

        foreach ($result as $val) {
            $this->jurusan[$val->prodi_id] = $val->jurusan_id;
        }

        return true;
    }
}
