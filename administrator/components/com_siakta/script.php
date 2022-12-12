<?php

use Joomla\CMS\Factory;

defined('_JEXEC') or die;

class com_siaktaInstallerScript
{
    public function install($parent)
    {
        $extension = 'com_siakta';
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
        $logConf->type_title = 'ta';
        $logConf->type_alias = $extension.'.form';
        $logConf->id_holder = 'id';
        $logConf->title_holder = 'title';
        $logConf->table_name = '#__siak_ta';
        $logConf->text_prefix = 'COM_SIAKTA_TA';

        try {
            $result = $db->insertObject('#__action_log_config', $logConf);
        } catch (RuntimeException $e) {
            Factory::getApplication()->enqueueMessage($e->getMessage());
            return false;
        }
    }
}
