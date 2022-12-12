<?php

defined('_JEXEC') or die();

class TableRuangan extends JTable
{
    public function __construct(&$db)
    {
        $this->setColumnAlias('published', 'state');
        parent::__construct('#__siak_ruangan', 'id', $db);
    }
}
