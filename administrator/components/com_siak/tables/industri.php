<?php

defined('_JEXEC') or exit();

class TableIndustri extends JTable
{
    public function __construct(&$db)
    {
        $this->setColumnAlias('published', 'state');
        parent::__construct('#__siak_industri', 'id', $db);
    }
}
