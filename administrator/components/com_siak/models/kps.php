<?php

defined('_JEXEC') or exit();

JLoader::register('Siak', JPATH_COMPONENT_ADMINISTRATOR.'/helpers/siak.php');

class SiakModelKps extends JModelList
{
    public function __construct($cfg)
    {
        if (empty($cfg['filter_fields'])) {
            $cfg['filter_fields'] = [
                'prodi',
                'jurusan',
                'ta',
                'state',
                'u.name',
            ];
        }

        parent::__construct($cfg);
    }

    protected function getListQuery()
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);

        $search = $this->getState('filter.search');
        $prodi = $this->getState('filter.prodi');
        $jurusan = $this->getState('filter.jurusan');
        $published = $this->getState('filter.published');
        $ta = $this->getState('filter.ta');

        empty($ta) ? $ta = Siak::getTA() : $ta;

        $query->select(['kp.id', 'kp.tahun_ajaran AS ta', 'kp.no_surat', 'kp.tanggal_mulai AS start', 'kp.tanggal_selesai AS finish', 'kp.judul_laporan', 'kp.tanggal_daftar', 'kp.state', 'kp.tanggal_seminar', 'kp.file_laporan'])
            ->from($db->qn('#__siak_kp', 'kp'))
        ;

        $query->where($db->qn('kp.prodi').' = '.(int) $prodi);

        if (!empty($jurusan)) {
            $query->where($db->qn('kp.jurusan').' = '.(int) $jurusan);
        }

        if (!empty($ta)) {
            $query->where($db->qn('kp.tahun_ajaran').' = '.$db->q($ta));
        }

        if (!empty($published)) {
            $query->where($db->qn('kp.state').' = '.(int) $published);
        } else {
            $query->where($db->qn('kp.state').' IN (0,1)');
        }

        $query->select(['u.name AS mahasiswa', 'u.username AS npm'])
            ->leftJoin('#__users AS u ON u.id = kp.user_id')
        ;

        if (!empty($search)) {
            $search = $db->q('%'.$search.'%');
            $searchs = [
                $db.qn('u.name').' LIKE '.$search,
                $db.qn('u.username').' LIKE '.$search,
            ];
            $query->where('('.implode(' OR ', $searchs).')');
        }

        $query->select('p.alias AS prodi')
            ->leftJoin('#__siak_prodi AS p ON p.id = kp.prodi')
        ;

        $query->select('j.title AS jurusan')
            ->leftJoin('#__siak_jurusan AS j ON j.id = kp.jurusan')
        ;

        $query->select('k.title AS kelas')
            ->leftJoin('#__siak_kelas_mahasiswa AS k ON k.id=kp.kelas')
        ;

        $query->select(['i.nama AS tempatKP', 'i.alamat', 'i.kabupaten', 'i.pic', 'i.telepon_pic'])
            ->leftJoin('#__siak_industri AS i ON i.id = kp.instansi')
        ;

        $query->select('us.name as dosbing')
            ->leftJoin('#__users AS us ON us.id = kp.dosbing')
        ;

        $orderCol = $this->state->get('list.ordering', 'u.username');
        $orderDirn = $this->state->get('list.direction', 'asc');

        $query->order($db->escape($orderCol).' '.$db->escape($orderDirn));

        return $query;
    }

    protected function populateState($order = 'u.username', $dir = 'asc')
    {
        $this->setState('filter.prodi', $this->getUserStateFromRequest($this->context.'.filter.prodi', 'filter_prodi', '', 'string'));
        $this->setState('filter.jurusuan', $this->getUserStateFromRequest($this->context.'.filter_jurusan', '', 'string'));
        $this->setState('filter.ta', $this->getUserStateFromRequest($this->context.'.filter_ta', 'filter_ta', '', 'string'));

        $format = \JFactory::getApplication()->input->get('format', 'html', 'cmd');

        parent::populateState($order, $dir);
        if ('html' !== $format) {
            $this->setState('list.limit', '');
        }
    }
}
