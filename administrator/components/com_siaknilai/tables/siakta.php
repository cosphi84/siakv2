<?php
/**
 * @package     com_siakta
 * @subpackage  Table Ta
 *
 * @copyright   (C) 2022 @ Risam, S.T
 * @license     Limited for FT-UNTAG Cirebon use Only
 */

use Joomla\CMS\Factory;
use Joomla\CMS\Table\Table;

defined('_JEXEC') or die;

class TableSiakta extends Table
{
    public function __construct(&$db)
    {
        $this->setColumnAlias('published', 'state');
        parent::__construct('#__siak_ta', 'id', $db);
    }

    public function check()
    {
        try {
            parent::check();
        } catch (Exception $e) {
            $this->setError($e->getMessage());

            return false;
        }

        if (empty($this->id)) {
            $this->created_on = Factory::getDate()->toSql();
            $this->created_by = Factory::getUser()->id;
        } else {
            $this->last_update_on = Factory::getDate()->toSql();
            $this->last_update_by = Factory::getUser()->id;
        }

        return true;
    }
}
