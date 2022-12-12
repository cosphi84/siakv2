<?php

use Joomla\CMS\MVC\Model\ListModel;

defined('_JEXEC') or exit;

class SiakModelNilais extends ListModel
{
    public function __construct($cfg = [])
    {
        if (empty($cfg['filter_fields'])) {
            $cfg['filter_fields'] = [
                'prodi',
                'jurusan',
                'semester',
                'kelas',
                'ta'
            ];
        }
        parent::__construct($cfg);
    }

    public function getNilaiMahasiswa($id = 0)
    {
    }

    protected function getListQuery()
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $prodi = $this->getState('filter.prodi');
        $jurusan = $this->getState('filter.jurusan');
        $semester = $this->getState('filter.semester');
        $kelas = $this->getState('filter.kelas');
        $ta = $this->getState('filter.ta');
        $search = $this->getState('filter.search');

        $query->select('n.*')
            ->from($db->qn('#__siak_nilai', 'n'))
        ;

        if (!empty($ta)) {
            $query->where($db->qn('n.tahun_ajaran').' = '.$db->q($ta));
        }

        if (!empty($jurusan)) {
            $query->where($db->qn('n.jurusan').' = '.(int) $jurusan);
        }

        if (!empty($semester)) {
            $query->where($db->qn('n.semester').' = '.(int) $semester);
        }

        if (!empty($kelas)) {
            $query->where($db->qn('n.kelas').' = '.(int) $kelas);
        }

        $query->select(['u.name AS mahasiswa', 'u.username AS npm'])
            ->leftJoin('#__users AS u ON u.id=n.user_id')
        ;

        if (!empty($search)) {
            $search = $db->q('%'.$search.'%');
            $searchs = [
                $db->qn('u.name').' LIKE '.$search,
                $db->qn('u.username').' LIKE '.$search,
            ];

            $query->where('( '.implode(' OR ', $searchs).')');
        } else {
            $query->where($db->qn('n.prodi').' = '.(int) $prodi);
        }

        $query->select('s.title as semester')
            ->leftJoin('#__siak_semester AS s ON s.id=n.semester')
        ;

        $query->select('j.title as jurusan')
            ->leftJoin('#__siak_jurusan AS j ON j.id=n.jurusan')
        ;

        $query->select('k.title as kelas')
            ->leftJoin('#__siak_kelas_mahasiswa AS k ON k.id=n.kelas')
        ;

        $query->select(['m.title as kodemk', 'm.alias as mk'])
            ->leftJoin('#__siak_matakuliah AS m ON m.id = n.matakuliah')
        ;

        $query->select(['input_nilai_by', 'input_nilai_time', 'sp.nilai_akhir_remid', 'sp.nilai_remid_angka', 'sp.nilai_remid_mutu'])
            ->leftJoin('#__siak_sp AS sp ON sp.nilai_id = n.id')
        ;

        $query->where($db->qn('n.state').' = 1');
        $query->order('n.matakuliah ASC');

        return $query;
    }

    protected function populateState($ordering = 'n.matakuliah', $direction = 'ASC')
    {
        $app = JFactory::getApplication('administrator');
        if ($layout = $app->input->get('layout', 'default', 'cmd')) {
            $this->context .= '.'.$layout;
        }
        $format = $app->input->get('format', 'html', 'cmd');
        $params = JComponentHelper::getParams('com_siak');

        /*
        $this->setState('filter.search', $this->getUserStateFromRequest($this->context.'.filter.search', 'filter_search', '', 'string'));
        $this->setState('filter.prodi', $this->getUserStateFromRequest($this->context.'.filter.prodi', 'filter_prodi', '', 'string'));
        $this->setState('filter.jurusan', $this->getUserStateFromRequest($this->context.'.filter.jurusan', 'filter_jurusan', '', 'string'));
        $this->setState('filter.jenismk', $this->getUserStateFromRequest($this->context.'.filter.jenismk', 'filter_jenismk', '', 'string'));
        $this->setState('filter.semester', $this->getUserStateFromRequest($this->context.'.filter.semester', 'filter_semester', '', 'string'));
        $this->setState('filter.published', $this->getUserStateFromRequest($this->context.'.filter.published', 'filter_published', '', 'string'));
        */
        $this->setState('params', $params);

        parent::populateState($ordering, $direction);
        if ('html' !== $format) {
            $this->setState('list.limit', '');
        }
    }
}
