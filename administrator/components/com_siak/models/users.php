<?php

defined('_JEXEC') or exit('Direct Access Forbidden!');

class SiakModelUsers extends JModelList
{
    public function __construct($config = [])
    {
        if (empty($config['filter_fields'])) {
            $config['filter_fields'] = [
                'published',
                'prodi',
                'm.state',
                'jurusan',
                'u.npm',
                'u.name',
            ];
        }
        parent::__construct($config);
    }

    protected function getListQuery()
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select(['u.id as uid', ' u.name as mahasiswa', 'u.username as npm']);
        $query->select('p.title as prodi');
        $query->select('j.title as jurusan');
        $query->select('k.title as kelas');
        $query->select('ms.id, ms.status');
        $query->select('s.title as semester');

        $query->from($db->qn('#__users', 'u'));
        $query->join('LEFT', $db->qn('#__user_usergroup_map', 'ug').' ON '.$db->qn('ug.user_id').' = '.$db->qn('u.id'));
        $query->join('LEFT', $db->qn('#__siak_mhs_status', 'ms').' ON '.$db->qn('ms.user_id').' = '.$db->qn('u.id'));
        $query->join('LEFT', $db->qn('#__siak_prodi', 'p').' ON '.$db->qn('p.id').' = '.$db->qn('ms.prodi'));
        $query->join('LEFT', $db->qn('#__siak_jurusan', 'j').' ON '.$db->qn('j.id').' = '.$db->qn('ms.jurusan'));
        $query->join('LEFT', $db->qn('#__siak_kelas_mahasiswa', 'k').' ON '.$db->qn('k.id').' = '.$db->qn('ms.kelas'));
        $query->join('LEFT', $db->qn('#__siak_semester', 's').' ON '.$db->qn('s.id').' = '.$db->qn('ms.semester'));

        $params = JComponentHelper::getParams('com_siak');
        $grpMahasiswa = $params->get('grpMahasiswa');

        if (!empty($grpMahasiswa)) {
            $query->where($db->qn('ug.group_id').' = '.$db->q($grpMahasiswa));
        }

        $search = $this->getState('filter.search');
        if (!empty($search)) {
            $search = $db->quote('%'.$search.'%');
            $searchs = [];
            $searchs[] = $db->qn('u.name').' LIKE '.$search;
            $searchs[] = $db->qn('u.username').' LIKE '.$search;
            $query->where('('.implode(' OR ', $searchs).' )');
        }

        $status = $this->getState('filter.published');
        if (is_numeric($status)) {
            $query->where($db->qn('ms.status').' = '.(int) $status);
        } elseif ('' === $status) {
            $query->where('('.implode(' OR ', ['ms.status  IN (-1,0,1)', 'ms.status IS NULL']).' )');
        }

        $orderCol = $this->state->get('list.ordering', 'u.name');
        $orderDirn = $this->state->get('list.direction', ' ASC');

        $query->order($db->qn($db->escape($orderCol)).' '.$db->escape($orderDirn));

        return $query;
    }

    protected function populateState($ordering = 'u.name', $direction = 'ASC')
    {
        $app = JFactory::getApplication('administrator');
        if ($layout = $app->input->get('layout', 'default', 'cmd')) {
            $this->context .= '.'.$layout;
        }
        $format = $app->input->get('format', 'html', 'cmd');
        $params = JComponentHelper::getParams('com_siak');
        $this->setState('filter.search', $this->getUserStateFromRequest($this->context.'.filter.search', 'filter_search', '', 'string'));
        $this->setState('filter.published', $this->getUserStateFromRequest($this->context.'.filter.published', 'filter_published', '', 'string'));
        $this->setState('params', $params);

        parent::populateState($ordering, $direction);

        if ('json' === $format) {
            $this->setState('list.limit', '');
        }
    }

    protected function getStoreId($id = '')
    {
        // Compile the store id.
        $id .= ':'.$this->getState('filter.search');
        $id .= ':'.$this->getState('filter.published');
        $id .= ':'.$this->getState('filter.status');

        return parent::getStoreId($id);
    }
}
