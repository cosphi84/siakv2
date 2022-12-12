<?php

defined('_JEXEC') or exit;

JLoader::register('Siak', JPATH_COMPONENT_ADMINISTRATOR.'/helpers/siak.php');

class SiakModelKrss extends JModelList
{
    public function __construct($cfg = [])
    {
        if (empty($cfg['filter_fields'])) {
            $cfg['filter_fields'] = [
                'confirm_dw',
                'semester',
                'prodi',
                'jurusan',
                'ta',
            ];
        }

        parent::__construct($cfg);
    }

    protected function getListQuery()
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $prodi = $this->getState('filter.prodi');
        $ta = $this->getState('filter.ta');
        $jurusuan = $this->getState('filter.jurusan');
        $semester = $this->getState('filter.semester');
        $confirm = $this->getState('filter.confirm_dw');
        $npm = $this->getState('filter.search');

        $query->select(['k.id', 'k.user_id', 'k.ttl_sks', 'k.tahun_ajaran', 'k.confirm_dw as statusKRS', 'k.created_time', 'k.confirm_note'])
            ->from($db->qn('#__siak_krs', 'k'))
        ;

        $query->select(['u.name as mahasiswa', 'u.username as npm'])
            ->join('LEFT', $db->qn('#__users', 'u').' ON u.id=k.user_id')
        ;

        if (!empty($npm)) {
            $query->where($db->qn('u.username').' = '.$db->q($npm));
        } else {
            $query->where($db->qn('k.prodi').' = '.(int) $prodi);
            if (!empty($ta)) {
                $query->where($db->qn('k.tahun_ajaran').' = '.$db->q($ta));
            } else {
                $query->where($db->qn('k.tahun_ajaran').' = '.$db->q(Siak::getTA()));
            }
        }

        if (!empty($jurusuan)) {
            $query->where($db->qn('k.jurusan').' = '.(int) $jurusuan);
        }

        if (!empty($semester)) {
            $query->where($db->qn('k.semester').' = '.(int) $semester);
        }

        $query->select('s.title as semester')
            ->join('LEFT', $db->qn('#__siak_semester', 's').' ON s.id = k.semester')
        ;

        $query->select('dw.user_id as dosenwaliid')
            ->join('LEFT', $db->qn('#__siak_dosen_wali', 'dw').' ON dw.id = k.dosen_wali')
        ;

        $query->select('usr.name as dosenwali')
            ->join('LEFT', $db->qn('#__users', 'usr').' ON usr.id = dw.user_id')
        ;

        $query->select('p.alias as prodi')
            ->join('LEFT', $db->qn('#__siak_prodi', 'p').' ON p.id=k.prodi')
        ;

        $query->select('j.alias as jurusan')
            ->join('LEFT', $db->qn('#__siak_jurusan', 'j').' ON j.id = k.jurusan')
        ;

        if (is_numeric($confirm)) {
            $query->where($db->qn('k.confirm_dw').' = '.(int) $confirm);
        } else {
            $query->where($db->qn('k.confirm_dw').' IN (-2, 2)');
        }

        $query->order($db->qn('p.title').','.$db->qn('s.title').','.$db->qn('u.username'));

        return $query;
    }

    protected function populateState($order = 'p.title,s.title,u.username', $dir = null)
    {
        $confirmDW = $this->getUserStateFromRequest($this->context.'.filter.confirm_dw', 'filter_confirm_dw', '', 'string');
        $this->setState('filter.confirm_dw', $confirmDW);

        $this->setState('filter.prodi', $this->getUserStateFromRequest($this->context.'.filter.prodi', 'filter_prodi', '', 'string'));
        $this->setState('filter.jurusuan', $this->getUserStateFromRequest($this->context.'.filter_jurusan', '', 'string'));
        $this->setState('filter.ta', $this->getUserStateFromRequest($this->context.'.filter_ta', 'filter_ta', '', 'string'));
        $this->setState('filter.semester', $this->getUserStateFromRequest($this->context.'.filter.semester', 'filter_semester', '', 'string'));
        $format = \JFactory::getApplication()->input->get('format', 'html', 'cmd');

        parent::populateState($order, $dir);
        if ('html' !== $format) {
            $this->setState('list.limit', '');
        }
    }
}
