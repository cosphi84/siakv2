<?php

defined('_JEXEC') or die();

class TableRombel extends JTable
{
    public function __construct(&$db)
    {
        $this->setColumnAlias('published', 'state');
        parent::__construct('#__siak_rombel', 'id', $db);
    }
}
