<?php

defined('_JEXEC') or exit;

class SiakModelDosenwali extends JModelList
{
    public function __construct($cfg = [])
    {
        if (empty($cfg['filter_fields'])) {
            $cfg['filter_fields'] = [
                'mahasiswa',
            ];
        }

        parent::__construct($cfg);
    }

    protected function getListQuery()
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);

        $query->select('dw.angkatan')
            ->from($db->qn('#__siak_dosen_wali', 'dw'))
        ;

        $query->where($db->qn('.dw.status').' = 1');

        $query->select('u.name as dosen')
            ->join('LEFT', $db->qn('#__users', 'u').' ON u.id = dw.user_id')
        ;

        $query->select('p.alias as prodi')
            ->join('LEFT', $db->qn('#__siak_prodi', 'p').' ON p.id=dw.prodi')
        ;

        $query->select('k.title as kelas')
            ->join('LEFT', $db->qn('#__siak_kelas_mahasiswa', 'k').' ON k.id = dw.kelas')
        ;

        return $query;
    }
}
