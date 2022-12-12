<?php

defined('_JEXEC') or exit;

class SiakModelProdis extends JModelList
{
    public function __construct($config = [])
    {
        if (empty($config['filter_fields'])) {
            $config['filter_fields'] = [
                'published',
                'p.title',
                'p.strata',
                'p.gelar',
                'u.name',
            ];
        }
        parent::__construct($config);
    }

    protected function getListQuery()
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select(['p.id', 'p.title', 'p.alias', 'p.strata', 'p.gelar', 'p.state as published', 'u.name as kaprodi']);
        $query->from($db->qn('#__siak_prodi', 'p'));
        $query->join('LEFT', $db->qn('#__users', 'u').' ON '.$db->qn('u.id').' = '.$db->qn('p.kaprodi'));

        $search = $this->getState('filter.search');
        if (!empty($search)) {
            $search = $db->quote('%'.$search.'%');
            $searchs = [];
            $searchs[] = $db->qn('p.title').' LIKE '.$search;
            $searchs[] = $db->qn('p.alias').' LIKE '.$search;
            $query->where('('.implode(' OR ', $searchs).' )');
        }

        $published = $this->getState('filter.published');
        if (is_numeric($published)) {
            $query->where($db->qn('p.state').' = '.(int) $published);
        } elseif ('' === $published) {
            $query->where('(p.state  IN (0,1))');
        }

        $query->where($db->qn('p.title').' != '.$db->q('Non Prodi'));

        $orderCol = $this->state->get('list.ordering', 'p.id');
        $orderDirn = $this->state->get('list.direction', 'asc');

        $query->order($db->qn($db->escape($orderCol)).' '.$db->escape($orderDirn));

        return $query;
    }

    protected function populateState($ordering = 'p.id', $direction = 'desc')
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

        return parent::getStoreId($id);
    }
}
