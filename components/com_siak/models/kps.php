<?php

defined('_JEXEC') or exit('Metuoo Suuu!!');

class SiakModelKps extends JModelList
{
    public function __construct($config = [])
    {
        if (empty($config['filter_fields'])) {
            $config['filter_fields'] = [
                'prodi',
                'jurusan',
            ];
        }
        parent::__construct($config);
    }

    protected function populateState($ordering = 'null', $direction = 'null')
    {
        $prodi = $this->getUserStateFromRequest($this->context.'.filter.prodi', 'filter_prodi', '', 'string');
        $jurusan = $this->getUserStateFromRequest($this->context.'.filter.jurusan', 'filter_jurusan', '', 'string');
        $search = $this->getUserStateFromRequest($this->context.'.filter.search', 'filter_search', '', 'string');

        $this->setState('filter.prodi', $prodi);
        $this->setState('filter.jurusan', $jurusan);
        $this->setState('filter.search', $search);

        parent::populateState($ordering, $direction);
    }

    protected function getListQuery()
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $search = $this->getState('filter.search');

        $user = JFactory::getUser();

        $grpMhs = JComponentHelper::getParams('com_siak')->get('grpMahasiswa');
        if (in_array($grpMhs, $user->get('groups')) && '' == $search) {
            $search = $user->username;
        }
        $prodi = $this->getState('filter.prodi');
        $jurusan = $this->getState('filter.jurusan');

        $query->select(['kp.id', 'kp.user_id', 'kp.tanggal_mulai AS start', 'kp.tanggal_selesai AS finish', 'kp.dosbing', 'kp.tanggal_seminar AS seminar', 'kp.file_laporan', 'kp.state as status'])
            ->from($db->qn('#__siak_kp', 'kp'))
        ;
        $query->select(['i.nama as instansi', 'i.alamat', 'i.kabupaten', 'i.propinsi'])
            ->leftJoin('#__siak_industri AS i ON i.id = kp.instansi')
        ;
        $query->select('p.title AS prodi')
            ->join('LEFT', $db->qn('#__siak_prodi', 'p').' ON p.id = kp.prodi')
        ;

        $query->select('j.title AS jurusan')
            ->join('LEFT', $db->qn('#__siak_jurusan', 'j').' ON j.id = kp.jurusan')
        ;

        $query->select('k.title AS kelas')
            ->join('LEFT', $db->qn('#__siak_kelas_mahasiswa', 'k').' ON k.id = kp.kelas')
        ;

        $query->select(['u.name AS mahasiswa', 'u.username as npm'])
            ->join('LEFT', $db->qn('#__users', 'u').' ON u.id = kp.user_id')
        ;

        $query->select('t.name AS dosbing')
            ->join('LEFT', $db->qn('#__users', 't').' ON t.id = kp.dosbing')
        ;

        if (!empty($search)) {
            $search = $db->quote('%'.$search.'%');
            $searchs = [];
            $searchs[] = $db->qn('u.name').' LIKE '.$search;
            $searchs[] = $db->qn('u.username').' LIKE '.$search;
            $query->where('('.implode(' OR ', $searchs).' )');
        } else {
            $query->where($db->qn('kp.tahun_ajaran').' = '.$db->q(SiakHelper::getTA()));
        }

        if (is_numeric($prodi)) {
            $query->where($db->qn('kp.prodi').' = '.(int) $prodi);
        }

        if (is_numeric($jurusan)) {
            $query->where($db->qn('kp.jurusan').' = '.(int) $jurusan);
        }

        $query->where($db->qn('kp.state').' = 1');

        return $query;
    }

    protected function getStoreId($id = '')
    {
        // Compile the store id.
        $id .= ':'.$this->getState('filter.search');
        $id .= ':'.$this->getState('filter.prodi');
        $id .= ':'.$this->getState('filter.jurusan');

        return parent::getStoreId($id);
    }
}
