<?php

defined('_JEXEC') or die();

class TableBobot extends JTable
{
    public function __construct(&$db)
    {
        $this->setColumnAlias('published', 'state');
        parent::__construct('#__siak_bobot_nilai', 'id', $db);
    }
}
