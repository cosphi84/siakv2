<?php

defined('_JEXEC') or exit();

class SiakTableRevisinilai extends JTable
{
    public function __construct(&$db)
    {
        parent::__construct('#__siak_revisi_nilai', 'id', $db);
    }
}
