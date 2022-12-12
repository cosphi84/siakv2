<?php

defined('_JEXEC') or die;

class TableLatepay extends JTable
{
    public function __construct(&$db)
    {
        $this->setColumnAlias('published', 'state');
        parent::__construct('#__siak_penangguhan_bayar', 'id', $db);
    }
}
