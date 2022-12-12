<?php

defined('_JEXEC') or die('Direct Access Forbidden!');

class SiakModelDosens extends JModelList
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
        $query->select(['u.id as uid', ' u.name as dosen']);
        $query->select(['su.id', 'su.reset', 'su.nidn', 'su.nik']);
        $query->select('ju.title as jenis_user');

        $query->from($db->qn('#__users', 'u'));
        $query->join('LEFT', $db->qn('#__user_usergroup_map', 'ug').' ON '.$db->qn('ug.user_id').' = '.$db->qn('u.id'));
        $query->join('LEFT', $db->qn('#__siak_user', 'su').' ON '.$db->qn('su.user_id').' = '.$db->qn('u.id'));
        $query->join('LEFT', $db->qn('#__siak_jenis_user', 'ju').' ON '.$db->qn('ju.id').' = '.$db->qn('su.tipe_user'));

        $params = JComponentHelper::getParams('com_siak');
        $grpMahasiswa = $params->get('grpMahasiswa');

        if (!empty($grpMahasiswa)) {
            $query->where($db->qn('ug.group_id').' != '.$db->q($grpMahasiswa));
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
            $query->where($db->qn('su.reset').' = '.(int) $status);
        }

        $query->group($db->qn('u.name'));
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
