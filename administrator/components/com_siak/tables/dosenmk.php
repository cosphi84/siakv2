<?php

defined('_JEXEC') or die();

class TableDosenmk extends JTable
{
    public function __construct(&$db)
    {
        $this->setColumnAlias('published', 'state');
        parent::__construct('#__siak_dosen_mk', 'id', $db);
    }
}
