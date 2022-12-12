<?php

defined('_JEXEC') or exit();

class TableUjian extends JTable
{
    public function __construct(&$db)
    {
        $this->setColumnAlias('published', 'state');
        parent::__construct('#__siak_jadwal_ujian', 'id', $db);
    }
}
