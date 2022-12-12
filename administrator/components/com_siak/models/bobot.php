<?php

defined('_JEXEC') or exit;

class SiakModelBobot extends JModelAdmin
{
    protected $_item;

    public function __construct($config)
    {
        $config = array_merge(
            [
                'event_after_delete' => 'onBobotAfterDelete',
                'event_after_save' => 'onBobotAfterSave',
                'event_before_delete' => 'onBobotBeforeDelete',
                'event_before_save' => 'onBobotBeforeSave',
            ],
            $config
        );
        parent::__construct($config);
    }

    public function getTable($type = 'Bobot', $prefix = 'Table', $config = [])
    {
        return JTable::getInstance($type, $prefix, $config);
    }

    public function getForm($data = [], $loadData = true)
    {
        $form = $this->loadForm('com_siak.bobot', 'bobotnilai', ['control' => 'jform', 'load_data' => $loadData]);
        if (empty($form)) {
            return false;
        }

        return $form;
    }

    public function loadFormData()
    {
        $data = JFactory::getApplication()->getUserState('com_siak.edit.bobot.data', []);
        if (empty($data)) {
            $data = $this->getItem();
        }

        return $data;
    }
}
