<?php

defined('_JEXEC') or exit;

class SiakModelSk extends JModelAdmin
{
    protected $_item;

    public function __construct($config)
    {
        $config = array_merge(
            [
                'event_after_delete' => 'onSkAfterDelete',
                'event_after_save' => 'onSkAfterSave',
                'event_before_delete' => 'onSkBeforeDelete',
                'event_before_save' => 'onSkBeforeSave',
            ],
            $config
        );
        parent::__construct($config);
    }

    public function getTable($type = 'Sk', $prefix = 'Table', $config = [])
    {
        return JTable::getInstance($type, $prefix, $config);
    }

    public function getForm($data = [], $loadData = true)
    {
        $form = $this->loadForm('com_siak.sk', 'sk', ['control' => 'jform', 'load_data' => $loadData]);
        if (empty($form)) {
            return false;
        }

        return $form;
    }

    public function loadFormData()
    {
        $data = JFactory::getApplication()->getUserState('com_siak.edit.prodi.data', []);
        if (empty($data)) {
            $data = $this->getItem();
        }

        return $data;
    }

    public function getItem($pk = null)
    {
        $pk = (!empty($pk)) ? $pk : (int) $this->getState('prodi.id');

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
        $pk = (!empty($data['id'])) ? $data['id'] : (int) $this->getState('prodi.id');
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

        $this->setState('prodi.id', $pk);

        return true;
    }

    public function delete(&$pks)
    {
        $dispatcher = \JEventDispatcher::getInstance();
        $pks = (array) $pks;
        $table = $this->getTable();

        jimport('joomla.filesystem.file');
        foreach ($pks as $k => $v) {
            $table->load($v);
            $filenames = $table->getProperties();
            $path = 'media/com_siak/files/sk/';
            $pathName = JPath::clean($path.$filenames['file']);
            $fullPath = JPATH_ROOT.'/'.$pathName;
            if (JFile::exists($fullPath)) {
                JFile::delete($fullPath);
            }
            $table->reset();
        }

        parent::delete($pks);

        return true;
    }

    protected function canDelete($record)
    {
        if (!empty($record->id)) {
            return JFactory::getUser()->authorise('core.delete', 'com_siak.sk.'.$record->id);
        }
    }
}
