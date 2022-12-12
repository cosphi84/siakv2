<?php

defined('_JEXEC') or die;

class SiakModelKonfirmasiku extends JModelList
{
    public function getListQuery()
    {
        $db = JFactory::getDbo();
        $uid = JFactory::getUser()->id;
        $query = $db->getQuery(true);

        $query->select(['k.id', 'k.confirm', 'k.create_date', 'k.status', 'k.ta']);
        $query->select('s.title as semester');
        $query->select('p.title as prodi');
        $query->select('j.title as jurusan');
        $query->select('c.title as kelas');

        $query->from($db->qn('#__siak_mhs_status', 'k'));
        $query->join('LEFT', $db->qn('#__siak_semester', 's').' ON '.$db->qn('s.id').' = '.$db->qn('k.semester'));
        $query->join('LEFT', $db->qn('#__siak_prodi', 'p').' ON '.$db->qn('p.id').' = '.$db->qn('k.prodi'));
        $query->join('LEFT', $db->qn('#__siak_jurusan', 'j').' ON '.$db->qn('j.id').' = '.$db->qn('k.jurusan'));
        $query->join('LEFT', $db->qn('#__siak_kelas_mahasiswa', 'c').' ON '.$db->qn('c.id').' = '.$db->qn('k.kelas'));

        $query->where($db->qn('k.user_id').' = '.$db->q($uid));
        $query->order($db->qn('s.title').' ASC');

        return $query;
    }
}
