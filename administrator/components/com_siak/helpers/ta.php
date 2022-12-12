<?php

defined('_JEXEC') or exit;

class TaHelpers
{
    public static function getTA($mahasiswa_id = 0)
    {
        if ($mahasiswa_id > 0) {
            $app = JFactory::getApplication();
            $db = JFactory::getDbo();
            $query = $db->getQuery(true);
            $query->select(['dosbing_1 AS dosbing1', 'dosbing_2 AS dosbing2', 'judul', 'tanggal_seminar', 'tanggal_lulus', 'nilai_akhir', 'nilai_angka', 'file'])
                ->from($db->qn('#__siak_ta'))
                ->where($db->qn('user_id').' = '.(int) $mahasiswa_id)
            ;

            try {
                $db->setQuery($query);
                $data = $db->loadAssoc();
            } catch (\Exception $e) {
                $app->enqueueMessage($e->getMessage());

                return false;
            }

            return $data;
        }

        return false;
    }
}
