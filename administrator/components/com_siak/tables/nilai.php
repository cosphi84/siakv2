<?php

defined('_JEXEC') or exit();

class TableNilai extends JTable
{
    public function __construct(&$db)
    {
        $this->setColumnAlias('published', 'state');

        parent::__construct('#__siak_nilai', 'id', $db);
    }
}
