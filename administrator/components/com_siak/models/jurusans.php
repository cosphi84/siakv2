<?php

defined('_JEXEC') or exit;

class SiakModelJurusans extends JModelList
{
    public function __construct($config = [])
    {
        if (empty($config['filter_fields'])) {
            $config['filter_fields'] = [
                'published',
                'prodi',
                'j.title',
                'j.prodi',
                'u.name',
            ];
        }
        parent::__construct($config);
    }

    protected function getListQuery()
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select(['j.id', 'p.title as prodi', 'p.alias as program_studi', 'j.title', 'j.alias', 'j.state as published']);
        $query->select('u.name as kajur');

        $query->from($db->qn('#__siak_jurusan', 'j'));
        $query->join('LEFT', $db->qn('#__siak_prodi', 'p').' ON '.$db->qn('p.id').' = '.$db->qn('j.prodi'));
        $query->join('LEFT', $db->qn('#__users', 'u').' ON '.$db->qn('u.id').' = '.$db->qn('j.kajur'));

        $search = $this->getState('filter.search');
        if (!empty($search)) {
            $search = $db->quote('%'.$search.'%');
            $searchs = [];
            $searchs[] = $db->qn('j.title').' LIKE '.$search;
            $searchs[] = $db->qn('j.alias').' LIKE '.$search;
            $query->where('('.implode(' OR ', $searchs).' )');
        }

        $published = $this->getState('filter.published');
        if (is_numeric($published)) {
            $query->where($db->qn('j.state').' = '.(int) $published);
        } elseif ('' === $published) {
            $query->where('(j.state  IN (0,1))');
        }

        $prodi = $this->getState('filter.prodi');
        if (is_numeric($prodi)) {
            $query->where($db->qn('j.prodi').' = '.(int) $prodi);
        }

        //$query->where($db->qn('j.title').' != '.$db->q('Non Jurusan'));

        $orderCol = $this->state->get('list.ordering', 'j.prodi');
        $orderDirn = $this->state->get('list.direction', 'asc');

        $query->order($db->qn($db->escape($orderCol)).' '.$db->escape($orderDirn));

        return $query;
    }

    protected function populateState($ordering = 'j.prodi', $direction = 'DESC')
    {
        $app = JFactory::getApplication('administrator');
        if ($layout = $app->input->get('layout', 'default', 'cmd')) {
            $this->context .= '.'.$layout;
        }
        $format = $app->input->get('format', 'html', 'cmd');
        $params = JComponentHelper::getParams('com_siak');
        $this->setState('filter.search', $this->getUserStateFromRequest($this->context.'.filter.search', 'filter_search', '', 'string'));
        $this->setState('filter.prodi', $this->getUserStateFromRequest($this->context.'.filter.prodi', 'filter_prodi', '', 'string'));
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
        $id .= ':'.$this->getState('filter.prodi');
        $id .= ':'.$this->getState('filter.published');

        return parent::getStoreId($id);
    }
}
