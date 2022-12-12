<?php

defined('_JEXEC') or exit;

class SiakModelMatkuls extends JModelList
{
    public function __construct($config = [])
    {
        if (empty($config['filter_fields'])) {
            $config['filter_fields'] = [
                'published',
                'prodi',
                'jurusan',
                'jenismk',
                'mk.title',
                'mk.alias',
                'mk.prodi',
                'mk.jurusan',
                'mk.sks',
            ];
        }
        parent::__construct($config);
    }

    protected function getListQuery()
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select(['mk.id', 'mk.title as kode', 'mk.alias as matkul', 'mk.sks', 'mk.uang_mk', 'mk.state as published']);
        $query->select(['p.title as prodi', 'j.title as jurusan', 'jmk.title as tipe_mk']);

        $query->from($db->qn('#__siak_matakuliah', 'mk'));
        $query->join('LEFT', $db->qn('#__siak_prodi', 'p').' ON '.$db->qn('p.id').' = '.$db->qn('mk.prodi'));
        $query->join('LEFT', $db->qn('#__siak_jurusan', 'j').' ON '.$db->qn('j.id').' = '.$db->qn('mk.jurusan'));
        $query->join('LEFT', $db->qn('#__siak_jenis_mk', 'jmk').' ON '.$db->qn('jmk.id').' = '.$db->qn('mk.type'));

        $search = $this->getState('filter.search');
        if (!empty($search)) {
            $search = $db->quote('%'.$search.'%');
            $searchs = [];
            $searchs[] = $db->qn('mk.title').' LIKE '.$search;
            $searchs[] = $db->qn('mk.alias').' LIKE '.$search;
            $query->where('('.implode(' OR ', $searchs).' )');
        }

        $query->where('(mk.state  =  1)');

        $prodi = $this->getState('filter.prodi');
        if (is_numeric($prodi)) {
            $query->where($db->qn('mk.prodi').' = '.(int) $prodi);
        }

        $jurusan = $this->getState('filter.jurusan');
        if (is_numeric($jurusan)) {
            $query->where($db->qn('mk.jurusan').' = '.(int) $jurusan);
        }

        $type = $this->getState('filter.jenismk');
        if (is_numeric($type)) {
            $query->where($db->qn('mk.type').' = '.(int) $type);
        }

        $orderCol = $this->state->get('list.ordering', 'mk.type');
        $orderDirn = $this->state->get('list.direction', 'ASC');

        $query->order($db->qn($db->escape($orderCol)).' '.$db->escape($orderDirn));

        return $query;
    }

    protected function populateState($ordering = 'mk.type', $direction = 'ASC')
    {
        $app = JFactory::getApplication();
        if ($layout = $app->input->get('layout', 'default', 'cmd')) {
            $this->context .= '.'.$layout;
        }
        $format = $app->input->get('format', 'html', 'cmd');
        $params = JComponentHelper::getParams('com_siak');
        $this->setState('filter.search', $this->getUserStateFromRequest($this->context.'.filter.search', 'filter_search', '', 'string'));
        $this->setState('filter.prodi', $this->getUserStateFromRequest($this->context.'.filter.prodi', 'filter_prodi', '', 'string'));
        $this->setState('filter.jurusan', $this->getUserStateFromRequest($this->context.'.filter.jurusan', 'filter_jurusan', '', 'string'));
        $this->setState('filter.jenismk', $this->getUserStateFromRequest($this->context.'.filter.jenismk', 'filter_jenismk', '', 'string'));
        $this->setState('filter.published', $this->getUserStateFromRequest($this->context.'.filter.published', 'filter_published', '', 'string'));
        $this->setState('params', $params);

        parent::populateState($ordering, $direction);

        if ('json' === $format || 'xlsx' === $format) {
            $this->setState('list.limit', '');
        }
    }

    protected function getStoreId($id = '')
    {
        // Compile the store id.
        $id .= ':'.$this->getState('filter.search');
        $id .= ':'.$this->getState('filter.prodi');
        $id .= ':'.$this->getState('filter.jurusan');
        $id .= ':'.$this->getState('filter.jenismk');
        $id .= ':'.$this->getState('filter.published');

        return parent::getStoreId($id);
    }
}
