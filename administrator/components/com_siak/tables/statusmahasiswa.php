<?php

defined('_JEXEC') or die();

class TableStatusmahasiswa extends JTable
{
    public function __construct(&$db)
    {
        $this->setColumnAlias('published', 'confirm');
        parent::__construct('#__siak_mhs_status', 'id', $db);
    }
}
