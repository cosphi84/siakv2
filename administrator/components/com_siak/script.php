<?php

defined('_JEXEC') or exit;

class com_siakInstallerScript
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
        $query->values($db->quote('com_siak'));
        $db->setQuery($query);

        try {
            $result = $db->execute();
        } catch (RuntimeException $ex) {
            JFactory::getApplication()->enqueueMessage($ex->getMessage());

            return false;
        }

        $parent->getParent()->setRedirectURL('index.php?option=com_siak');
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
