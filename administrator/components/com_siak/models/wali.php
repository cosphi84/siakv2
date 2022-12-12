<?php

defined('_JEXEC') or exit;

class SiakModelWali extends JModelAdmin
{
    protected $_item;

    public function __construct($config)
    {
        $config = array_merge(
            [
                'event_after_delete' => 'onWaliAfterDelete',
                'event_after_save' => 'onWaliAfterSave',
                'event_before_delete' => 'onWaliBeforeDelete',
                'event_before_save' => 'onWaliBeforeSave',
            ],
            $config
        );
        parent::__construct($config);
    }

    public function getTable($type = 'Dosenwali', $prefix = 'Table', $config = [])
    {
        return JTable::getInstance($type, $prefix, $config);
    }

    public function getForm($data = [], $loadData = true)
    {
        $form = $this->loadForm('com_siak.wali', 'wali', ['control' => 'jform', 'load_data' => $loadData]);
        if (empty($form)) {
            return false;
        }

        return $form;
    }

    public function loadFormData()
    {
        $data = JFactory::getApplication()->getUserState('com_siak.edit.wali.data', []);
        if (empty($data)) {
            $data = $this->getItem();
        }

        return $data;
    }

    public function getItem($pk = null)
    {
        $pk = (!empty($pk)) ? $pk : (int) $this->getState('wali.id');

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
        $pk = (!empty($data['id'])) ? $data['id'] : (int) $this->getState('wali.id');
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

        $this->setState('wali.id', $pk);

        return true;
    }
}
