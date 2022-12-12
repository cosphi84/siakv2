<?php

defined('_JEXEC') or exit;

class SiakModelJurusan extends JModelAdmin
{
    protected $_item;

    public function __construct($config)
    {
        $config = array_merge(
            [
                'event_after_delete' => 'onJurusanAfterDelete',
                'event_after_save' => 'onJurusanAfterSave',
                'event_before_delete' => 'onJurusanBeforeDelete',
                'event_before_save' => 'onJurusanBeforeSave',
            ],
            $config
        );
        parent::__construct($config);
    }

    public function getTable($type = 'Jurusan', $prefix = 'Table', $config = [])
    {
        return JTable::getInstance($type, $prefix, $config);
    }

    public function getForm($data = [], $loadData = true)
    {
        $form = $this->loadForm('com_siak.jurusan', 'jurusan', ['control' => 'jform', 'load_data' => $loadData]);
        if (empty($form)) {
            return false;
        }

        return $form;
    }

    public function loadFormData()
    {
        $data = JFactory::getApplication()->getUserState('com_siak.edit.jurusan.data', []);
        if (empty($data)) {
            $data = $this->getItem();
        }

        return $data;
    }

    public function getItem($pk = null)
    {
        $pk = (!empty($pk)) ? $pk : (int) $this->getState('jurusan.id');

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
        $pk = (!empty($data['id'])) ? $data['id'] : (int) $this->getState('jurusan.id');
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

        $this->setState('jurusan.id', $pk);

        return true;
    }
}
