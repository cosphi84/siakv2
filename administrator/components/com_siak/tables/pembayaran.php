<?php

defined('_JEXEC') or exit();

class TablePembayaran extends JTable
{
    public function __construct(&$db)
    {
        parent::__construct('#__siak_pembayaran', 'id', $db);
    }
}
