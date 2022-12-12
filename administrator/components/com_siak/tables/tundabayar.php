<?php

defined('_JEXEC') or exit();

class SiakTableTundaBayar extends JTable
{
    public function __construct(&$db)
    {
        parent::__construct('#__siak_penangguhan_bayar', 'id', $db);
    }
}
