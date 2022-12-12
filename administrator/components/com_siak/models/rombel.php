<?php

defined('_JEXEC') or die;

class SiakModelRombel extends JModelAdmin
{
    public function getTable($type = 'Nilai', $prefix = 'Table', $config = [])
    {
        return JTable::getInstance($type, $prefix, $config);
    }

    public function getForm($data = [], $loadData = true)
    {
        $form = $this->loadForm('com_siak.rombel', 'rombel', ['control' => 'jform', 'load_data' => $loadData]);
        if (empty($form)) {
            return false;
        }

        return $form;
    }

    public function loadFormData()
    {
        $data = JFactory::getApplication()->getUserState('com_siak.edit.rombel.data', []);
        if (empty($data)) {
            $data = $this->getItem();
        }

        return $data;
    }

    public function getItem($pk = null)
    {
        $pk = (!empty($pk)) ? $pk : (int) $this->getState('rombel.id');

        if (null === $this->_item) {
            $this->_item = [];
        }

        if (!isset($this->_item[$pk])) {
            $this->_item[$pk] = parent::getItem($pk);
        }

        $user = JFactory::getUser($this->_item[$pk]->user_id);

        return $this->_item[$pk];
    }
}
