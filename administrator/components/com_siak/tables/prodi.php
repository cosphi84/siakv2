<?php

defined('_JEXEC') or die();

class TableProdi extends JTable
{
    public function __construct(&$db)
    {
        $this->setColumnAlias('published', 'state');
        parent::__construct('#__siak_prodi', 'id', $db);
    }
}
