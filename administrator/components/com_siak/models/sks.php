<?php

defined('_JEXEC') or exit;

class SiakModelSks extends JModelList
{
    public function __construct($config = [])
    {
        if (empty($config['filter_fields'])) {
            $config['filter_fields'] = [
                'published',
                'p.title',
            ];
        }
        parent::__construct($config);
    }

    protected function getListQuery()
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select(['a.id', 'a.title', 'a.alias', 'a.note', 'a.access', 'a.file', 'a.state as published']);
        $query->from($db->qn('#__siak_sk', 'a'));
        $query->select('v.title AS access_level')
            ->join('LEFT', '#__viewlevels AS v ON v.id = a.access')
        ;

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
            $query->where($db->qn('state').' = '.(int) $published);
        } elseif ('' === $published) {
            $query->where('(state  IN (0,1))');
        }

        $orderCol = $this->state->get('list.ordering', 'id');
        $orderDirn = $this->state->get('list.direction', 'DESC');

        $query->order($db->qn($db->escape($orderCol)).' '.$db->escape($orderDirn));

        return $query;
    }

    protected function populateState($ordering = 'id', $direction = 'desc')
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
