<?php

use Joomla\CMS\Factory;

defined('_JEXEC') or die;

class com_siaknilaiInstallerScript
{
    public function install($parent)
    {
        $extension = 'com_siaknilai';
        $db = Factory::getDbo();
        $query = $db->getQuery(true);

        $query->insert($db->quoteName('#__action_logs_extensions'))
            ->columns($db->quoteName('extension'))
            ->values($db->quote($extension));

        try {
            $db->setQuery($query);
            $db->execute();
        } catch (RuntimeException $e) {
            Factory::getApplication()->enqueueMessage($e->getMessage());
            return false;
        }

        $logConf = new stdClass();
        $logConf->id = 0;
        $logConf->type_title = 'nilai';
        $logConf->type_alias = $extension.'.form';
        $logConf->id_holder = 'id';
        $logConf->title_holder = 'title';
        $logConf->table_name = '#__siak_nilai';
        $logConf->text_prefix = 'COM_SIAKNILAI';

        try {
            $result = $db->insertObject('#__action_log_config', $logConf);
        } catch (RuntimeException $e) {
            Factory::getApplication()->enqueueMessage($e->getMessage());
            return false;
        }
    }
}
