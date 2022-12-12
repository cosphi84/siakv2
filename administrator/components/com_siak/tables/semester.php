<?php

defined('_JEXEC') or die();

class TableSemester extends JTable
{
    public function __construct(&$db)
    {
        $this->setColumnAlias('published', 'state');
        parent::__construct('#__siak_semester', 'id', $db);
    }
}
