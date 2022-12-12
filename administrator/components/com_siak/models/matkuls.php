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
                'semester',
            ];
        }
        parent::__construct($config);
    }

    protected function getListQuery()
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select([
            'mk.id',
            'mk.title as kode',
            'mk.alias as matkul',
            'mk.sks', 'mk.uang_mk',
            'mk.state as published', ])
            ->from($db->qn('#__siak_matakuliah', 'mk'))
        ;

        $query->select('p.title as prodi')
            ->leftJoin('#__siak_prodi AS p ON p.id=mk.prodi')
        ;

        $query->select('j.title as jurusan')
            ->leftJoin('#__siak_jurusan AS j ON j.id = mk.jurusan')
        ;

        $query->select('jmk.title as tipe_mk')
            ->leftJoin('#__siak_jenis_mk AS jmk ON jmk.id = mk.type')
        ;

        $search = $this->getState('filter.search');
        if (!empty($search)) {
            $search = $db->quote('%'.$search.'%');
            $searchs = [];
            $searchs[] = $db->qn('mk.title').' LIKE '.$search;
            $searchs[] = $db->qn('mk.alias').' LIKE '.$search;
            $query->where('('.implode(' OR ', $searchs).' )');
        }

        $published = $this->getState('filter.published');
        if (is_numeric($published)) {
            $query->where($db->qn('mk.state').' = '.(int) $published);
        } elseif ('' === $published) {
            $query->where($db->qn('mk.state').'  =1');
        }

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

        //$orderDirn = $this->state->get('list.direction', 'ASC');

        $query->order($db->qn('mk.prodi').','.$db->qn('mk.alias'));

        return $query;
    }

    protected function populateState($ordering = 'mk.prodi, mk.alias', $direction = 'ASC')
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
        $this->setState('filter.published', $this->getUserStateFromRequest($this->context.'.filter.published', 'filter_published', '', 'string'));
        $this->setState('filter.semester', $this->getUserStateFromRequest($this->context.'.filter.semester', 'filter_semester', '', 'string'));
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
