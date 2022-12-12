<?php

defined('_JEXEC') or exit;

class SiakModelRemidial extends JModelForm
{
    /**
     * Method to load form xml file.
     *
     * @param mixed $data
     * @param mixed $loadData
     *
     * @return bool|object
     */
    public function getForm($data = [], $loadData = true)
    {
        $form = $this->loadForm('com_siak.remidial', 'remidial', ['control' => 'jform', 'load_data' => $loadData]);
        if (empty($form)) {
            return false;
        }

        return $form;
    }

    /**
     * Method to get table class.
     *
     * @param string $name   table class name
     * @param mixed  $prefix
     * @param mixed  $config
     */
    public function getTable($name = 'Sp', $prefix = 'Table', $config = [])
    {
        return JTable::getInstance($name, $prefix, $config);
    }

    /**
     * Method to get single Record.
     *
     * @param int $pk The ID of primary key
     *
     * @return booledan|object Object on succes, false on failure
     */
    public function getItem($pk = null)
    {
        $pk = (!empty($pk)) ? $pk : (int) $this->getState($this->getName().'.id');
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);

        $query->select(['r.id', 'r.nilai_id', 'r.tahun_ajaran AS ta'])
            ->from($db->qn('#__siak_sp', 'r'))
        ;

        $query->select(['u.name AS mahasiswa', 'u.username AS npm'])
            ->leftJoin('#__users AS u ON u.id = r.user_id')
        ;

        $query->select(['n.nilai_akhir', 'n.nilai_angka', 'n.nilai_mutu', '.n.matakuliah AS mk', 'n.dosen'])
            ->innerJoin('#__siak_nilai AS n ON n.id = r.nilai_id')
        ;

        $query->select(['mk.title AS kodeMK', 'mk.alias AS matakuliah'])
            ->leftJoin('#__siak_matakuliah AS mk ON mk.id = n.matakuliah')
        ;

        $query->where($db->qn('r.id').' = '.(int) $pk);

        $db->setQuery($query);

        try {
            $result = $db->loadObject();
        } catch (RuntimeException $e) {
            $this->setError($e->getMessage());

            return false;
        }

        return $result;
    }

    public function save($data)
    {
        $table = $this->getTable();

        $key = $table->getKeyName();
        $pk = \JFactory::getApplication()->input->getInt($key);
        //$pk = (!empty($data[$key])) ? $data[$key] : (int) $this->getState($this->getName().'.id');

        try {
            // Load the row if saving an existing record.
            if ($pk > 0) {
                $table->load($pk);
            }

            // Bind the data.
            if (!$table->bind($data)) {
                $this->setError($table->getError());

                return false;
            }

            // Check the data.
            if (!$table->check()) {
                $this->setError($table->getError());

                return false;
            }

            // Store the data.
            if (!$table->store()) {
                $this->setError($table->getError());

                return false;
            }

            // Clean the cache.
            $this->cleanCache();
        } catch (\Exception $e) {
            $this->setError($e->getMessage());

            return false;
        }

        $tableNilai = $this->getTable('Nilai');
        $tableNilai->load($data['nilai_id']);
        $nilai = ['status' => 'REMIDIAL'];
        $tableNilai->bind($nilai);
        $tableNilai->check();
        $tableNilai->store();

        if (isset($table->{$key})) {
            $this->setState($this->getName().'.id', $table->{$key});
        }

        return true;
    }

    /**
     * Method to load Bobot Nilai.
     */
    public function getBobotNilai()
    {
        $db = JFactory::getDbo();

        $query = $db->getQuery(true);
        $query->select(['title', 'alias', 'bobot'])
            ->from($db->qn('#__siak_bobot_nilai'))
            ->where($db->qn('state').' = 1')
            ->order($db->qn('id').' ASC')
        ;

        $db->setQuery($query);

        try {
            $result = $db->loadObjectList();
        } catch (\Throwable $th) {
            $this->setError($th->getMessage());

            return false;
        }

        return $result;
    }

    /**
     * Stock method to auto-populate the model state.
     *
     * @since   1.6
     */
    protected function populateState()
    {
        $table = $this->getTable();
        $key = $table->getKeyName();

        // Get the pk of the record from the request.
        $pk = \JFactory::getApplication()->input->getInt($key);
        $this->setState($this->getName().'.id', $pk);

        // Load the parameters.
        $value = \JComponentHelper::getParams($this->option);
        $this->setState('params', $value);
    }

    protected function loadFormData()
    {
        return $this->getItem();
    }
}
