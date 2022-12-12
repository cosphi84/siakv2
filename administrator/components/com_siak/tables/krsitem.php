<?php

defined('_JEXEC') or die();

class TableKrsitem extends JTable
{
    public function __construct(&$db)
    {
        parent::__construct('#__siak_krs_items', 'id', $db);
    }
}
