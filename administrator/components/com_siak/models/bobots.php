<?php

defined('_JEXEC') or die('Direct access not allowed!');

class SiakModelBobots extends JModelList
{
    public function __construct($cfg = [])
    {
        if (!empty($cfg['filter_fields'])) {
            $cfg['filter_fields'] = [
                'b.title',
                'b.bobot',
                'state',
            ];
        }

        parent::__construct($cfg);
    }

    protected function getListQuery()
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('b.*')
            ->from($db->qn('#__siak_bobot_nilai', 'b'))
        ;

        $state = $this->getState('filter.state');
        if (is_numeric($state)) {
            $query->where($db->qn('b.state').' = '.(int) $state);
        } else {
            $query->where($db->qn('b.state').' IN(0,1)');
        }

        $orderCol = $this->state->get('list.ordering', 'b.title');
        $orderDirn = $this->state->get('list.direction', 'ASC');

        $query->order($db->qn($db->escape($orderCol)).' '.$db->escape($orderDirn));

        return $query;
    }
}
