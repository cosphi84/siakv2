<?php

use Joomla\CMS\MVC\Model\ListModel;

defined('_JEXEC') or exit;

JLoader::register('Siak', JPATH_COMPONENT_ADMINISTRATOR.'/helpers/siak.php');

class SiakModelSps extends ListModel
{
    public function __construct($config = [])
    {
        if (empty($config['filter_fields'])) {
            $config['filter_fields'] = [
                'prodi',
                'kelas',
                'ta',
                'jurusan',
                'matakuliah',
                'semester',
                'state'
            ];
        }
        parent::__construct($config);
    }

    protected function getListQuery()
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $prodi = $this->getState('filter.prodi');
        $jurusan = $this->getState('filter.jurusan');
        $kelas = $this->getState('filter.kelas');
        $tahun = $this->getState('filter.tahun');
        $state = $this->getState('filter.state');

        $query->select(['sp.id', 'sp.tahun_ajaran', 'sp.tanggal_daftar', 'sp.state', 'sp.status_bayar', 'sp.nilai_akhir_remid', 'sp.nilai_remid_mutu', 'sp.nilai_remid_angka', 'sp.input_nilai_by', 'sp.input_nilai_time'])
            ->from($db->qn('#__siak_sp', 'sp'))
        ;

        $query->select('p.title AS prodi')
            ->leftJoin('#__siak_prodi AS p ON p.id = sp.prodi')
        ;

        $query->select('j.title AS jurusan')
            ->leftJoin('#__siak_jurusan AS j ON j.id=sp.jurusan')
        ;

        $query->select(['n.nilai_akhir', 'n.nilai_angka', 'n.nilai_mutu', 'n.created_by', 'n.created_date', 'n.status AS statusNilai', 'n.matakuliah AS mk'])
            ->leftJoin('#__siak_nilai AS n ON n.id = sp.nilai_id')
        ;

        $query->select(['m.title AS kodemk', 'm.alias AS matakuliah', 'm.sks'])
            ->leftJoin('#__siak_matakuliah AS m ON m.id = n.matakuliah')
        ;

        $query->select(['u.name AS mahasiswa', 'u.username AS npm'])
            ->leftJoin('#__users AS u ON u.id = sp.user_id')
        ;

        $query->select('d.name AS dosen')
            ->leftJoin('#__users AS d ON d.id = sp.dosen')
        ;

        $query->select('k.title AS kelas')
            ->leftJoin('#__siak_kelas_mahasiswa AS k ON k.id = sp.kelas')
        ;

        $query->select('sm.title as smt')
            ->leftJoin('#__siak_semester AS sm ON sm.id = sp.semester')
        ;

        $mkid = $this->getState('filter.search');
        if (!empty($mkid)) {
            $query->where($db->qn('n.matakuliah').' = '.(int) $mkid);
        }

        !empty($prodi) ? $query->where($db->qn('sp.prodi').' = '.(int) $prodi) : $query;
        !empty($jurusan) ? $query->where($db->qn('sp.jurusan').' = '.(int) $jurusan) : $query;
        !empty($kelas) ? $query->where($db->qn('sp.kelas').' = '.(int) $kelas) : $query;
        !empty($tahun) ? $query->where($db->qn('sp.tahun_ajaran').' = '.$db->q($tahun)) : $query;
        if (!empty($state)) {
            $query->where($db->qn('sp.state').' = '.(int) $state);
        } else {
            $query->where($db->qn('sp.state').' IN (0,1)');
        }

        $query->order(implode(',', [$db->qn('sp.prodi'), $db->qn('u.username')]), 'ASC');

        return $query;
    }

    protected function populateState($order = 'sp.prodi, u.username', $ordering = 'ASC')
    {
        $app = JFactory::getApplication('administrator');
        if ($layout = $app->input->get('layout', 'default', 'cmd')) {
            $this->context .= '.'.$layout;
        }
        $format = $app->input->get('format', 'html', 'cmd');

        $tahun = $this->getUserStateFromRequest($this->context.'.filter.ta', 'filter_ta', '', 'string');
        empty($tahun) ? $tahun = Siak::getTA() : $tahun;
        $this->setState('filter.tahun', $tahun);
        $this->setState('filter.prodi', $this->getUserStateFromRequest($this->context.'.filter.prodi', 'filter_prodi', '', 'string'));
        $this->setState('filter.jurusan', $this->getUserStateFromRequest($this->context.'.filter.jurusan', 'filter_jurusan', '', 'string'));
        $this->setState('filter.kelas', $this->getUserStateFromRequest($this->context.'.filter.kelas', 'filter_kelas', '', 'string'));
        $this->setState('filter.state', $this->getUserStateFromRequest($this->context.'.filter.state', 'filter_state', '', 'string'));

        parent::populateState($order, $ordering);
        if ('json' == $format || 'xlsx' == $format) {
            $this->setState('list.limit', '');
        }
    }
}
