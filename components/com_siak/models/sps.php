<?php

defined('_JEXEC') or exit();

class SiakModelSps extends JModelList
{
    public function __construct($config = [])
    {
        if (empty($config['filter_fields'])) {
            $config['filter_fields'] = [
                'tahun_ajaran',
                'user_id',
                'prodi',
                'jurusan',
            ];
        }
        parent::__construct($config);
    }

    /**
     * getMode. Mengambil mode yang sedang berjalan berdasarkan loged user.
     *
     * @return string Mahasiswa atau nonMahsiswa
     */
    public function getMode()
    {
        $return = 'mahasiswa';
        $return == $this->getState('mode') ? $return : $return = 'nonMahasiswa';

        return $return;
    }

    protected function getListQuery()
    {
        $db = $this->getDbo();
        $query = $db->getQuery(true);
        $mode = $this->getState('mode');

        $query->select(['r.id', 'r.dosen as dosid', 'r.tanggal_daftar', 'r.state'])
            ->from($db->qn('#__siak_sp', 'r'))
        ;

        $query->select(['u.name AS mahasiswa', 'u.username AS npm'])
            ->leftJoin('#__users AS u ON u.id = r.user_id')
        ;

        $query->select('n.matakuliah AS mk')
            ->leftJoin('#__siak_nilai AS n ON n.id=r.nilai_id')
        ;

        $query->select(['m.title AS kodeMK', 'm.alias AS matakuliah'])
            ->leftJoin('#__siak_matakuliah AS m ON m.id=n.matakuliah')
        ;

        $query->select('k.alias AS kelas')
            ->leftJoin('#__siak_kelas_mahasiswa AS k ON k.id=r.kelas')
        ;

        $query->select('j.alias AS konsentrasi')
            ->leftJoin('#__siak_jurusan AS j ON j.id=r.jurusan')
        ;

        $query->select('d.name as dosen')
            ->leftJoin('#__users as d ON d.id=r.dosen')
        ;

        if ('mahasiswa' == $mode) {
            $query->where($db->qn('r.user_id').' = '.(int) $this->getState('user_id'));
        } else {
            $query->where($db->qn('r.dosen').' = '.JFactory::getUser()->id);
            $query->where($db->qn('r.state').' = 1');
        }

        $tahun_ajaran = $this->getState('filter.tahun_ajaran');
        if (!empty($tahun_ajaran)) {
            $query->where($db->qn('r.tahun_ajaran').' = '.$db->q($tahun_ajaran));
        }

        $mkid = $this->getState('filter.search');
        if (!empty($mkid)) {
            $query->where($db->qn('n.matakuliah').' = '.(int) $mkid);
        }

        $orderCol = $this->state->get('list.ordering', 'u.username');
        $orderDirn = $this->state->get('list.direction', 'ASC');

        $query->order($db->escape($orderCol).' '.$db->escape($orderDirn));

        return $query;
    }

    protected function populateState($order = 'u.username', $orderin = 'ASC')
    {
        $user = JFactory::getUser();
        $params = JComponentHelper::getParams('com_siak');

        if (in_array($params->get('grpMahasiswa'), $user->get('groups'))) {
            $this->setState('mode', 'mahasiswa');
            $this->setState('user_id', $user->id);
        } else {
            $this->setState('mode', 'nonMahasiswa');
        }

        $this->setState('filter.tahun_ajaran', $this->getUserStateFromRequest($this->context.'.filter.ta', 'filter_ta', '', 'string'));
        $this->setState('filter.search', $this->getUserStateFromRequest($this->context.'.filter.search', 'filter_search', '', 'string'));
        parent::populateState($order, $orderin);
    }
}
