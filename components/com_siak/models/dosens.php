<?php

defined('_JEXEC') or exit;

class SiakModelDosens extends JModelList
{
    public function __construct($config = [])
    {
        if (empty($config['filter_fields'])) {
            $config['filter_fields'] = [
                'published',
                'prodi',
            ];
        }
        parent::__construct($config);
    }

    public function getForm($data = [], $loadData = true)
    {
        $form = $this->loadForm('com_siak.dosen', 'dosens', ['control' => 'jform', 'load_data' => $loadData]);
        if (empty($form)) {
            return false;
        }

        $grpUsr = JFactory::getUser()->get('groups');
        $grpMhs = JComponentHelper::getParams('com_siak')->get('grpMahasiswa');

        if (in_array($grpMhs, $grpUsr)) {
            // prepare form for Mahasiswa
            $form->removeField('nidn');
            $form->removeField('nik');
        } else {
            $form->removeField('angkatan');
            $form->setFieldAttribute('no_ktp', 'required', 'true');
        }

        return $form;
    }

    protected function loadFormData()
    {
        $data = [];
        $data['prodi'] = $this->getState('filter.prodi');
        $data['jurusan'] = $this->getState('filter.jurusan');
        $data['ta'] = $this->getState('filter.ta');
        $data['kelas'] = $this->getState('filter.kelas');

        return $data;
    }

    protected function populateState($ordering = 'd.id', $direction = 'ASC')
    {
        $this->setState('filter.prodi', $this->getUserStateFromRequest($this->context.'.filter.prodi', 'filter_prodi', '', 'string'));
        $this->setState('filter.jurusan', $this->getUserStateFromRequest($this->context.'.filter.jurusan', 'filter_jurusan', '', 'string'));
        $this->setState('filter.tahunAjaran', $this->getUserStateFromRequest($this->context.'.filter.ta', 'filter_ta', '', 'string'));
        $this->setState('filter.kelas', $this->getUserStateFromRequest($this->context.'.filter.kelas', 'filter_kelas', '', 'string'));
        parent::populateState($ordering, $direction);
    }

    protected function getListQuery()
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);

        $prodi = $this->getState('filter.prodi');
        $jurusan = $this->getState('filter.jurusan');
        $thAkademik = $this->getState('filter.tahunAjaran');
        $kelas = $this->getState('filter.kelas');

        $query->select('dmk.id')->from($db->qn('#__siak_dosen_mk', 'dmk'));
        $query->select('u.name AS dosen');
        $query->select(['mk.title AS kode', 'mk.alias AS matakuliah', 'mk.sks']);
        $query->select('p.title AS prodi');
        $query->select('j.title AS jurusan');
        $query->select('k.title AS kelas');

        $query->join('LEFT', $db->qn('#__users', 'u').' ON '.$db->qn('u.id').' = '.$db->qn('dmk.user_id'));
        $query->join('LEFT', $db->qn('#__siak_matakuliah', 'mk').' ON '.$db->qn('mk.id').' = '.$db->qn('dmk.matakuliah'));
        $query->join('LEFT', $db->qn('#__siak_prodi', 'p').' ON '.$db->qn('p.id').' = '.$db->qn('dmk.prodi'));
        $query->join('LEFT', $db->qn('#__siak_jurusan', 'j').' ON '.$db->qn('j.id').' = '.$db->qn('dmk.jurusan'));
        $query->join('LEFT', $db->qn('#__siak_kelas_mahasiswa', 'k').' ON '.$db->qn('k.id').' = '.$db->qn('dmk.kelas'));
        $query->where($db->qn('dmk.prodi').' = '.$db->q($prodi));
        $query->where($db->qn('dmk.tahun_ajaran').' = '.$db->q($thAkademik));

        if (empty($prodi) || empty($thAkademik)) {
            $query->where($db->qn('dmk.id').' = '.$db->q('0'));

            return $query;
        }

        if (is_numeric($jurusan)) {
            $query->where($db->qn('dmk.jurusan').' = '.$db->q($jurusan));
        }

        if (is_numeric($kelas)) {
            $query->where($db->qn('dmk.kelas').' = '.$db->q($kelas));
        }

        $query->where($db->qn('dmk.state').' = 1');
        $query->order($db->qn('dmk.kelas').' ASC');

        return $query;
    }
}
