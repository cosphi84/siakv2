<?php

defined('_JEXEC') or exit;

class SiakModelKelas extends JModelAdmin
{
    protected $_item;

    public function __construct($config)
    {
        $config = array_merge(
            [
                'event_after_delete' => 'onKelasAfterDelete',
                'event_after_save' => 'onKelasAfterSave',
                'event_before_delete' => 'onKelasBeforeDelete',
                'event_before_save' => 'onKelasBeforeSave',
            ],
            $config
        );
        parent::__construct($config);
    }

    public function getTable($type = 'Kelasmhs', $prefix = 'Table', $config = [])
    {
        return JTable::getInstance($type, $prefix, $config);
    }

    public function getForm($data = [], $loadData = true)
    {
        $form = $this->loadForm('com_siak.kelas', 'kelas', ['control' => 'jform', 'load_data' => $loadData]);
        if (empty($form)) {
            return false;
        }

        return $form;
    }

    public function loadFormData()
    {
        $data = JFactory::getApplication()->getUserState('com_siak.edit.kelas.data', []);
        if (empty($data)) {
            $data = $this->getItem();
        }

        return $data;
    }

    public function getItem($pk = null)
    {
        $pk = (!empty($pk)) ? $pk : (int) $this->getState('kelas.id');

        if (null === $this->_item) {
            $this->_item = [];
        }

        if (!isset($this->_item[$pk])) {
            $this->_item[$pk] = parent::getItem($pk);
        }

        return $this->_item[$pk];
    }

    public function save($data)
    {
        $pk = (!empty($data['id'])) ? $data['id'] : (int) $this->getState('kelas.id');
        $table = $this->getTable();
        $table->load($pk);
        if (!$table->bind($data)) {
            $this->setError($table->getError());

            return false;
        }

        if (!$table->save($data)) {
            $this->setError($table->getError());

            return false;
        }

        $this->setState('kelas.id', $pk);

        return true;
    }
}
