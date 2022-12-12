<?php

defined('_JEXEC') or exit;

class MahasiswaHelper
{
    public static function getData($mahasiswa_id = null)
    {
        if ($mahasiswa_id > 0) {
            $app = JFactory::getApplication();
            $db = JFactory::getDbo();
            $query = $db->getQuery(true);
            $query->select('u.*')
                ->from($db->qn('#__siak_user', 'u'))
                ->where($db->qn('u.user_id').' = '.(int) $mahasiswa_id)
            ;
            $query->select('p.alias as programstudi')
                ->leftJoin('#__siak_prodi AS p ON p.id = u.prodi')
            ;

            $query->select('j.alias as konsentrasi')
                ->leftJoin('#__siak_jurusan AS j ON j.id = u.jurusan')
            ;

            try {
                $db->setQuery($query);
                $data = $db->loadObject();
            } catch (\Exception $e) {
                $app->enqueueMessage($e->getMessage());

                return false;
            }

            return $data;
        }

        return false;
    }
}
