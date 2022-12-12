<?php

defined('_JEXEC') or exit;

class SiakModelUser extends JModelAdmin
{
    protected $_item;

    public function __construct($config)
    {
        $config = array_merge(
            [
                'event_after_delete' => 'onUserAfterDelete',
                'event_after_save' => 'onUserAfterSave',
                'event_before_delete' => 'onUserBeforeDelete',
                'event_before_save' => 'onUserBeforeSave',
            ],
            $config
        );
        parent::__construct($config);
    }

    public function getTable($type = 'User', $prefix = 'Table', $config = [])
    {
        return JTable::getInstance($type, $prefix, $config);
    }

    public function getForm($data = [], $loadData = true)
    {
        $form = $this->loadForm('com_siak.user', 'user', ['control' => 'jform', 'load_data' => $loadData]);
        if (empty($form)) {
            return false;
        }
        $id = JFactory::getApplication()->input->get('id', 0, 'int');
        $uid = $this->getUid($id);

        $serID = JFactory::getApplication()->getUserState('com_siak.edit.user.userID', '0');
        $id > 0 ? $serID = $uid : $serID;

        $grpUsr = JFactory::getUser($serID)->get('groups');
        $grpMhs = JComponentHelper::getParams('com_siak')->get('grpMahasiswa');

        if (in_array($grpMhs, $grpUsr)) {
            // prepare form for Mahasiswa
            $form->removeField('nidn');
            $form->removeField('nik');
        } else {
            $form->removeField('angkatan');
        }

        return $form;
    }

    public function loadFormData()
    {
        $data = JFactory::getApplication()->getUserState('com_siak.edit.user.data', []);
        if (empty($data)) {
            $data = $this->getItem();
        }

        return $data;
    }

    public function getItem($pk = null)
    {
        $pk = (!empty($pk)) ? $pk : (int) $this->getState('user.id');

        if (null === $this->_item) {
            $this->_item = [];
        }

        if (!isset($this->_item[$pk])) {
            $this->_item[$pk] = parent::getItem($pk);
        }
        $uid = JFactory::getApplication()->getUserState('com_siak.edit.user.userID', '0');
        if ('0' != $uid) {
            $user = JFactory::getUser($uid)->name;
            $this->_item[$pk]->user = $user;
        }

        if (empty($this->_item[$pk]->user_id)) {
            $this->_item[$pk]->user_id = $uid;
        }

        return $this->_item[$pk];
    }

    public function save($data)
    {
        $pk = (!empty($data['id'])) ? $data['id'] : (int) $this->getState('user.id');
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

        $this->setState('user.id', $pk);

        return true;
    }

    public function getRecordID($uid)
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('id');
        $query->from($db->qn('#__siak_user'));
        $query->where($db->qn('user_id').' = '.$db->q($uid));
        $db->setQuery($query);

        try {
            $result = $db->loadResult();
        } catch (RuntimeException $err) {
            return $err->getMessage();
        }

        $this->setState('user.id', $result);

        return $result;
    }

    public function getUid($recordID)
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('user_id');
        $query->from($db->qn('#__siak_user'));
        $query->where($db->qn('id').' = '.$db->q($recordID));
        $db->setQuery($query);

        try {
            $result = $db->loadResult();
        } catch (RuntimeException $err) {
            return $err->getMessage();
        }

        return $result;
    }

    public function resetBio()
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);

        $query->update($db->qn('#__siak_user'));
        $query->set($db->qn('reset').' = '.$db->q('1'));
        $query->where($db->qn('id').' > '.$db->q('0'));
        $db->setQuery($query);

        return $db->execute();
    }
}
