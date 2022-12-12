<?php

defined('_JEXEC') or exit;

class SiakModelDu extends JModelAdmin
{
    protected $_item;

    public function __construct($config)
    {
        $config = array_merge(
            [
                'event_after_delete' => 'onDuAfterDelete',
                'event_after_save' => 'onDuAfterSave',
                'event_before_delete' => 'onDuBeforeDelete',
                'event_before_save' => 'onDuBeforeSave',
            ],
            $config
        );
        parent::__construct($config);
    }

    public function getTable($type = 'Statusmahasiswa', $prefix = 'Table', $config = [])
    {
        return JTable::getInstance($type, $prefix, $config);
    }

    public function getForm($data = [], $loadData = true)
    {
        $form = $this->loadForm('com_siak.daftarulang', 'daftarulang', ['control' => 'jform', 'load_data' => $loadData]);
        if (empty($form)) {
            return false;
        }

        return $form;
    }

    public function loadFormData()
    {
        $data = JFactory::getApplication()->getUserState('com_siak.edit.daftarulang.data', []);
        if (empty($data)) {
            $data = $this->getItem();
        }

        return $data;
    }

    public function getItem($pk = null)
    {
        $pk = (!empty($pk)) ? $pk : (int) $this->getState('daftarulang.id');

        if (null === $this->_item) {
            $this->_item = [];
        }

        if (!isset($this->_item[$pk])) {
            $this->_item[$pk] = parent::getItem($pk);
        }
        $uid = JFactory::getApplication()->getUserState('com_siak.edit.daftarulang.userID', '0');
        if (empty($this->_item[$pk]->user_id)) {
            $this->_item[$pk]->user_id = $uid;
        }

        return $this->_item[$pk];
    }

    public function save($data)
    {
        $pk = (!empty($data['id'])) ? $data['id'] : (int) $this->getState('daftarulang.id');
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

        $this->setState('daftarulang.id', $pk);

        return true;
    }

    public function setStatus(&$pks, $value = 1)
    {
        $dispatcher = \JEventDispatcher::getInstance();
        $user = \JFactory::getUser();
        $table = $this->getTable();
        $table->setColumnAlias('published', 'status');
        $pks = (array) $pks;

        // Include the plugins for the change of state event.
        \JPluginHelper::importPlugin($this->events_map['change_state']);

        // Access checks.
        foreach ($pks as $i => $pk) {
            $table->reset();

            if ($table->load($pk)) {
                if (!$this->canEditState($table)) {
                    // Prune items that you can't change.
                    unset($pks[$i]);

                    \JLog::add(\JText::_('JLIB_APPLICATION_ERROR_EDITSTATE_NOT_PERMITTED'), \JLog::WARNING, 'jerror');

                    return false;
                }

                // If the table is checked out by another user, drop it and report to the user trying to change its state.
                if (property_exists($table, 'checked_out') && $table->checked_out && ($table->checked_out != $user->id)) {
                    \JLog::add(\JText::_('JLIB_APPLICATION_ERROR_CHECKIN_USER_MISMATCH'), \JLog::WARNING, 'jerror');

                    // Prune items that you can't change.
                    unset($pks[$i]);

                    return false;
                }

                /**
                 * Prune items that are already at the given state.  Note: Only models whose table correctly
                 * sets 'published' column alias (if different than published) will benefit from this.
                 */
                $publishedColumnName = $table->getColumnAlias('published');

                if (property_exists($table, $publishedColumnName) && $table->get($publishedColumnName, $value) == $value) {
                    unset($pks[$i]);

                    continue;
                }
            }
        }

        // Check if there are items to change
        if (!count($pks)) {
            return true;
        }

        // Attempt to change the state of the records.
        if (!$table->publish($pks, $value, $user->get('id'))) {
            $this->setError($table->getError());

            return false;
        }

        $context = $this->option.'.'.$this->name;

        // Trigger the change state event.
        $result = $dispatcher->trigger($this->event_change_state, [$context, $pks, $value]);

        if (in_array(false, $result, true)) {
            $this->setError($table->getError());

            return false;
        }

        // Clear the component's cache
        $this->cleanCache();

        return true;
    }
}
