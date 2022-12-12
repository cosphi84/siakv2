<?php

defined('_JEXEC') or die();

class TableKrs extends JTable
{
    public function __construct(&$db)
    {
        $this->setColumnAlias('published', 'confirm_dw');
        parent::__construct('#__siak_krs', 'id', $db);
    }
}
