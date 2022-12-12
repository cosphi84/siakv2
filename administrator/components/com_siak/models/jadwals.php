<?php

defined('_JEXEC') or exit;

use Joomla\CMS\Factory;
use Joomla\CMS\MVC\Model\ListModel;

JLoader::register('Siak', JPATH_COMPONENT_ADMINISTRATOR.'/helpers/siak.php');

class SiakModelJadwals extends ListModel
{
    public function __construct($config = [])
    {
        if (empty($config['filter_fields'])) {
            $config['filter_fields'] = [
                'prodi',
                'jurusan',
                'semester',
                'kelas',
                'tahun_ajaran',
            ];
        }
        parent::__construct($config);
    }

    protected function getListQuery()
    {
        $db = $this->getDbo();
        $query = $db->getQuery(true);

        $search = $this->getState('filter.search');

        $jurusan = $this->getState('filter.jurusan');
        $semester = $this->getState('filter.semester');
        $state = $this->getState('filter.published');
        $kelas = $this->getState('filter.kelas');
        $tahun = $this->getState('filter.tahun_ajaran');

        $query->select('jw.*')
            ->from($db->qn('#__siak_jadwal_kbm', 'jw'))
        ;

        $query->select('p.alias as prodi')
            ->leftJoin('#__siak_prodi AS p ON p.id = jw.prodi')
        ;

        $query->select('j.alias AS konsentrasi')
            ->leftJoin('#__siak_jurusan AS j ON j.id = jw.jurusan')
        ;

        $query->select('s.title AS semester')
            ->leftJoin('#__siak_semester AS s ON s.id = jw.semester')
        ;

        $query->select('k.title AS kelas')
            ->leftJoin('#__siak_kelas_mahasiswa AS k ON k.id = jw.kelas')
        ;

        $query->select(['m.title AS kodeMK', 'm.alias AS matakuliah'])
            ->leftJoin('#__siak_matakuliah AS m ON m.id = jw.matakuliah')
        ;

        $query->select('dmk.user_id AS dosenID')
          ->leftJoin(
              '#__siak_dosen_mk AS dmk on dmk.prodi = jw.prodi AND '.
              'dmk.jurusan = jw.jurusan AND '.
              'dmk.kelas=jw.kelas AND '.
              'dmk.matakuliah=jw.matakuliah AND '.
              'dmk.tahun_ajaran = jw.tahun_ajaran'
          )
        ;

        $query->select('r.title AS ruangan')
            ->leftJoin('#__siak_ruangan AS r ON r.id = jw.ruangan')
        ;

        $prodi = $this->getState('filter.prodi');
        if (is_numeric($prodi)) {
            $query->where($db->qn('jw.prodi').' = '.(int) $prodi);
        }

        if (!empty($jurusan)) {
            $query->where($db->qn('jw.jurusan').' = '.(int) $jurusan);
        }

        if (!empty($semester)) {
            $query->where($db->qn('jw.semester').' = '.(int) $semester);
        }

        if (!empty($tahun)) {
            $query->where($db->qn('jw.tahun_ajaran') .' = '. $db->q($tahun));
        } else {
            $query->where($db->quoteName('jw.tahun_ajaran') . ' = '. $db->quote(Siak::getTA()));
        }

        if (!empty($kelas)) {
            $query->where($db->qn('jw.kelas').' = '.(int) $kelas);
        }

        if (!empty($state)) {
            $query->where($db->qn('jw.state').' = '.(int) $state);
        } else {
            $query->where($db->qn('jw.state').' IN (0,1)');
        }

        if (!empty($search)) {
            $query->where($db->qn('m.title').' LIKE '.$db->q('%'.$search.'%'));
        }

        $query->order($db->qn('s.title').' ASC');
        $query->order($db->qn('jw.hari').' ASC');

        return $query;
    }

    protected function populateState($order = 'jw.hari', $dir = 'ASC')
    {
        $app = Factory::getApplication();
        $doc = Factory::getDocument();
        $format = $doc->getType();

        parent::populateState($order, $dir);
        if ($format !== 'html') {
            $this->setState('list.limit', null);
        }
    }
}
