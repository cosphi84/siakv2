<?php

defined('_JEXEC') or exit;

class SiakModelRombels extends JModelList
{
    public function __construct($config)
    {
        if (empty($config['filter_fields'])) {
            $config['filter_fields'] = [
                'prodi', 'jurusan', 'kelas', 'published', 'ta',
                'u.name',
            ];
        }

        parent::__construct($config);
    }

    public function getListQuery()
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $search = $this->getState('filter.search');
        $prodi = $this->getState('filter.prodi');
        $jurusan = $this->getState('filter.jurusan');
        $kelas = $this->getState('filter.kelas');
        $status = $this->getState('filter.status');
        $ta = $this->getState('filter.ta');
        $orderCol = $this->getState('list.ordering', 'u.username');
        $orderDirn = $this->getState('list.direction', 'asc');

        $query->select(['n.id', 'n.state', 'n.tahun_ajaran AS ta'])
            ->from($db->qn('#__siak_nilai', 'n'))
        ;
        $query->where($db->qn('n.matakuliah').' = '.(int) $search);
        if (is_numeric($prodi)) {
            $query->where($db->qn('n.prodi').' = '.(int) $prodi);
        }
        if (is_numeric($jurusan)) {
            $query->where($db->qn('n.jurusan').' = '.(int) $jurusan);
        }
        if (!empty($ta)) {
            $query->where($db->qn('n.tahun_ajaran').' = '.$db->q($ta));
        }
        if (is_numeric($status)) {
            $query->where($db->qn('n.state').' = '.(int) $status);
        } else {
            $query->where($db->qn('n.state').' IN (0,1)');
        }
        if (is_numeric($kelas)) {
            $query->where($db->qn('n.kelas').' = '.(int) $kelas);
        }

        $query->select(['mk.title as kodeMK', 'mk.alias AS MK'])
            ->join('LEFT', $db->qn('#__siak_matakuliah', 'mk').' ON mk.id = n.matakuliah')
        ;

        $query->select('p.alias AS prodi')
            ->join('LEFT', $db->qn('#__siak_prodi', 'p').' ON p.id=n.prodi')
        ;

        $query->select('j.alias AS jurusan')
            ->join('LEFT', $db->qn('#__siak_jurusan', 'j').' ON j.id=n.jurusan')
        ;

        $query->select('k.title AS kelas')
            ->join('LEFT', $db->qn('#__siak_kelas_mahasiswa', 'k').' ON k.id=n.kelas')
        ;

        $query->select(['u.name as mahasiswa', 'u.username as npm'])
            ->join('LEFT', $db->qn('#__users', 'u').' ON u.id=n.user_id')
        ;

        $query->order($db->qn($db->escape($orderCol)).' '.$db->escape($orderDirn));

        return $query;
    }

    protected function populateState($order = 'u.username', $dir = 'ASC')
    {
        $format = \JFactory::getApplication()->input->get('format', 'html', 'cmd');

        $this->setState('filter.prodi', $this->getUserStateFromRequest($this->context.'.filter.prodi', 'filter_prodi', '', 'string'));
        $this->setState('filter.jurusan', $this->getUserStateFromRequest($this->context.'.filter.jurusan', 'filter_jurusan', '', 'string'));
        $this->setState('filter.kelas', $this->getUserStateFromRequest($this->context.'.filter.kelas', 'filter_kelas', '', 'string'));
        $this->setState('filter.status', $this->getUserStateFromRequest($this->context.'.filter.status', 'filter_status', '', 'string'));
        $this->setState('filter.ta', $this->getUserStateFromRequest($this->context.'.filter.ta', 'filter_ta', '', 'string'));

        // @var ActivityRepository
        parent::populateState($order, $dir);

        if ('html' != $format) {
            $this->setState('list.limit', null);
        }
    }
}
