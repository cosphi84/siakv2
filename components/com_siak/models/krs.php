<?php

use Joomla\Utilities\ArrayHelper;

defined('_JEXEC') or exit;

class SiakModelKrs extends JModelAdmin
{
    private $_item;

    public function getForm($data = [], $loadData = true)
    {
        $form = $this->loadForm('siak.krs', 'krs', ['control' => 'jform', 'load_data' => $loadData]);
        if (empty($form)) {
            return false;
        }

        return $form;
    }

    public function getPaketMK()
    {
        $id = JFactory::getApplication()->input->get('id', 0, 'int');
        $db = JFactory::getDbo();

        $query = $db->getQuery(true);

        /*
        if (empty($semester) || '2' == $biodata->tipe_user) {
            return false;
        }
        */

        $query->select('p.matakuliah as mkid')
            ->from($db->qn('#__siak_paket_mk', 'p'))
        ;

        $query->select(['m.title AS kode', 'm.alias AS MK', 'm.sks'])
            ->join('INNER', $db->qn('#__siak_matakuliah', 'm').' ON m.id=p.matakuliah')
        ;

        $query->select('k.id as kid')
            ->join('LEFT', $db->qn('#__siak_krs', 'k').' ON k.semester=p.semester')
        ;

        $query->where($db->qn('k.id').' = '.$db->q($id));
        $query->where($db->qn('p.state').' = 1');

        $db->setQuery($query);

        try {
            $result = $db->loadObjectList();
        } catch (\Exception $th) {
            $this->setError($th->getMessage());

            return false;
        }

        return $result;
    }

    public function getMyMK()
    {
        $id = JFactory::getApplication()->input->get('id', 0, 'int');
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('my.id as mid, my.matakuliah as mkid');
        $query->from($db->qn('#__siak_krs_items', 'my'));
        $query->select(['m.id as mkid', 'm.title AS kode', 'm.alias AS MK', 'm.sks'])
            ->join('INNER', $db->qn('#__siak_matakuliah', 'm').' ON m.id=my.matakuliah')
        ;
        $query->where($db->qn('my.krs').' = '.(int) $id);

        $db->setQuery($query);

        try {
            $result = $db->loadObjectList();
        } catch (\Throwable $th) {
            $this->setError($th->getMessage());

            return false;
        }

        return $result;
    }

    public function save($data)
    {
        $table = $this->getTable();

        if (isset($data['id']) && $data['id'] > 0) {
            $table->load($data['id']);
        } else {
            $table->load(['user_id' => $data['user_id'],
                'semester' => $data['semester'], ]);
        }

        $savedData = $table->getProperties(1);
        if ($savedData['confirm_dw'] > 1) {
            $this->setError('KRS anda untuk semester ini sudah disetujui oleh dosen wali!');

            return false;
        }

        $data['id'] = $savedData['id'];

        return parent::save($data);
    }

    public function saveMK($mks = [], $idKRS = null)
    {
        $table = $this->getTable('Krsitem');
        $arr = [];
        foreach ($mks as $k => $v) {
            $arr[$k]['id'] = '';
            $arr[$k]['krs'] = $idKRS;
            $arr[$k]['matakuliah'] = $v;

            if ($table->load(['krs' => $idKRS, 'matakuliah' => $v])) {
                $data = $table->getProperties(1);

                //if (null !== $data['id']) {
                $table->delete($data['id']);
                //}
            }
        }
        $table->reset();
        unset($mks);
        foreach ($arr as $k => $v) {
            $table->load($v['id']);
            if (!$table->bind($v)) {
                $this->setError($table->getError());

                return false;
            }

            // Prepare the row for saving
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
        }

        return true;
    }

    /**
     * getlastID
     * Get last ID KRS for current user.
     *
     * @param mixed $user_id User ID lookup id
     * @param mixed $data
     *
     * @return int ID of LAST KRS
     */
    public function getID($data)
    {
        $db = JFactory::getDbo();
        $q = $db->getQuery(true);
        $q->select('id')->from($db->qn('#__siak_krs'));
        $q->where($db->qn('user_id').' = '.$db->q($data['user_id']));
        $q->where($db->qn('semester').' = '.$db->q($data['semester']));
        $q->order($db->qn('id').' DESC');
        $db->setQuery($q);

        try {
            $id = $db->loadResult();
        } catch (\Exception $th) {
            $this->setError($th->getMessage());

            return false;
        }

        return $id;
    }

    public function getItem($pk = null)
    {
        $pk = (!empty($pk)) ? $pk : (int) $this->getState($this->getName().'.id');
        $table = $this->getTable();

        if ($pk > 0) {
            // Attempt to load the row.
            $return = $table->load($pk);

            // Check for a table object error.
            if (false === $return && $table->getError()) {
                $this->setError($table->getError());

                return false;
            }
        }

        // Convert to the \JObject before adding other data.
        $properties = $table->getProperties(1);
        $item = ArrayHelper::toObject($properties, '\JObject');

        $tableSM = $this->getTable('Semester');
        $sm = $tableSM->load($item->semester);
        if (false === $sm && $tableSM->getError()) {
            $this->setError($tableSM->getError());

            return false;
        }

        $smprop = $tableSM->getProperties(1);
        $sm = ArrayHelper::toObject($smprop, '\JObject');
        unset($sm->id);

        if (property_exists($item, 'params')) {
            $registry = new Registry($item->params);
            $item->params = $registry->toArray();
        }

        return (object) array_merge((array) $item, (array) $sm);
    }

    public function deletemk($id = null)
    {
        $table = $this->getTable('Krsitem');
        if (!$table->load($id)) {
            return 'Error No Data';
        }

        if (!$table->delete($id)) {
            return $table->getError();
        }

        return true;
    }

    public function getData()
    {
        $app = JFactory::getApplication();
        $id = $app->input->get('id', 0, 'int');

        $id = (!empty($id)) ? $id : (int) $this->getState($this->getName().'.id');
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);

        $query->select(['k.id', 'k.ttl_sks', 'k.created_time', 'k.confirm_dw', 'k.confirm_note'])
            ->from($db->qn('#__siak_krs', 'k'))
        ;

        $query->select(['u.name AS mahasiswa', 'u.username AS npm'])
            ->join('INNER', $db->qn('#__users', 'u').' ON u.id=k.user_id')
        ;

        $query->select(['us.alamat_1', 'us.angkatan'])
            ->join('INNER', $db->qn('#__siak_user', 'us').' ON us.user_id = k.user_id')
        ;

        $query->select('dw.user_id as dosenwaliid')
            ->join('LEFT', $db->qn('#__siak_dosen_wali', 'dw').' ON dw.id = k.dosen_wali')
        ;

        $query->select('usr.name as dosenwali')
            ->join('LEFT', $db->qn('#__users', 'usr').' ON usr.id = dw.user_id')
        ;

        $query->select(['pr.title as nama_prodi', 'pr.alias as nama_prodi_alias'])
            ->join('LEFT', $db->qn('#__siak_prodi', 'pr').' ON pr.id = k.prodi')
        ;

        $query->select('sem.title as semester')
            ->join('LEFT', $db->qn('#__siak_semester', 'sem').' ON sem.id = k.semester')
        ;

        $query->where($db->qn('k.id').' = '.(int) $id);

        $db->setQuery($query);

        try {
            $result = $db->loadObject();
        } catch (\Throwable $th) {
            $this->setError($th->getMessage());

            return false;
        }

        $dataMKS = $this->getMyMK();

        return (object) array_merge((array) $result, (array) $dataMKS);
    }

    protected function loadFormData()
    {
        $this->_item = parent::getItem();

        if (empty($this->_item->id)) {
            $usr = JFactory::getUser();
            $app = JFactory::getApplication();
            $mhs = $app->getUserState('com_siak.mahasiswa.status');
            $this->_item = (object) array_merge((array) $this->_item, (array) $mhs);
            $this->_item->tahun_ajaran = SiakHelper::getTA();
        } else {
            $usr = JFactory::getUser($this->_item->user_id);
        }
        $this->_item->nama = $usr->name;
        $this->_item->npm = $usr->username;

        return $this->_item;
    }
}
