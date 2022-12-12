<?php

defined('_JEXEC') or die();

class TableJenisuser extends JTable
{
    public function __construct(&$db)
    {
        $this->setColumnAlias('published', 'state');
        parent::__construct('#__siak_jenis_user', 'id', $db);
    }
}
