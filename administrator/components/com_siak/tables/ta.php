<?php

defined('_JEXEC') or die();

class TableTa extends JTable
{
    public function __construct(&$db)
    {
        $this->setColumnAlias('published', 'state');
        parent::__construct('#__siak_ta', 'id', $db);
    }
}
