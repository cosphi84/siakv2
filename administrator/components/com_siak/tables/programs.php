<?php

defined('_JEXEC') or exit();

class TablePrograms extends JTable
{
    public function __construct(&$db)
    {
        $this->setColumnAlias('published', 'state');
        parent::__construct('#__siak_programs_pembelajaran', 'id', $db);
    }
}
