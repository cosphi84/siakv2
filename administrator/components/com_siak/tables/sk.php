<?php

defined('_JEXEC') or die();

class TableSk extends JTable
{
    public function __construct(&$db)
    {
        $this->setColumnAlias('published', 'state');
        parent::__construct('#__siak_sk', 'id', $db);
    }

    public function bind($array, $ignore = '')
    {
        if (isset($array['rules']) && is_array($array['rules'])) {
            $rules = new JAccessRules($array['rules']);
            $this->setRules($rules);
        }

        return parent::bind($array, $ignore);
    }

    protected function _getAssetName()
    {
        return 'com_siak.sk.'.(int) $this->_tbl_key;
    }
}
