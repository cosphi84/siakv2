<?php

defined('_JEXEC') or exit;

class SiakModelBayaran extends JModelList
{
    public function __construct($config = [])
    {
        if (empty($config['filter_fields'])) {
            $config['filter_fields'] = [
                'semester',
            ];
        }

        parent::__construct($config);
    }

    protected function populateState($sort = 'a.semester', $arah = 'ASC')
    {
        $semester = $this->getUserStateFromRequest($this->context.'.filter.semester', 'filter_semester', '', 'string');
        $search = $this->getUserStateFromRequest($this->context.'.filter.search', 'filter_search', '', 'string');
        $this->setState('filter.search', $search);
        $this->setState('filter.semester', $semester);
        parent::populateState($sort, $arah);
    }

    protected function getListQuery()
    {
        $user = JFactory::getUser();
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);

        $query->select('a.*')
            ->from($db->qn('#__siak_pembayaran', 'a'))
        ;

        $query->select('s.title as Sem')
            ->join('LEFT', $db->qn('#__siak_semester', 's').' ON s.id = a.semester')
        ;
        $query->select(['mk.title as kodeMK', 'mk.alias as mk'])
            ->leftJoin('#__siak_matakuliah AS mk ON mk.id=a.matakuliah')
        ;

        $search = $this->getState('filter.search');
        $semester = $this->getState('filter.semester');

        if (is_string($search)) {
            $query->where($db->qn('a.no_ref').' LIKE '.$db->q($search.'%'));
        }

        if (is_numeric($semester)) {
            $query->where($db->qn('a.semester').' = '.$db->q($semester));
        }
        $query->where($db->qn('a.user_id').' = '.$db->q($user->id));
        $query->order($db->qn('a.semester').' ASC');

        return $query;
    }
}
