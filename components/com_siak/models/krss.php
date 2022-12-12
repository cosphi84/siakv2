<?php

defined('_JEXEC') or exit;

class SiakModelKrss extends JModelList
{
    public function __construct($cfg = [])
    {
        if (empty($cfg['filter_fields'])) {
            $cfg['filter_fields'] = [
                'confirm_dw',
                'semester',
            ];
        }

        parent::__construct($cfg);
    }

    protected function getListQuery()
    {
        $db = JFactory::getDbo();
        $app = JFactory::getApplication();
        $query = $db->getQuery(true);
        $user = Jfactory::getUser();
        $params = JComponentHelper::getParams('com_siak');
        $grpMhs = $params->get('grpMahasiswa');

        in_array($grpMhs, $user->get('groups')) ? $isMahasiwa = true : $isMahasiwa = false;
        if ($isMahasiwa) {
            $biodata = SiakHelper::loadBiodata($user->id);
        }

        $query = $db->getQuery(true);
        $query->select(['k.id', 'k.user_id', 'k.ttl_sks', 'k.confirm_dw as statusKRS', 'k.created_time', 'k.confirm_note'])
            ->from($db->qn('#__siak_krs', 'k'))
        ;

        $query->select(['u.name as mahasiswa', 'u.username as npm'])
            ->join('LEFT', $db->qn('#__users', 'u').' ON u.id = k.user_id')
        ;

        $query->select('s.title as semester')
            ->join('LEFT', $db->qn('#__siak_semester', 's').' ON s.id = k.semester')
        ;

        $query->select('dw.user_id as dosenwaliid')
            ->join('LEFT', $db->qn('#__siak_dosen_wali', 'dw').' ON dw.id = k.dosen_wali')
        ;

        $query->select('usr.name as dosenwali')
            ->join('LEFT', $db->qn('#__users', 'usr').' ON usr.id = dw.user_id')
        ;

        if ($isMahasiwa) {
            $query->where($db->qn('k.user_id').' = '.$db->q($user->id));
        } else {
            $query->where($db->qn('dw.user_id').' = '.$db->q($user->id));
        }

        $confirm = $this->getState('filter.confirm_dw');
        if (!$isMahasiwa || is_numeric($confirm)) {
            $query->where($db->qn('k.confirm_dw').' = '.(int) $confirm);
            $query->order($db->qn('s.title').' ASC');
        } else {
            $query->order($db->qn('k.created_time').' ASC');
        }

        return $query;
    }

    protected function populateState($order = null, $dir = null)
    {
        $confirmDW = $this->getUserStateFromRequest($this->context.'.filter.confirm_dw', 'filter_confirm_dw', '', 'string');
        $search = $this->getUserStateFromRequest($this->context.'.filter.search', 'filter_search', '', 'string');

        $this->setState('filter.confirm_dw', $confirmDW);
        $this->setState('filter.search', $search);
        parent::populateState($order, $dir);
    }
}
