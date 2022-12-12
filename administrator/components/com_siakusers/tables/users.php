<?php


defined('_JEXEC') or die;

use Joomla\CMS\Table\Table;

class SiakusersTableUsers extends Table
{
    public function __construct(&$db)
    {
        parent::__construct('#__siak_user', 'id', $db);
    }
}
