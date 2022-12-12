<?php

use Joomla\CMS\MVC\Model\AdminModel;
use Joomla\CMS\Table\Table;
use Joomla\Utilities\ArrayHelper;
use Joomla\Registry\Registry;

defined('_JEXEC') or exit;

class SiakModelJadwal extends AdminModel
{
    protected $_item;

    public function __construct($config)
    {
        $config = array_merge(
            [
                'event_after_delete' => 'onJadwalAfterDelete',
                'event_after_save' => 'onJadwalAfterSave',
                'event_before_delete' => 'onJadwalBeforeDelete',
                'event_before_save' => 'onJadwalBeforeSave',
            ],
            $config
        );
        parent::__construct($config);
    }

    public function getTable($type = 'Kbm', $prefix = 'Table', $config = [])
    {
        return Table::getInstance($type, $prefix, $config);
    }

    public function getForm($data = [], $loadData = true)
    {
        $form = $this->loadForm('com_siak.jadwal', 'jadwal', ['control' => 'jform', 'load_data' => $loadData]);
        if (empty($form)) {
            return false;
        }

        return $form;
    }


    public function loadFormData()
    {
        return $this->getItem();
    }

    public function getItem($pk = null)
    {
        $pk = (!empty($pk)) ? $pk : (int) $this->getState($this->getName() . '.id');
        $table = $this->getTable();


        if ($pk > 0) {
            // Attempt to load the row.
            $return = $table->load($pk);

            // Check for a table object error.
            if ($return === false && $table->getError()) {
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

    public function save($data)
    {
        $pk = (!empty($data['id'])) ? $data['id'] : (int) $this->getState('jadwal.id');
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

        $this->setState('jadwal.id', $pk);

        return true;
    }
}
