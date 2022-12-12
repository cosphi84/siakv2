<?php

defined('_JEXEC') or exit;

class SiakModelWalis extends JModelList
{
    public function __construct($config = [])
    {
        if (empty($config['filter_fields'])) {
            $config['filter_fields'] = [
                'w.jurusan',
                'prodi', 'w.prodi',
                'kelas', 'w.kelas',
                'published', 'w.angkatan',
            ];
        }
        parent::__construct($config);
    }

    protected function getListQuery()
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select(['w.id', 'w.status as published', 'w.angkatan']);
        $query->select('p.title as prodi');
        $query->select('u.name as dosen');
        $query->select('k.title as kelas');

        $query->from($db->qn('#__siak_dosen_wali', 'w'));
        $query->join('LEFT', $db->qn('#__users', 'u').' ON '.$db->qn('u.id').' = '.$db->qn('w.user_id'));
        $query->join('LEFT', $db->qn('#__siak_prodi', 'p').' ON '.$db->qn('p.id').' = '.$db->qn('w.prodi'));
        $query->join('LEFT', $db->qn('#__siak_kelas_mahasiswa', 'k').' ON '.$db->qn('k.id').' = '.$db->qn('w.kelas'));

        $search = $this->getState('filter.search');
        if (!empty($search)) {
            $search = $db->quote('%'.$search.'%');
            $searchs = [];
            $searchs[] = $db->qn('u.name').' LIKE '.$search;
            $searchs[] = $db->qn('w.angkatan').' LIKE '.$search;
            $query->where('('.implode(' OR ', $searchs).' )');
        }

        $published = $this->getState('filter.published');
        if (is_numeric($published)) {
            $query->where($db->qn('w.status').' = '.(int) $published);
        } elseif ('' === $published) {
            $query->where('(w.status  IN (0,1))');
        }

        $prodi = $this->getState('filter.prodi');
        if (is_numeric($prodi)) {
            $query->where($db->qn('w.prodi').' = '.(int) $prodi);
        }

        $kelas = $this->getState('filter.kelas');
        if (is_numeric($kelas)) {
            $query->where($db->qn('w.kelas').' = '.(int) $kelas);
        }

        $orderCol = $this->state->get('list.ordering', 'w.angkatan');
        $orderDirn = $this->state->get('list.direction', ' DESC');

        $query->order($db->qn($db->escape($orderCol)).' '.$db->escape($orderDirn));

        return $query;
    }

    protected function populateState($ordering = 'w.angkatan', $direction = ' DESC')
    {
        $app = JFactory::getApplication('administrator');
        if ($layout = $app->input->get('layout', 'default', 'cmd')) {
            $this->context .= '.'.$layout;
        }
        $format = $app->input->get('format', 'html', 'cmd');
        $params = JComponentHelper::getParams('com_siak');
        $this->setState('filter.search', $this->getUserStateFromRequest($this->context.'.filter.search', 'filter_search', '', 'string'));
        $this->setState('filter.published', $this->getUserStateFromRequest($this->context.'.filter.published', 'filter_published', '', 'string'));
        $this->setState('filter.prodi', $this->getUserStateFromRequest($this->context.'.filter.prodi', 'filter_prodi', '', 'string'));
        $this->setState('filter.kelas', $this->getUserStateFromRequest($this->context.'.filter.kelas', 'filter_kelas', '', 'string'));
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
        $id .= ':'.$this->getState('filter.prodi');
        $id .= ':'.$this->getState('filter.kelas');

        return parent::getStoreId($id);
    }
}
