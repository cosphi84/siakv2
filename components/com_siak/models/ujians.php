<?php

defined('_JEXEC') or exit;

class SiakModelUjians extends JModelList
{
    public function __construct($config)
    {
        if (empty($config['filter_fields'])) {
            $config['filter_fields'] = [
                'semester',
                'ta',
                'prodi',
                'jurusan',
                'kelas',
            ];
        }
        parent::__construct($config);
    }

    protected function getListQuery()
    {
        $db = $this->getDbo();
        $query = $db->getQuery(true);
        $search = $this->getState('filter.search');
        $prodi = $this->getState('filter.prodi');
        $jurusan = $this->getState('filter.jurusan');
        $semester = $this->getState('filter.semester');
        $kelas = $this->getState('filter.kelas');
        $tah = $this->getState('filter.tah');

        $query->select(['j.id', 'j.tanggal', 'DATE_FORMAT(j.tanggal, \'%W\') AS hari', 'j.jam_mulai', 'j.jam_akhir', 'j.tahun_ajaran'])
            ->from($db->qn('#__siak_jadwal_ujian', 'j'))
        ;

        $query->select('p.title AS prodi')->leftJoin('#__siak_prodi AS p ON p.id = j.prodi');
        $query->select('jk.title AS konsentrasi')->leftJoin('#__siak_jurusan AS jk ON jk.id = j.jurusan');
        $query->select('s.title AS semester')->leftJoin('#__siak_semester AS s ON s.id = j.semester');
        $query->select('k.title AS kelas')->leftJoin('#__siak_kelas_mahasiswa AS k ON k.id = j.kelas');
        $query->select(['m.title AS kodemk', 'm.alias AS matakuliah'])
            ->leftJoin('#__siak_matakuliah AS m ON m.id = j.matakuliah')
        ;

        $query->select('r.title AS ruangan')
            ->leftJoin('#__siak_ruangan AS r ON r.id = j.ruangan')
        ;

        $query->select('u.name AS dosen')
            ->leftJoin('#__users AS u ON u.id = j.dosen')
        ;

        $query->select('a.name AS pengawas')
            ->leftJoin('#__users AS a ON a.id = j.pengawas')
        ;

        $query->where($db->qn('j.prodi').' = '.(int) $prodi);
        !empty($jurusan) ? $query->where($db->qn('j.jurusan').' = '.(int) $jurusan) : $query;
        !empty($kelas) ? $query->where($db->qn('j.kelas').' = '.(int) $kelas) : $query;
        !empty($semester) ? $query->where($db->qn('j.semester').' = '.(int) $semester) : $query;
        $query->where($db->qn('j.tahun_ajaran').' = '.$db->q($tah));
        $query->where($db->qn('j.state').' = 1');

        $orderCol = $this->state->get('list.ordering', 'j.tanggal');
        $orderDirn = $this->state->get('list.direction', 'ASC');

        $query->order($db->escape($orderCol).' '.$db->escape($orderDirn));

        return $query;
    }

    protected function populateState($orderby = 'j.tanggal', $ordering = 'ASC')
    {
        $ta = SiakHelper::getTA();
        $this->setState('filter.tah', $ta);
        $this->setState('filter.prodi', $this->getUserStateFromRequest($this->context.'.filter.prodi', 'filter_prodi', '', 'string'));
        $this->setState('filter.jurusan', $this->getUserStatefromRequest($this->context.'.filter.jurusan', 'filter_jurusan', '', 'string'));
        $this->setState('filter.semester', $this->getUserStateFromRequest($this->context.'.filter.semester', 'filter_semester', '', 'string'));
        $this->setState('filter.kelas', $this->getUserStateFromRequest($this->context.'.filter.kelas', 'filer_kelas', '', 'string'));

        parent::populateState($orderby, $ordering);
    }
}
