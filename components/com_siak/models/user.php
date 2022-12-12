<?php

use Joomla\Utilities\ArrayHelper;

class SiakModelUser extends JModelAdmin
{
    public function getTable($type = 'User', $prefix = 'Table', $config = [])
    {
        return JTable::getInstance($type, $prefix, $config);
    }

    public function getForm($data = [], $loadData = true)
    {
        $form = $this->loadForm('com_siak.user', 'user', ['control' => 'jform', 'load_data' => $loadData]);
        if (empty($form)) {
            return false;
        }

        $grpUsr = JFactory::getUser()->get('groups');
        $grpMhs = JComponentHelper::getParams('com_siak')->get('grpMahasiswa');

        if (in_array($grpMhs, $grpUsr)) {
            // prepare form for Mahasiswa
            $form->removeField('nidn');
            $form->removeField('nik');
            $form->setFieldAttribute('tipe_user', 'label', 'COM_SIAK_BIODATA_TIPE_MAHASISWA_LABEL');
        } else {
            $form->removeField('angkatan');
            $form->setFieldAttribute('no_ktp', 'required', 'true');
        }

        return $form;
    }

    public function loadFormData()
    {
        return $this->getItem();
    }

    public function getItem($pk = null)
    {
        $uid = JFactory::getUser()->id;
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);

        $query->select(['s.name as Nama', 's.username AS NPM', 'p.alias as Program_studi', 'j.alias as konsentrasi', 'k.title as kelas_mahasiswa', 'su.*'])
            ->from($db->qn('#__siak_user', 'su'))
            ->leftJoin('#__siak_prodi AS p ON p.id=su.prodi')
            ->leftJoin('#__siak_jurusan AS j ON j.id = su.jurusan')
            ->leftJoin('#__siak_kelas_mahasiswa AS k ON k.id=su.kelas')
            ->leftJoin('#__users as s on s.id = su.user_id')
        ;

        $query->where($db->qn('su.user_id').' = '.(int) $uid);
        $db->setQuery($query);

        try {
            $result = $db->loadObject();
        } catch (\Throwable $th) {
            $this->setError($th->getMessage());

            return false;
        }

        return $result;
        /*
        $table = $this->getTable();
        $uid = JFactory::getUser()->id;

        if (empty($pk)) {
            $ret = $table->load(['user_id' => $uid]);
        } else {
            $ret = $table->load($pk);
        }

        if (!$ret && $table->getError()) {
            $this->setError($table->getError());

            return false;
        }

        $prop = $table->getProperties(true);

        return ArrayHelper::toObject($prop, '\JObject');
        */
    }

    public function getUser()
    {
        $table = $this->getTable();
        $uid = JFactory::getUser()->id;
        if ($table->load(['user_id' => $uid])) {
            $data = $table->getProperties();

            return (array) $data;
        }

        return null;
    }
}
