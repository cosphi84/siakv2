<?php

defined('_JEXEC') or die();

class TableUser extends JTable
{
    public function __construct(&$db)
    {
        $this->setColumnAlias('published', 'reset');
        parent::__construct('#__siak_user', 'id', $db);
    }
}
