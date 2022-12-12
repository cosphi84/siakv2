<?php

defined('_JEXEC') or exit;

use Joomla\CMS\MVC\Model\ListModel;

JLoader::register('Siak', JPATH_COMPONENT_ADMINISTRATOR.'/helpers/siak.php');

class SiakModelDosenmks extends ListModel
{
    public function __construct($config = [])
    {
        if (empty($config['filter_fields'])) {
            $config['filter_fields'] = [
                'ta',
                'prodi',
                'published',
                'kelas',
            ];
        }
        parent::__construct($config);
    }

    protected function getListQuery()
    {
        $db = $this->getDbo();

        $query = $db->getQuery(true);
        $search = $this->getState('filter.search');
        $published = $this->getState('filter.published');
        $prodi = $this->getState('filter.prodi');
        $semester = $this->getState('filter.semester');

        $query->select(['dmk.id', 'dmk.tahun_ajaran', 'dmk.state'])
            ->from($db->qn('#__siak_dosen_mk', 'dmk'))
        ;

        $query->select(['m.title AS kodeMK', 'm.alias AS MK', 'm.sks'])
            ->join('LEFT', $db->qn('#__siak_matakuliah', 'm').' ON ( m.id = dmk.matakuliah AND m.state > 0)')
        ;

        $query->select('p.title as prodi')
            ->join('LEFT', $db->qn('#__siak_prodi', 'p').' ON p.id = m.prodi')
        ;

        $query->select('j.title as jurusan')
            ->join('LEFT', $db->qn('#__siak_jurusan', 'j').' ON j.id = m.jurusan')
        ;

        $query->select('k.title as kelas')
            ->join('LEFT', $db->qn('#__siak_kelas_mahasiswa', 'k').' ON k.id=dmk.kelas')
        ;

        $query->select('u.name as dosen')
            ->join('LEFT', $db->qn('#__users', 'u').' ON u.id = dmk.user_id')
        ;


        if (is_numeric($published)) {
            $query->where($db->qn('dmk.state').' = '.(int) $published);
        } else {
            $query->where($db->qn('dmk.state').' IN (0, 1)');
        }


        if (!empty($search)) {
            $search = $db->quote('%'.$search.'%');
            $seacrhs[] = $db->quoteName('u.name'). ' LIKE '. $search;
            $seacrhs[] = $db->quoteName('m.title'). ' LIKE '. $search;
            $query->where('( '. implode(' OR ', $seacrhs). ')');
        }

        if (!empty($prodi)) {
            $query->where($db->quoteName('dmk.prodi'). ' = '. (int) $prodi);
        }

        if (!empty($jurusan = $this->getState('filter.jurusan'))) {
            $query->where($db->quoteName('dmk.jurusan'). ' = '. (int) $jurusan);
        }

        if (!empty($kelas = $this->getState('filter.kelas'))) {
            $query->where($db->quoteName('dmk.kelas'). ' = '. (int) $kelas);
        }

        if (!empty($ta = $this->getState('filter.ta'))) {
            $query->where($db->qn('dmk.tahun_ajaran').' = '.$db->q($ta));
        } else {
            $query->where($db->quoteName('dmk.tahun_ajaran') . ' = '. $db->quote(Siak::getTA()));
        }

        $query->order('m.title, m.prodi ASC');

        return $query;
    }

    protected function populateState($order = '', $dir = '')
    {
        parent::populateState($order, $dir);
    }
}
