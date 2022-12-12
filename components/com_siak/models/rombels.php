<?php

defined('_JEXEC') or exit;

class SiakModelRombels extends JModelList
{
    public function __construct($cfg = [])
    {
        if (empty($cfg['filter_fields'])) {
            $cfg['filter_fields'] = [
                'ta',
            ];
        }

        parent::__construct($cfg);
    }

    public function getForm($data = [], $loadData = true)
    {
        $form = $this->loadForm($this->context.'.filter', 'filter_rombels', ['control' => '', 'load_data' => $loadData]);
        if (empty($form)) {
            return false;
        }

        return $form;
    }

    protected function getListQuery()
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $app = JFactory::getApplication();

        $serach = $this->getState('filter.search');
        $ta = $this->getState('filter.ta', '');
        $kelas = $this->getState('filter.kelas');

        $query->select('n.id')
            ->from($db->qn('#__siak_nilai', 'n'))
        ;
        $query->select(['u.name as mahasiswa', 'u.username as npm'])
            ->join('LEFT', $db->qn('#__users', 'u').' ON u.id = n.user_id')
            ;
        $query->select('m.title as matakuliah')
            ->join('LEFT', $db->qn('#__siak_matakuliah', 'm').' ON m.id = n.matakuliah')
            ;

        $query->select('s.title as semester')
            ->join('LEFT', $db->qn('#__siak_semester', 's').' ON s.id=n.semester')
        ;

        $query->where($db->qn('n.matakuliah').' = '.(int) $serach);
        $query->where($db->qn('n.tahun_ajaran').' = '.$db->q($ta));
        $query->where($db->qn('n.state').' >= 1');
        $query->where($db->qn('n.kelas').' = '.(int) $kelas);

        return $query;
    }

    protected function populateState($ordering = 'u.username', $dir = 'ASC')
    {
        $this->setState('filter.ta', $this->getUserStateFromRequest($this->context.'.filter.ta', 'filter_ta', '', 'string'));
        $this->setState('filter.kelas', $this->getUserStateFromRequest($this->context.'.filter.kelas', 'filter_kelas', '', 'string'));

        parent::populateState($ordering, $dir);
    }
}
