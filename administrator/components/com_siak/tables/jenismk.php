<?php

defined('_JEXEC') or die();

class TableJenismk extends JTable
{
    public function __construct(&$db)
    {
        $this->setColumnAlias('published', 'state');
        parent::__construct('#__siak_jenis_mk', 'id', $db);
    }
}
