<?php

defined('_JEXEC') or die;

class SiakModelPraktikums extends JModelList
{
    public function __construct($config = [])
    {
        if (empty($config['filter_fields'])) {
            $config['filter_fields'] = [
                'tahun_akademik',
                'published',
                'u.username',
                'prodi',
            ];
        }

        parent::__construct($config);
    }

    protected function getListQuery()
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $matakuliah = $this->getState('filter.search');
        $prodi = $this->getState('filter.prodi');
        $ta = $this->getState('filter.tahun_ajaran');

        $query->select(['p.id', 'p.status', 'p.ta', 'p.create_date'])
            ->from($db->qn('#__siak_praktikum', 'p'))
        ;

        if (!empty($matakuliah)) {
            $query->where('p.matakuliah = '.(int) $matakuliah);
        }

        if (!empty($ta)) {
            $query->where('p.ta = '.$db->q($ta));
        }

        if (!empty($prodi)) {
            $query->where('p.prodi = '.(int) $prodi);
        }

        $query->select(['u.name AS mahasiswa', 'u.username AS npm'])
            ->leftJoin('#__users AS u ON u.id = p.user_id')
        ;
        $query->select('j.alias AS jurusan')
            ->leftJoin('#__siak_jurusan AS j ON j.id = p.jurusan')
        ;
        $query->select('pr.title AS prodi')
            ->leftJoin('#__siak_prodi AS pr ON pr.id = p.prodi')
        ;
        $query->select('k.title AS kelas')
            ->leftJoin('#__siak_kelas_mahasiswa AS k ON k.id = p.kelas')
        ;
        $query->select(['b.lunas', 'b.tanggal_bayar'])
            ->leftJoin('#__siak_pembayaran AS b ON ( '.
                'b.user_id = p.user_id AND '.
                'b.pembayaran = '.$db->q('PRAKTIKUM').' AND '.
                'b.ta = '.$db->q($ta).' AND '.
                'b.matakuliah = p.matakuliah)')
        ;

        $orderCol = $this->state->get('list.ordering', 'u.username');
        $orderDirn = $this->state->get('list.direction', 'ASC');

        $query->order($db->qn($db->escape($orderCol)).' '.$db->escape($orderDirn));

        return $query;
    }

    protected function populateState($order = 'u.username', $dir = 'ASC')
    {
        $input = JFactory::getApplication()->input;
        $format = $input->get('format', 'html', 'cmd');

        $this->setState('filter.tahun_ajaran', $this->getUserStateFromRequest($this->context.'.filter.tahun_akademik', 'filter_tahun_akademik', '', 'string'));
        $this->setState('filter.prodi', $this->getUserStateFromRequest($this->context.'.filter.prodi', 'filter_prodi', '', 'string'));

        parent::populateState($order, $dir);

        if ('html' != $format) {
            $this->setState('list.limit', '');
        }
    }
}
