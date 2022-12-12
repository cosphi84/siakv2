<?php

defined('_JEXEC') or die();

class TablePaketmk extends JTable
{
    public function __construct(&$db)
    {
        $this->setColumnAlias('published', 'state');
        parent::__construct('#__siak_paket_mk', 'id', $db);
    }
}
