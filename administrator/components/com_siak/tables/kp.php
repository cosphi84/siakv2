<?php

defined('_JEXEC') or die();

class TableKp extends JTable
{
    public function __construct(&$db)
    {
        $this->setColumnAlias('published', 'state');
        parent::__construct('#__siak_kp', 'id', $db);
    }
}
