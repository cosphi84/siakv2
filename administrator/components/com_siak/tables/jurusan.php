<?php

defined('_JEXEC') or die();

class TableJurusan extends JTable
{
    public function __construct(&$db)
    {
        $this->setColumnAlias('published', 'state');
        parent::__construct('#__siak_jurusan', 'id', $db);
    }
}
