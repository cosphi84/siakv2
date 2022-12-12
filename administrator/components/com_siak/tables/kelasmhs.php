<?php

defined('_JEXEC') or die();

class TableKelasmhs extends JTable
{
    public function __construct(&$db)
    {
        $this->setColumnAlias('published', 'state');
        parent::__construct('#__siak_kelas_mahasiswa', 'id', $db);
    }
}
