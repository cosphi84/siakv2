<?php

defined('_JEXEC') or exit;

class TrasnkripHelper
{
    /**
     * Get Payment Status by Mahasiswa ID at Semester.
     *
     * @param mixed $mahasiswa Mahasiswa ID
     * @param mixed $semester  Semesters
     *
     * @return true on Lunas, false otherwise
     */
    public static function getPaymentStatus($mahasiswa, $semester)
    {
        $app = JFactory::getApplication();
        $user = JFactory::getUser($mahasiswa);
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('COUNT(id)')
            ->from($db->qn('#__siak_pembayaran'))
            ->where($db->qn('user_id').' = '.(int) $user->id)
            ->where($db->qn('semester').' = '.(int) $semester)
            ->where($db->qn('lunas').' = 2')
            ->where($db->qn('confirm').' = 1')
        ;

        try {
            $db->setQuery($query);
            $result = $db->loadResult();
        } catch (RuntimeException $err) {
            $app->enqueueMessage('Error on load data Payment Status!: '.$err->getMessage(), 'error');

            return false;
        }

        if ($result > 0) {
            return true;
        }

        return false;
    }
}
