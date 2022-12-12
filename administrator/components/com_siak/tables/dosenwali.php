<?php

defined('_JEXEC') or die();

class TableDosenwali extends JTable
{
    public function __construct(&$db)
    {
        $this->setColumnAlias('published', 'status');
        parent::__construct('#__siak_dosen_wali', 'id', $db);
    }
}
