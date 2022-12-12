<?php

defined('_JEXEC') or die;

class SiakModelAlldosens extends JModelList
{
    public function __construct($config = [])
    {
        if (empty($config['filter_fields'])) {
            $config['filter_fields'] = [
                'published',
            ];
        }

        parent::__construct($config);
    }

    protected function getListQuery()
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $grpDosen = JComponentHelper::getParams('com_siak')->get('grpDosen');

        $query->select(['u.id', 'u.name as dosen', 'u.email'])
            ->from($db->qn('#__users', 'u'))
        ;

        $query->join('LEFT', $db->qn('#__user_usergroup_map', 'm').' ON m.user_id = u.id');
        $query->where($db->qn('m.group_id').' = '.(int) $grpDosen);
        $query->order($db->qn('u.name').' ASC');

        return $query;
    }
}
