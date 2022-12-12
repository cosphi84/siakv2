<?php

defined('_JEXEC') or exit;

class SiakModelKuliahku extends JModelList
{
    public function __construct($config = [])
    {
        if (empty($config['filter_fields'])) {
            $config['filter_fields'] = [
                'search',
            ];
        }

        parent::__construct($config);
    }

    protected function getListQuery()
    {
        $db = $this->getDbo();
        $userID = JFactory::getUser()->id;
        $query = $db->getQuery(true);

        $query->select(['n.id', 'n.tahun_ajaran'])
            ->from($db->qn('#__siak_nilai', 'n'))
        ;

        $query->select(['mk.title AS kodemk', 'mk.alias AS matakuliah', 'mk.sks'])
            ->leftJoin('#__siak_matakuliah AS mk ON mk.id = n.matakuliah')
        ;

        $query->select('s.title AS semester')
            ->leftJoin('#__siak_semester AS s ON s.id=n.semester')
        ;

        $search = $this->getState('filter.search');
        if (!empty($search)) {
            $query->where($db->qn('n.tahun_ajaran').' = '.$db->q($search));
        }

        $query->where($db->qn('n.state').' = \'1\'');
        $query->where($db->qn('n.user_id').' = '.(int) $userID);
        $query->order($db->qn('n.tahun_ajaran').' ASC');

        return $query;
    }

    protected function populateState($ordering = 'n.semester', $direction = 'ASC')
    {
        $app = JFactory::getApplication();
        $format = $app->input->get('format', 'html', 'cmd');
        $this->setState('filter.search', $this->getUserStateFromRequest($this->context.'.filter.search', 'filter_search', '', 'string'));
        parent::populateState($ordering, $direction);

        if ('json' === $format || 'xlsx' === $format) {
            $this->setState('list.limit', '');
        }
    }
}
