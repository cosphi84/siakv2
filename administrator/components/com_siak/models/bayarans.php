<?php

defined('_JEXEC') or exit;

class SiakModelBayarans extends JModelList
{
    public function __construct($cfg = [])
    {
        if (empty($cfg['filter_fields'])) {
            $cfg['filter_fields'] = [
                'ta',
                'pembayaran',
                'lunas',
                'confirm',
                'u.name',
                'semester',
            ];
        }

        parent::__construct($cfg);
    }

    protected function getListQuery()
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $ta = $this->getState('filter.ta');
        $pembayaran = $this->getState('filter.pembayaran');
        $lunas = $this->getState('filter.lunas');
        $confirm = $this->getState('filter.confirm');
        $search = $this->getState('filter.search');
        $semester = $this->getState('filter.semester');

        $query->select(['p.id', 'p.no_ref', 'p.pembayaran', 'p.tipe_bayar', 'p.tanggal_bayar', 'p.jumlah', 'p.ta', 'p.kuitansi', 'p.lunas', 'p.confirm', 'p.confirm_time', 'p.confirm_note'])
            ->from($db->qn('#__siak_pembayaran', 'p'))
        ;

        if (!empty($pembayaran)) {
            $query->where($db->qn('p.pembayaran').' = '.$db->q($pembayaran));
        }

        if (!empty($semester)) {
            $query->where($db->qn('p.semester').' = '.(int) $semester);
        }

        if (!empty($lunas)) {
            $query->where($db->qn('p.lunas').' = '.(int) $lunas);
        }

        if (!empty($confirm)) {
            $query->where($db->qn('p.confirm').' = '.(int) $confirm);
        }

        if (!empty($search)) {
            $search = $db->q('%'.$search.'%');
            $searchs = [
                $db->qn('u.name').' LIKE '.$search,
                $db->qn('u.username').' LIKE '.$search,
            ];

            $query->where('('.implode(' OR ', $searchs).')');
        } else {
            $query->where($db->qn('p.ta').' = '.$db->q($ta));
        }

        $query->select(['u.name as mahasiswa', 'u.username as npm'])
            ->leftJoin('#__users AS u ON u.id=p.user_id')
        ;

        $query->select('us.name AS confirm_user')
            ->leftJoin('#__users AS us ON us.id=p.confirm_user')
        ;

        $query->select(['m.title AS kodemk', 'm.alias AS mk'])
            ->leftJoin('#__siak_matakuliah AS m ON m.id=p.matakuliah')
        ;

        $query->select('s.title AS semester')
            ->leftJoin('#__siak_semester AS s ON s.id=p.semester')
        ;

        $orderCol = $this->state->get('list.ordering', 'u.name');
        $orderDirn = $this->state->get('list.direction', ' ASC');

        $query->order($db->qn($db->escape($orderCol)).' '.$db->escape($orderDirn));

        return $query;
    }

    protected function populateState($ordering = 'u.name', $direction = 'ASC')
    {
        $app = JFactory::getApplication('administrator');
        $format = $app->input->get('format', 'html', 'cmd');

        $this->setState('filter.ta', $this->getUserStateFromRequest($this->context.'.filter.ta', 'filter_ta', '', 'string'));
        $this->setState('filter.pembayaran', $this->getUserStateFromRequest($this->context.'.filter.pembayaran', 'filter_pembayaran', '', 'string'));
        $this->setState('filter.lunas', $this->getUserStateFromRequest($this->context.'.filter.lunas', 'filter_lunas', '', 'string'));
        $this->setState('filter.confirm', $this->getUserStateFromRequest($this->context.'.filter.confirm', 'filter_confirm', '', 'string'));
        $this->setState('filter.semester', $this->getUserStateFromRequest($this->context.'.filter.semester', 'filter_semester', '', 'string'));
        parent::populateState($ordering, $direction);

        if ('html' !== $format) {
            $this->setState('list.limit', '');
        }
    }
}
