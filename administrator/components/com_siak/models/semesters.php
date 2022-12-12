<?php

defined('_JEXEC') or exit;

class SiakModelSemesters extends JModelList
{
    public function __construct($config = [])
    {
        if (empty($config['filter_fields'])) {
            $config['filter_fields'] = [
                'state',
                'prodi', 'jurusan', 'kelas',
                's.title',
            ];
        }
        parent::__construct($config);
    }

    /**
     * getPaketMK.
     *
     * @param int $semID ID semesters yang ingin di cek pake MK nya
     *
     * @return array Object Paket MK (jika ada)
     */
    public function getPaketMK()
    {
        $id = JFactory::getApplication()->input->get('id', '0', 'int');
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('pm.id')
            ->from($db->qn('#__siak_paket_mk', 'pm'))
        ;
        $query->select(['m.title as kodeMK', 'm.alias as MK', 'm.sks'])
            ->join('LEFT', $db->qn('#__siak_matakuliah', 'm').' ON m.id=pm.matakuliah')
        ;
        $query->where($db->qn('pm.semester').' = '.(int) $id);

        $db->setQuery($query);

        try {
            $result = $db->loadObjectList();
        } catch (RuntimeException $e) {
            $this->setError($e->getMessage());

            return false;
        }

        return $result;
    }

    protected function getListQuery()
    {
        $canDo = JHelperContent::getActions('com_siak');
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select(['s.id', 's.title', 's.alias', 's.totalSKS', 's.uangSKS', 's.uangSPP', 's.state as published']);
        //$query->select(['s.prodi as p', 's.jurusan as j']);
        $query->select('p.alias as prodi');
        $query->select('j.alias as jurusan');
        $query->select('k.title as kelas');

        $query->from($db->qn('#__siak_semester', 's'));
        $query->join('LEFT', $db->qn('#__siak_prodi', 'p').' ON '.$db->qn('p.id').' = '.$db->qn('s.prodi'));
        $query->join('LEFT', $db->qn('#__siak_jurusan', 'j').' ON '.$db->qn('j.id').' = '.$db->qn('s.jurusan'));
        $query->join('LEFT', $db->qn('#__siak_kelas_mahasiswa', 'k').' ON '.$db->qn('k.id').' = '.$db->qn('s.kelas'));

        $search = $this->getState('filter.search');
        if (!empty($search)) {
            $search = $db->quote('%'.$search.'%');
            $searchs = [];
            $searchs[] = $db->qn('s.title').' LIKE '.$search;
            $searchs[] = $db->qn('s.alias').' LIKE '.$search;
            $query->where('('.implode(' OR ', $searchs).' )');
        }
        $prodi = $this->getState('filter.prodi', '0');
        $query->where($db->qn('s.prodi').' = '.(int) $prodi);

        $jurusan = $this->getState('filter.jurusan');
        if (is_numeric($jurusan)) {
            $query->where($db->qn('s.jurusan').' = '.(int) $jurusan);
        }

        $kelas = $this->getState('filter.kelas');
        if (is_numeric($kelas)) {
            $query->where($db->qn('s.jurusan').' = '.(int) $jurusan);
        }

        $published = $this->getState('filter.published');
        if (is_numeric($published)) {
            $query->where($db->qn('s.state').' = '.(int) $published);
        } else {
            $query->where('(s.state  IN (0,1))');
        }

        $orderCol = $this->state->get('list.ordering', 's.title');
        $orderDirn = $this->state->get('list.direction', 'ASC');

        $query->order($db->qn($db->escape($orderCol)).' '.$db->escape($orderDirn));

        return $query;
    }

    protected function populateState($ordering = 's.title', $direction = 'ASC')
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
        $this->setState('filter.jurusan', $this->getUserStateFromRequest($this->context.'.filter.jurusan', 'filter_jurusan', '', 'string'));
        $this->setState('filter.kelas', $this->getUserStateFromRequest($this->context.'.filter_kelas', 'filter_kelas', '', 'string'));

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
        $id .= ':'.$this->getState('filter.jurusan');
        $id .= ':'.$this->getState('filter.prodi');
        $id .= ':'.$this->getState('filter.kelas');
        $id .= ':'.$this->getState('filter.published');

        return parent::getStoreId($id);
    }
}
