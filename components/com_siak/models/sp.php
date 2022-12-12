<?php

defined('_JEXEC') or exit;

use Joomla\Utilities\ArrayHelper;

class SiakModelSp extends JModelAdmin
{
    public function getForm($data = [], $loadData = true)
    {
        $form = $this->loadForm(
            'com_siak.form-sp',
            'daftar-sp',
            [
                'control' => 'jform',
                'load_data' => $loadData,
            ]
        );

        if (empty($form)) {
            $errors = $this->getErrors();

            throw new Exception(implode("\n", $errors), 500);
        }

        return $form;
    }

    public function getItem($pk = null)
    {
        $pk = (!empty($pk)) ? $pk : (int) $this->getState('sp.id');
        if ('' == $pk) {
            $this->setError('No ID tidak ada!');

            return false;
        }

        $user = JFactory::getUser();
        $db = $this->getDbo();
        $query = $db->getQuery(true);

        $query->select(['n.id', 'n.nilai_akhir', 'n.nilai_angka', 'n.nilai_mutu', 'n.tahun_ajaran', 'n.user_id'])
            ->from($db->qn('#__siak_nilai', 'n'))
        ;

        $query->select(['m.title AS kodemk', 'm.alias AS matakuliah'])
            ->leftJoin('#__siak_matakuliah AS m ON m.id = n.matakuliah')
        ;

        $query->select(['u.name as mahasiswa', 'u.username AS npm'])
            ->leftJoin('#__users AS u ON u.id = n.user_id')
        ;

        $query->select(['s.title AS semester'])
            ->leftJoin('#__siak_semester AS s ON s.id=n.semester')
        ;
        $query->where($db->qn('n.id').' = '.(int) $pk);
        $db->setQuery($query);

        try {
            $result = $db->loadObject();
        } catch (RuntimeException $e) {
            $this->setError($e->getMessage());

            return false;
        }

        if ($result->user_id !== $user->id) {
            $this->setError('Kesalaan Hak Akses nilai!');

            return false;
        }

        return $result;
    }

    public function loadNilai($pk = null)
    {
        if (empty($pk)) {
            $this->setError('No ID!');

            return false;
        }

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

        if (property_exists($item, 'params')) {
            $registry = new Registry($item->params);
            $item->params = $registry->toArray();
        }

        return $item;
    }

    public function getTable($type = 'Nilai', $prefix = 'Table', $config = [])
    {
        return JTable::getInstance($type, $prefix, $config);
    }

    public function save($dataNilai = [])
    {
        $table = $this->getTable('Sp');
        if (is_object($dataNilai)) {
            $dataNilai = ArrayHelper::fromObject($dataNilai);
        }

        try {
            $table->load(['nilai_id' => $dataNilai['nilai_id']]);
            if (!$table->bind($dataNilai)) {
                $this->setError($table->getError());

                return false;
            }
            $this->prepareTable($table);
            if (!$table->check()) {
                $this->setError($table->getError());

                return false;
            }
            if (!$table->store()) {
                $this->setError($table->getError());

                return false;
            }
            $this->cleanCache();
        } catch (\Exception $e) {
            $this->setError($e->getMessage());

            return false;
        }

        return true;
    }

    protected function loadFormData()
    {
        $data = JFactory::getApplication()->getUserState(
            $this->option.'.edit.sp.data',
            []
        );

        if (empty($data)) {
            $data = $this->getItem();
        }

        return $data;
    }
}
