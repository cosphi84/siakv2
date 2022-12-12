<?php

defined('_JEXEC') or exit;

class SiakModelRuangans extends JModelList
{
    public function __construct($config = [])
    {
        if (empty($config['filter_fields'])) {
            $config['filter_fields'] = [
                'published',
                'r.title',
            ];
        }
        parent::__construct($config);
    }

    protected function getListQuery()
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select(['r.id', 'r.title', 'r.alias', 'r.note', 'r.state as published']);
        $query->from($db->qn('#__siak_ruangan', 'r'));

        $search = $this->getState('filter.search');
        if (!empty($search)) {
            $search = $db->quote('%'.$search.'%');
            $searchs = [];
            $searchs[] = $db->qn('r.title').' LIKE '.$search;
            $searchs[] = $db->qn('r.alias').' LIKE '.$search;
            $query->where('('.implode(' OR ', $searchs).' )');
        }

        $published = $this->getState('filter.published');
        if (is_numeric($published)) {
            $query->where($db->qn('r.state').' = '.(int) $published);
        } elseif ('' === $published) {
            $query->where('(r.state  IN (0,1))');
        }

        $orderCol = $this->state->get('list.ordering', 'r.title');
        $orderDirn = $this->state->get('list.direction', 'ASC');

        $query->order($db->qn($db->escape($orderCol)).' '.$db->escape($orderDirn));

        return $query;
    }

    protected function populateState($ordering = 'r.title', $direction = 'ASC')
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
