<?php

defined('_JEXEC') or die;

class TablePraktikum extends JTable
{
    public function __construct(&$db)
    {
        $this->setColumnAlias('published', 'status');
        parent::__construct('#__siak_praktikum', 'id', $db);
    }
}
