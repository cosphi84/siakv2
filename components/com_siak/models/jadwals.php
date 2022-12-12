<?php

defined('_JEXEC') or exit;

JLoader::register('SiakHelper', JPATH_COMPONENT.'/helpers/siak.php');
class SiakModelJadwals extends JModelList
{
    public function __construct($config = [])
    {
        if (empty($config['filter_fields'])) {
            $config['filter_fields'] = [
                'prodi',
                'jurusan',
                'semester',
                'kelas',
            ];
        }
        parent::__construct($config);
    }

    protected function getListQuery()
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);

        $search = $this->getState('filter.search');
        $prodi = $this->getState('filter.prodi');
        $jurusan = $this->getState('filter.jurusan');
        $semester = $this->getState('filter.semester');
        $kelas = $this->getState('filter.kelas');

        $query->select('jw.*')
            ->from($db->qn('#__siak_jadwal_kbm', 'jw'))
        ;

        $query->select('p.alias as prodi')
            ->leftJoin('#__siak_prodi AS p ON p.id = jw.prodi')
        ;

        $query->select('j.alias AS konsentrasi')
            ->leftJoin('#__siak_jurusan AS j ON j.id = jw.jurusan')
        ;

        $query->select('s.title AS semester')
            ->leftJoin('#__siak_semester AS s ON s.id = jw.semester')
        ;

        $query->select('k.title AS kelas')
            ->leftJoin('#__siak_kelas_mahasiswa AS k ON k.id = jw.kelas')
        ;

        $query->select(['m.title AS kodeMK', 'm.alias AS matakuliah'])
            ->leftJoin('#__siak_matakuliah AS m ON m.id = jw.matakuliah')
        ;

        $query->select('dmk.user_id AS dosenID')
            ->leftJoin('#__siak_dosen_mk AS dmk on dmk.prodi = jw.prodi AND dmk.jurusan = jw.jurusan AND dmk.kelas=jw.kelas AND dmk.matakuliah=jw.matakuliah AND dmk.tahun_ajaran = '.$db->q(SiakHelper::getTA()))
        ;

        $query->select('r.title AS ruangan')
            ->leftJoin('#__siak_ruangan AS r ON r.id = jw.ruangan')
        ;

        $query->where($db->qn('jw.prodi').' = '.(int) $prodi);

        if (!empty($jurusan)) {
            $query->where($db->qn('jw.jurusan').' = '.(int) $jurusan);
        }

        if (!empty($semester)) {
            $query->where($db->qn('jw.semester').' = '.(int) $semester);
        }

        if (!empty($kelas)) {
            $query->where($db->qn('jw.kelas').' = '.(int) $kelas);
        }

        $query->where($db->qn('jw.state').' = '. $db->q('1'));

        if (!empty($search)) {
            $query->where($db->qn('jw.hari').' LIKE '.$db->q('%'.$search.'%'));
        }

        $query->order($db->qn('jw.hari').' ASC');
      	//echo $query->dump();

        return $query;
    }

    protected function populateState($order = 'jw.hari', $dir = 'ASC')
    {
        $this->setState('filter.prodi', $this->getUserStateFromRequest($this->context.'.filter.prodi', 'filter_prodi', '', 'string'));
        $this->setState('filter.jurusan', $this->getUserStateFromRequest($this->context.'.filter.jurusan', 'filter_jurusan', '', 'string'));
        $this->setState('filter.semester', $this->getUserStateFromRequest($this->context.'.filter.semester', 'filter_semester', '', 'string'));
        $this->setState('filter.kelas', $this->getUserStateFromRequest($this->context.'.filter.kelas', 'filter_kelas', '', 'string'));

        parent::populateState($order, $dir);
    }
}
