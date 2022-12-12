<?php

defined('_JEXEC') or exit;


class com_siakusersInstallerScript
{
    /**
     * Method Install, dipanggil setelah Ekstensi di install.
     *
     * @param mixed $parent
     */
    public function install($parent)
    {
        // install user action Log
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->insert($db->quoteName('#__action_logs_extensions'));
        $query->columns($db->quoteName('extension'));
        $query->values($db->quote('com_siakusers'));
        $db->setQuery($query);

        try {
            $result = $db->execute();
        } catch (RuntimeException $ex) {
            JFactory::getApplication()->enqueueMessage($ex->getMessage());

            return false;
        }

        $logConf = new stdClass();
        $logConf->id = 0;
        $logConf->type_title = 'transaction';
        $logConf->type_alias = 'com_siakusers';
        $logConf->id_holder = 'id';
        $logConf->title_holder = 'trans_desc';
        $logConf->table_name = '#__siak_users';
        $logConf->text_prefix = 'COM_SIAKUSERS_TRANSACTION';

        try {
            // If it fails, it will throw a RuntimeException
            // Insert the object into the table.
            $result = JFactory::getDbo()->insertObject('#__action_log_config', $logConf);
        } catch (RuntimeException $e) {
            JFactory::getApplication()->enqueueMessage($e->getMessage());
            return false;
        }

        $parent->getParent()->setRedirectURL('index.php?option=com_siakusers');
    }

    /**
     * Method Uninstall, dipanggil setelah Ekstensi diepas.
     *
     * @param mixed $parent
     */
    public function uninstall($parent)
    {
    }

    /**
     * Method Update, fungsi dipanggil setelah komponen diupdate.
     *
     * @param mixed $parent
     */
    public function update($parent)
    {
    }

    /**
     * Methode Preflight, di eksekusi sesaatsebelum komponen di install.
     *
     * @param mixed $type
     * @param mixed $parent
     */
    public function preflight($type, $parent)
    {
    }

    public function postflight($type, $parent)
    {
    }
}
