<?php

defined('_JEXEC') or exit;

use Joomla\Utilities\ArrayHelper;

class SiakModelKrs extends JModelAdmin
{
    public function __construct($config)
    {
        $config = array_merge(
            [
                'event_after_delete' => 'onKrsAfterDelete',
                'event_after_save' => 'onKrsAfterSave',
                'event_before_delete' => 'onKrsBeforeDelete',
                'event_before_save' => 'onKrsBeforeSave',
            ],
            $config
        );
        parent::__construct($config);
    }

    public function getForm($data = [], $loadData = true)
    {
        $form = $this->loadForm('com_siak.krs', 'krs', ['control' => 'jform', 'load_data' => $loadData]);
        if (empty($form)) {
            return false;
        }

        return $form;
    }

    public function loadFormData()
    {
        $data = JFactory::getApplication()->getUserState('com_siak.edit.krs.data', []);
        if (empty($data)) {
            $data = $this->getItem();
        }

        return $data;
    }

    /**
     * method to get KRS Item.
     *
     * @param null       $id ID of KRS
     * @param null|mixed $pk
     *
     * @return mixed KRS item or false on error
     */
    public function getItem($pk = null)
    {
        $id = (!empty($pk)) ? $pk : (int) \JFactory::getApplication()->input->get('id', 0, 'int');

        $db = JFactory::getDbo();
        $query = $db->getQuery(true);

        $query->select(['k.id', 'k.ttl_sks', 'k.prodi', 'k.jurusan', 'k.tahun_ajaran', 'k.created_time', 'k.confirm_dw', 'k.confirm_note'])
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

        $dataMKS = $this->getKRSItems($id);

        return (object) array_merge((array) $result, (array) $dataMKS);
    }

    /**
     * Method to load Table class.
     *
     * @param mixed $type
     * @param mixed $prefix
     * @param mixed $config
     */
    public function getTable($type = 'Krs', $prefix = 'Table', $config = [])
    {
        return JTable::getInstance($type, $prefix, $config);
    }

    public function delete(&$pks)
    {
        $dispatcher = \JEventDispatcher::getInstance();
        $pks = (array) $pks;
        $table = $this->getTable();

        // Include the plugins for the delete events.
        \JPluginHelper::importPlugin($this->events_map['delete']);

        // Iterate the items to delete each one.
        foreach ($pks as $i => $pk) {
            if ($table->load($pk)) {
                if ($this->canDelete($table)) {
                    $context = $this->option.'.'.$this->name;

                    // Trigger the before delete event.
                    $result = $dispatcher->trigger($this->event_before_delete, [$context, $table]);

                    if (in_array(false, $result, true)) {
                        $this->setError($table->getError());

                        return false;
                    }

                    if (!$table->delete($pk)) {
                        $this->setError($table->getError());

                        return false;
                    }

                    // Trigger the after event.
                    $dispatcher->trigger($this->event_after_delete, [$context, $table]);
                } else {
                    // Prune items that you can't change.
                    unset($pks[$i]);
                    $error = $this->getError();

                    if ($error) {
                        \JLog::add($error, \JLog::WARNING, 'jerror');

                        return false;
                    }

                    \JLog::add(\JText::_('JLIB_APPLICATION_ERROR_DELETE_NOT_PERMITTED'), \JLog::WARNING, 'jerror');

                    return false;
                }
            } else {
                $this->setError($table->getError());

                return false;
            }
        }

        // Clear the component's cache
        $this->cleanCache();

        return true;
    }

    protected function canDelete($record)
    {
        return \JFactory::getUser()->authorise('core.delete', $this->option);
    }

    /**
     * Method to load all Matakuliah Items from KRS MK items based on KRS id.
     *
     * @param null|mixed $id ID of krs
     *
     * @return mixed Matakuliah Items of false on error
     */
    protected function getKRSItems($id = null)
    {
        $db = \JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select(['m.title as kodeMK', 'm.alias as MK', 'm.sks'])
            ->from($db->qn('#__siak_matakuliah', 'm'))
            ->join('LEFT', $db->qn('#__siak_krs_items', 'ki').' ON ki.matakuliah=m.id')
            ->where($db->qn('ki.krs').' = '.(int) $id)
        ;
        $db->setQuery($query);

        try {
            $result = $db->loadObjectList();
        } catch (RuntimeException $error) {
            $this->setError($error->getMessage());

            return false;
        }

        return $result;
    }

    protected function getBiodata($user_id)
    {
        $table = $this->getTable('user');
        if (null != $user_id) {
            $return = $table->load(['user_id' => $user_id]);
            if (false === $return && $table->getError()) {
                $this->setError($table->getError());

                return false;
            }
        }

        $properties = $table->getProperties(1);

        $data = ArrayHelper::toObject($properties, '\JObject');
        $data->mahasiswa = \JFactory::getUser($user_id);

        return $data;
    }
}
