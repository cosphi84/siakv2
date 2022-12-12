<?php

defined('_JEXEC') or exit;

class SiakModelPaketmks extends JModelList
{
    public function __construct($config = [])
    {
        if (empty($config['filter_fields'])) {
            $config['filter_fields'] = [
                'published',
                'prodi',
                'jurusan',
                'semester',
                'pk.matakuliah',
            ];
        }
        parent::__construct($config);
    }

    protected function getListQuery()
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select(['pk.id', 'pk.state as published']);
        $query->select('p.title as prodi');
        $query->select('j.title as jurusan');
        $query->select('s.title as semester');
        $query->select(['mk.title as kodeMK', 'mk.alias as namaMK', 'mk.sks']);
        $query->select('jmk.title as jenisMK');

        $query->from($db->qn('#__siak_paket_mk', 'pk'));
        $query->join('LEFT', $db->qn('#__siak_prodi', 'p').' ON '.$db->qn('p.id').' = '.$db->qn('pk.prodi'));
        $query->join('LEFT', $db->qn('#__siak_jurusan', 'j').' ON '.$db->qn('j.id').' = '.$db->qn('pk.jurusan'));
        $query->join('LEFT', $db->qn('#__siak_semester', 's').' ON '.$db->qn('s.id').' = '.$db->qn('pk.semester'));
        $query->join('LEFT', $db->qn('#__siak_matakuliah', 'mk').' ON '.$db->qn('mk.id').' = '.$db->qn('pk.matakuliah'));
        $query->join('LEFT', $db->qn('#__siak_jenis_mk', 'jmk').' ON '.$db->qn('jmk.id').' = '.$db->qn('mk.type'));

        $search = $this->getState('filter.search');
        if (!empty($search)) {
            $search = $db->quote('%'.$search.'%');
            $searchs = [];
            $searchs[] = $db->qn('pk.title').' LIKE '.$search;
            $searchs[] = $db->qn('pk.alias').' LIKE '.$search;
            $query->where('('.implode(' OR ', $searchs).' )');
        }

        $published = $this->getState('filter.published');
        if (is_numeric($published)) {
            $query->where($db->qn('pk.state').' = '.(int) $published);
        } elseif ('' === $published) {
            $query->where('(pk.state  = 1)');
        }

        $prodi = $this->getState('filter.prodi');
        if (is_numeric($prodi)) {
            $query->where($db->qn('pk.prodi').' = '.(int) $prodi);
        }

        $jurusan = $this->getState('filter.jurusan');
        if (is_numeric($jurusan)) {
            $query->where($db->qn('pk.jurusan').' = '.(int) $jurusan);
        }

        $semester = $this->getState('filter.semester');
        if (is_numeric($jurusan)) {
            $query->where($db->qn('pk.semester').' = '.(int) $semester);
        }

        //$orderCol = $this->state->get('list.ordering', 'mk.type');
        //$orderDirn = $this->state->get('list.direction', 'ASC');

        //$query->order($db->qn($db->escape($orderCol)).' '.$db->escape($orderDirn));
        $query->order([$db->qn('pk.semester'), $db->qn('pk.prodi')]);

        return $query;
    }

    protected function populateState($ordering = 'pk.semeser, pk.prodi', $direction = 'ASC')
    {
        $app = JFactory::getApplication('administrator');
        if ($layout = $app->input->get('layout', 'default', 'cmd')) {
            $this->context .= '.'.$layout;
        }
        $format = $app->input->get('format', 'html', 'cmd');
        $params = JComponentHelper::getParams('com_siak');
        $this->setState('filter.search', $this->getUserStateFromRequest($this->context.'.filter.search', 'filter_search', '', 'string'));
        $this->setState('filter.prodi', $this->getUserStateFromRequest($this->context.'.filter.prodi', 'filter_prodi', '', 'string'));
        $this->setState('filter.jurusan', $this->getUserStateFromRequest($this->context.'.filter.jurusan', 'filter_jurusan', '', 'string'));
        $this->setState('filter.jenismk', $this->getUserStateFromRequest($this->context.'.filter.jenismk', 'filter_jenismk', '', 'string'));
        $this->setState('filter.semester', $this->getUserStateFromRequest($this->context.'.filter.semester', 'filter_semester', '', 'string'));
        $this->setState('filter.published', $this->getUserStateFromRequest($this->context.'.filter.published', 'filter_published', '', 'string'));

        $this->setState('params', $params);

        parent::populateState($ordering, $direction);
        if ('html' !== $format) {
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
        $id .= ':'.$this->getState('filter.semester');

        return parent::getStoreId($id);
    }
}
