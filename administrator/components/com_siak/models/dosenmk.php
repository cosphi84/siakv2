<?php

use Joomla\CMS\Factory;
use Joomla\CMS\MVC\Model\AdminModel;
use Joomla\CMS\Table\Table;

defined('_JEXEC') or exit;

class SiakModelDosenmk extends AdminModel
{
    protected $_item;

    public function __construct($config)
    {
        $config = array_merge(
            [
                'event_after_delete' => 'onDosenmkAfterDelete',
                'event_after_save' => 'onDosenmkAfterSave',
                'event_before_delete' => 'onDosenmkBeforeDelete',
                'event_before_save' => 'onDosenmkBeforeSave',
            ],
            $config
        );
        parent::__construct($config);
    }

    public function getTable($type = 'Dosenmk', $prefix = 'Table', $config = [])
    {
        return Table::getInstance($type, $prefix, $config);
    }

    public function getForm($data = [], $loadData = true)
    {
        $form = $this->loadForm('com_siak.dosenmk', 'dosenmk', ['control' => 'jform', 'load_data' => $loadData]);
        if (empty($form)) {
            return false;
        }

        return $form;
    }

    public function loadFormData()
    {
        $data = Factory::getApplication()->getUserState('com_siak.edit.dosenmk.data', []);
        if (empty($data)) {
            $data = $this->getItem();
        }

        return $data;
    }

    public function getItem($pk = null)
    {
        $pk = (!empty($pk)) ? $pk : (int) $this->getState('dosenmk.id');
        JLoader::register('Siak', JPATH_COMPONENT_ADMINISTRATOR.'/helpers/siak.php');

        if (null === $this->_item) {
            $this->_item = [];
        }

        if (!isset($this->_item[$pk])) {
            $this->_item[$pk] = parent::getItem($pk);
        }
        if (empty($this->_item[$pk]->tahun_ajaran)) {
            $this->_item[$pk]->tahun_ajaran = Siak::getTA();
        }

        return $this->_item[$pk];
    }

    public function save($data)
    {
        $pk = (!empty($data['id'])) ? $data['id'] : (int) $this->getState('dosenmk.id');
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

        $this->setState('dosenmk.id', $pk);

        return true;
    }
}
