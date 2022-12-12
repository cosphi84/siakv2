<?php

defined('_JEXEC') or exit;

use Joomla\CMS\MVC\Model\ListModel;
use Joomla\CMS\Factory;

class SiakModelMymk extends ListModel
{
    public function __construct($cfg = [])
    {
        if (empty($cfg['filter_fields'])) {
            $cfg['filter_fields'] = [
                'ta',
            ];
        }

        parent::__construct($cfg);
    }

    protected function getListQuery()
    {
        $db = $this->getDbo();
        $query = $db->getQuery(true);
        $search = $this->getState('filter.search');
        $ta = $this->getState('filter.ta', 0);

        $query->select([
            $db->quoteName('dsdm.id'),
            $db->quoteName('dsdm.tahun_ajaran', 'TA'),
            $db->quoteName('dsdm.matakuliah', 'mkid'),
            $db->quoteName('dsdm.kelas', 'klid'),
            $db->quoteName('du.name', 'DOSEN'),
            $db->quoteName('dsm.title', 'KODEMK'),
            $db->quoteName('dsm.alias', 'MK'),
            $db->quoteName('dsm.sks'),
            $db->quoteName('dsj.title', 'JURUSAN_MK'),
            $db->quoteName('dsp.title', 'PRODI_MK'),
            $db->quoteName('dskm.title', 'KELAS_mhs'),
            $db->quoteName('dsdm.state', 'STATUS') ])
        ->from($db->quoteName('#__siak_dosen_mk', 'dsdm'))
        ->join('LEFT', $db->quoteName('#__siak_matakuliah', 'dsm') . ' ON '. $db->quoteName('dsm.id'). ' = '. $db->quoteName('dsdm.matakuliah'))
        ->join('LEFT', $db->quoteName('#__siak_prodi', 'dsp') . ' ON '. $db->quoteName('dsp.id'). ' = '. $db->quoteName('dsm.prodi'))
        ->join('LEFT', $db->quoteName('#__siak_jurusan', 'dsj') . ' ON '. $db->quoteName('dsj.id'). ' = '. $db->quoteName('dsm.jurusan'))
        ->join('LEFT', $db->quoteName('#__siak_kelas_mahasiswa', 'dskm') . ' ON '. $db->quoteName('dskm.id'). ' = '. $db->quoteName('dsdm.kelas'))
        ->join('LEFT', $db->quoteName('#__users', 'du') . ' ON '. $db->quoteName('du.id'). ' = '. $db->quoteName('dsdm.user_id'));

        $query->where($db->quoteName('dsdm.user_id'). ' = ' . (int) Factory::getUser()->id)
            ->where($db->quoteName('dsdm.tahun_ajaran'). ' = '. $db->quote($ta));

        $query->order($db->quoteName('dsdm.matakuliah').', '. $db->quoteName('dsm.prodi'). ' ASC');


        if (!empty($search)) {
            $search = $db->quote('%'.$search.'%');
            $searchs = [];
            $searchs[] = $db->qn('dsm.title').' LIKE '.$search;
            $searchs[] = $db->qn('dsm.alias').' LIKE '.$search;
            $query->where('('.implode(' OR ', $searchs).' )');
        }



        return $query;
    }

    protected function populateState($order = 'm.title', $dir = 'ASC')
    {
        $this->setState('filter.ta', $this->getUSerStateFromRequest($this->context.'.filter', 'filter_ta', '', 'string'));
        parent::populateState($order, $dir);
    }
}
