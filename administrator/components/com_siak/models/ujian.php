<?php

defined('_JEXEC') or exit;

use Joomla\CMS\Date\Date;
use Joomla\Utilities\ArrayHelper;

JLoader::register('Siak', JPATH_COMPONENT_ADMINISTRATOR.'/helpers/siak.php');

class SiakModelUjian extends JModelAdmin
{
    protected $_item;

    public function __construct($config)
    {
        $config = array_merge(
            [
                'event_after_delete' => 'onUjianAfterDelete',
                'event_after_save' => 'onUjianAfterSave',
                'event_before_delete' => 'onUjianBeforeDelete',
                'event_before_save' => 'onUjianBeforeSave',
            ],
            $config
        );
        parent::__construct($config);
    }

    public function getTable($type = 'Ujian', $prefix = 'Table', $config = [])
    {
        return JTable::getInstance($type, $prefix, $config);
    }

    public function getForm($data = [], $loadData = true)
    {
        $form = $this->loadForm('com_siak.ujian', 'ujian', ['control' => 'jform', 'load_data' => $loadData]);
        if (empty($form)) {
            return false;
        }

        return $form;
    }

    public function loadFormData()
    {
        $data = JFactory::getApplication()->getUserState('com_siak.edit.ujian.data', []);
        if (empty($data)) {
            $data = $this->getItem();
        }

        return $data;
    }

    public function getItem($pk = null)
    {
        $pk = (!empty($pk)) ? $pk : (int) $this->getState('ujian.id');

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
        $pk = (!empty($data['id'])) ? $data['id'] : (int) $this->getState('ujian.id');

        if (empty($pk) || $pk < 1) {
            $data['created_user'] = JFactory::getUser()->id;
            $tanggal = new Date('now', JFactory::getUser()->getParam('timezone', 'Asia/Jakarta'));
            $data['created_time'] = $tanggal->toSql();
        }

        return parent::save($data);
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

                    // Multilanguage: if associated, delete the item in the _associations table
                    if ($this->associationsContext && \JLanguageAssociations::isEnabled()) {
                        $db = $this->getDbo();
                        $query = $db->getQuery(true)
                            ->select('COUNT(*) as count, '.$db->quoteName('as1.key'))
                            ->from($db->quoteName('#__associations').' AS as1')
                            ->join('LEFT', $db->quoteName('#__associations').' AS as2 ON '.$db->quoteName('as1.key').' =  '.$db->quoteName('as2.key'))
                            ->where($db->quoteName('as1.context').' = '.$db->quote($this->associationsContext))
                            ->where($db->quoteName('as1.id').' = '.(int) $pk)
                            ->group($db->quoteName('as1.key'))
                        ;

                        $db->setQuery($query);
                        $row = $db->loadAssoc();

                        if (!empty($row['count'])) {
                            $query = $db->getQuery(true)
                                ->delete($db->quoteName('#__associations'))
                                ->where($db->quoteName('context').' = '.$db->quote($this->associationsContext))
                                ->where($db->quoteName('key').' = '.$db->quote($row['key']))
                            ;

                            if ($row['count'] > 2) {
                                $query->where($db->quoteName('id').' = '.(int) $pk);
                            }

                            $db->setQuery($query);
                            $db->execute();
                        }
                    }

                    $prop = $table->getProperties(1);
                    $item = ArrayHelper::toObject($prop, '\JObject');

                    Siak::deleteBerkas($item->dokumen_kerjasama, 'ujian');

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
}
