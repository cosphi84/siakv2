<?php

defined('_JEXEC') or exit;

class SiakModelIndustries extends JModelList
{
    public function __construct($cfg = [])
    {
        if (empty($cfg['filter_fields'])) {
            $cfg['filter_fields'] = [
                'published',
                'i.nama',
            ];
        }
        parent::__construct($cfg);
    }

    protected function getListQuery()
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $search = $this->getState('filter.search');
        $published = $this->getState('filter.published');

        $query->select('i.*')
            ->from($db->qn('#__siak_industri', 'i'))
        ;

        $query->select('u.name as dibuat_oleh')
            ->leftJoin('#__users AS u ON u.id = i.created_user')
        ;

        if (!empty($search)) {
            $query->where($db->qn('i.nama').' LIKE '.$db->q('%'.$search.'%'));
        }

        $query->where($db->qn('i.state').' = 1');

        return $query;
    }
}
