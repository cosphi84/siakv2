<?php

defined('_JEXEC') or die();

class TableSp extends JTable
{
    public function __construct(&$db)
    {
        $this->setColumnAlias('published', 'state');
        parent::__construct('#__siak_sp', 'id', $db);
    }
}
