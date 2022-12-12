<?php

defined('_JEXEC') or exit;

class Dosen
{
    //properti Group Dosen
    public $groupDosen;

    //contruktor
    public function __construct()
    {
        $app = JFactory::getApplication();
        $params = JComponentHelper::getParams('com_siak');
        $this->groupDosen = $params->get('grpDosen');
    }

    /**
     * Jumlah Total dosen yang ada.
     *
     * @return int Jumlah Dosen
     */
    public function jumlahDosen()
    {
        $app = JFactory::getApplication();
        $db = JFactory::getDbo();
        $query = $db->getQuey(true);

        $query->select('COUNT(u.id)')->from($db->qn('#__users', 'u'));
        $query->leftJoin('#__user_usergroup_map AS ug ON ug.user_id = u.id');
        $query->where($db->qn('ug.group_id').' = '.$db->q($this->groupDosen));

        $db->setQuery($query);

        try {
            $db->execute();
        } catch (RuntimeException $e) {
            $app->enqueueMessage($e->getMessage(), 'error');

            return false;
        }

        return $db->getNumRows();
    }

    /**
     * Mencari nama atau id dosen berdasarkan matakuliah.
     *
     * @param null|mixed $matakuliah   ID matakuliah yang akan di cari siapa dosennya
     * @param bool       $numeric      True untuk id dan false untuk nama, Default true
     * @param null|mixed $tahun_ajaran
     * @param null|mixed $kelas
     *
     * @return false on Error, int 0 on empty, String Nama Dosen on $numeric = false, INT ID dosen on $numeric true
     */
    public static function getDosenByMk($prodi = null, $konsentrasi = null, $kelas = null, $matakuliah = null, $tahun_ajaran = null, $numeric = true)
    {
        $app = JFactory::getApplication();
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);

        null == $tahun_ajaran ? $tahun_ajaran = Siak::getTA() : $tahun_ajaran;

        $query->select(['u.id', 'u.name'])->from($db->qn('#__users', 'u'));
        $query->leftJoin('#__siak_dosen_mk AS d ON u.id = d.user_id');
        if ($prodi != null) {
            $query->where($db->qn('d.prodi') . ' = '. (int)$prodi);
        }
        if ($konsentrasi != null) {
            $query->where($db->qn('d.jurusan') . ' = '. (int)$konsentrasi);
        }
        $query->where($db->qn('d.matakuliah').' = '.(int) $matakuliah);
        $query->where($db->qn('d.tahun_ajaran').' = '.$db->q($tahun_ajaran));
        $query->where($db->qn('d.kelas').' = '.(int) $kelas);
        $query->where($db->qn('d.state').' = 1');

        $db->setQuery($query);

        try {
            $db->execute();
        } catch (RuntimeException $e) {
            $app->enqueueMessage($e->getMessage(), 'error');

            return false;
        }

        $num = $db->getNumRows();
        if ($num <= 0) {
            return 0;
        }

        $dosen = $db->loadObject();
        if ($numeric) {
            return $dosen->id;
        }

        return $dosen->name;
    }
}
