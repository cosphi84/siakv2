<?php

defined('_JEXEC') or exit;

class SiakModelNilai extends JModelList
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

    protected function getListQuery()
    {
        $db = JFactory::getDbo();

        $query = $db->getQuery(true);

        $query->select('n.*')
            ->from($db->qn('#__siak_nilai', 'n'))
        ;

        $semester = $this->getState('filter.semester');
        if (!empty($semester)) {
            $query->where($db->qn('n.semester').' = '.(int) $semester);
        }
        $query->select(['u.name as mahasiswa', 'u.username as npm'])
            ->innerJoin('#__users AS u ON u.id = n.user_id')
        ;
        $search = $this->getState('filter.search');
        if (!empty($search)) {
            $search = $db->q('%'.$search.'%');
            $searchs = [
                $db->qn('u.name').' LIKE '.$search,
                $db->qn('u.username').' LIKE '.$search,
            ];
            $query->where('('.implode(' OR ', $searchs).')');
        } else {
            $query->where($db->qn('u.name').' = NULL ');
        }

        $query->select(['m.title as kodemk', 'm.alias as mk'])
            ->leftJoin('#__siak_matakuliah AS m ON m.id = n.matakuliah')
        ;

        $query->select('d.name as dosen')
            ->leftJoin('#__users AS d ON d.id = n.dosen')
        ;

        $query->select(['sp.input_nilai_by', 'sp.input_nilai_time', 'sp.nilai_akhir_remid', 'sp.nilai_remid_angka', 'sp.nilai_remid_mutu'])
            ->leftJoin('#__siak_sp AS sp ON sp.nilai_id = n.id')
        ;

        $query->order($db->qn('n.semester').' ASC');

        return $query;
    }

    protected function populateState($dir = 'n.semester', $order = 'Asc')
    {
        parent::populateState($dir, $order);
    }
}
