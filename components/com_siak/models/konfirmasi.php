<?php

JLoader::register('SiakHelper', JPATH_COMPONENT.'/helpers/siak/php');
defined('_JEXEC') or exit;

class SiakModelKonfirmasi extends JModelAdmin
{
    public function getForm($data = [], $loadData = true)
    {
        $form = $this->loadForm('com_siak.konfirmasi', 'konfirmasi', ['control' => 'jform', 'load_data' => $loadData]);
        if (empty($form)) {
            return false;
        }

        return $form;
    }

    public function getTable($type = 'Statusmahasiswa', $prefix = 'Table', $config = [])
    {
        return JTable::getInstance($type, $prefix, $config);
    }

    public function save($data)
    {
        $table = $this->getTable();
        $key = $table->getKeyName();
        if (in_array($key, $data)) {
            $table->load($data['key']);
        } else {
            $table->load(['user_id' => $data['user_id'], 'semester' => $data['semester']]);
        }

        if (!$table->bind($data)) {
            $this->setError($table->getError());

            return false;
        }
        $this->prepareTable($table);

        // Check the data.
        if (!$table->check()) {
            $this->setError($table->getError());

            return false;
        }
        if (!$table->store()) {
            $this->setError($table->getError());

            return false;
        }

        // Clean the cache.
        $this->cleanCache();

        return true;
    }

    public function getData($pk = null)
    {
        $user = JFactory::getUser();
        $app = JFactory::getApplication();
        $id = $app->input->get('id', 0, 'int');
        empty($pk) ? $pk = $id : $pk;
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);

        $query->select(['m.id', 'm.user_id', 'm.status', 'm.ta', 'm.confirm', 'm.create_date'])
            ->from($db->qn('#__siak_mhs_status', 'm'))
        ;
        $query->where($db->qn('m.id').' = '.(int) $pk);

        $query->select(['u.angkatan', 'u.no_ktp', 'u.dob', 'u.pob', 'u.jenis_kelamin', 'u.status_sipil', 'u.agama', 'u.alamat_1', 'u.alamat_2', 'u.kelurahan', 'u.kecamatan', 'u.kabupaten', 'u.propinsi', 'u.kode_pos', 'u.telepon', 'u.foto'])
            ->leftJoin('#__siak_user AS u ON u.user_id = m.user_id')
        ;

        $query->select(['p.title AS prodi', 'p.alias AS program_studi'])
            ->leftJoin('#__siak_prodi as p on p.id=m.prodi')
        ;

        $query->select(['j.title AS jurusan', 'j.alias AS konsentrasi'])
            ->leftJoin('#__siak_jurusan as j on j.id = m.jurusan')
        ;

        $query->select('k.title as kelas')
            ->leftJoin('#__siak_kelas_mahasiswa AS k ON k.id=m.kelas')
        ;

        $query->select('s.title as semester')
            ->leftJoin('#__siak_semester as s on s.id=m.semester')
        ;

        $query->select(['us.name as mahasiswa', 'us.username as npm'])
            ->leftJoin('#__users as us on us.id=m.user_id')
        ;

        try {
            $db->setQuery($query);
            $result = $db->loadObject();
        } catch (RuntimeException $err) {
            $this->setError($err->getMessage());

            return false;
        }

        if ($result->id > 0 && $result->user_id != $user->id) {
            $this->setError('Mencoba mengakses data orang lain tu ngga boleh yaaaa.....!!!!');

            return false;
        }

        return $result;
    }

    protected function loadFormData()
    {
        $result = $this->getItem();

        $result->ta = SiakHelper::getTA();
        //default status aktif aja ya
        $result->status = '1';
        $result->mahasiswa_id = $result->user_id;
        //$app->setUserState('com_siak.mahasiswa.status', $result);

        return $result;
    }
}
