<?php

defined('_JEXEC') or exit('Get Out Mother Fucker!');

class SiakModelPaketmk extends JModelList
{
    public function __construct($config = [])
    {
        if (empty($config['filter_fields'])) {
            $config['filter_fields'] = [
                'prodi',
                'jurusan',
                'semester',
            ];
        }

        parent::__construct($config);
    }

    protected function getListQuery()
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $app = JFactory::getApplication();

        $jurusan = $this->getState('filter.jurusan');
        $semester = $this->getState('filter.semester');
        $prodi = $this->getState('filter.prodi');

        $query->select('p.id')
            ->from($db->qn('#__siak_paket_mk', 'p'))
        ;

        $query->where($db->qn('p.state').' = 1');

        $query->select(['m.title AS kode', 'm.alias AS mk', 'm.sks', 'm.type'])
            ->join('LEFT', $db->qn('#__siak_matakuliah', 'm').' ON m.id = p.matakuliah')
        ;

        $query->select('j.title as jenis')
            ->join('LEFT', $db->qn('#__siak_jenis_mk', 'j').' ON j.id = m.type')
        ;

        $query->select('s.title AS semester')
            ->join('LEFT', $db->qn('#__siak_semester', 's').' ON s.id = p.semester')
        ;

        $query->select('pr.title AS prodi')
            ->leftJoin('#__siak_prodi AS pr ON pr.id = p.prodi')
        ;

        $query->select('k.title as jurusan')
            ->leftJoin('#__siak_jurusan AS k ON k.id = p.jurusan')
        ;

        $search = $this->getState('filter.search');
        $searchs = [];
        if (!empty($search)) {
            $searchs[] = $db->qn('m.title').' LIKE '.$db->q('%'.$search.'%');
            $searchs[] = $db->qn('m.alias').' LIKE '.$db->q('%'.$search.'%');
            $query->where(implode(' OR ', $searchs));
        }

        if (!empty($semester)) {
            $query->where($db->qn('p.semester').' = '.(int) $semester);
        }
        if (!empty($prodi)) {
            $query->where($db->qn('p.prodi').' = '.(int) $prodi);
        }

        $query->order(implode(',', [$db->qn('p.semester'), $db->qn('p.prodi'), $db->qn('p.jurusan')]));

        return $query;
    }

    protected function populateState($ordering = 'p.semester,p.prodi, p.jurusan', $direction = 'ASC')
    {
        $prodi = $this->getUserStateFromRequest($this->context.'.filter.prodi', 'filter_prodi', '', 'string');
        $jurusan = $this->getUserStateFromRequest($this->context.'.filter.jurusuan', 'filter_jurusan', '', 'string');
        $semester = $this->getUserStateFromRequest($this->context.'.filter.semester', 'filter_semester', '', 'string');

        $this->setState('filter.prodi', $prodi);
        $this->setState('filter.jurusan', $jurusan);
        $this->setState('filter.semester', $semester);

        parent::populateState($ordering, $direction);
    }
}
