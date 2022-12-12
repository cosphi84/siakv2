<?php

defined('_JEXEC') or exit;

class SiakModelSks extends JModelList
{
    public function __construct($config = [])
    {
        if (empty($config['filter_fields'])) {
            $config['filter_fields'] = [
                'title',
            ];
        }

        parent::__construct($config);
    }

    protected function getListQuery()
    {
        $user = JFactory::getUser();
        $db = JFactory::getDbo();
        $q = $db->getQuery(true);

        $q->select('a.*')->from($db->qn('#__siak_sk', 'a'));
        $q->select('v.title AS access_level')->join('LEFT', '#__viewlevels AS v ON v.id = a.access');

        if (!$user->authorise('core.admin')) {  // ie if not SuperUser
            $userAccessLevels = implode(',', $user->getAuthorisedViewLevels());
            $q->where('a.access IN ('.$userAccessLevels.')');
        }
        $search = $this->getState('filter.search');
        if (!empty($search)) {
            $search = $db->quote('%'.$search.'%');
            $searchs = [];
            $searchs[] = $db->qn('a.title').' LIKE '.$search;
            $searchs[] = $db->qn('a.alias').' LIKE '.$search;
            $q->where('('.implode(' OR ', $searchs).' )');
        }

        $q->where($db->qn('a.state').' = 1');
        $orderCol = $this->state->get('list.ordering', 'a.id');
        $orderDirn = $this->state->get('list.direction', 'DESC');

        $q->order($db->escape($orderCol).' '.$db->escape($orderDirn));

        return $q;
    }

    protected function populateState($ordering = 'a.id', $direction = 'DESC')
    {
        $this->setState('filter.search', $this->getUserStateFromRequest($this->context.'.filter.search', 'filter_search', '', 'string'));
        parent::populateState($ordering, $direction);
    }
}
