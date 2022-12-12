<?php
/**
 * @package     com_siakta
 * @subpackage  Table Ta
 *
 * @copyright   (C) 2022 @ Risam, S.T
 * @license     Limited for FT-UNTAG Cirebon use Only
 */

use Joomla\CMS\Table\Table;

defined('_JEXEC') or die;

class TableSiakusers extends Table
{
    public function __construct(&$db)
    {
        parent::__construct('#__siak_user', 'id', $db);
    }
}