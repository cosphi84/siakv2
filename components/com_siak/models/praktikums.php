<?php

defined('_JEXEC') or die;

class SiakModelPraktikums extends JModelList
{
    public function __construct($cfg = [])
    {
        if (empty($cfg['filter_fields'])) {
            $cfg['filter_fields'] = [
                'matakuliah',
                'mahasiswa',
                'ta',
            ];
        }
        parent::__construct($cfg);
    }

    public function getForm($data = [], $loadData = true)
    {
        $form = $this->loadForm($this->context.'.filter', 'filter_praktikums', ['control' => '', 'load_data' => $loadData]);
        if (empty($form)) {
            return false;
        }

        return $form;
    }

    public function getListQuery()
    {
        $db = JFactory::getDbo();
        $layout = \JFactory::getApplication()->input->get('layout', 'default', 'cmd');

        $search = $this->getState('filter.search');
        $ta = $this->getState('filter.ta', '');

        $query = $db->getQuery(true);

        $query->select(['p.id', 'p.status'])
            ->from($db->qn('#__siak_praktikum', 'p'))
        ;

        $query->select(['u.name as mahasiswa', 'u.username AS npm'])
            ->join('LEFT', $db->qn('#__users', 'u').' ON u.id = p.    user_id')
        ;

        $query->select(['m.title as kodeMK', 'm.alias as mk'])
            ->join('LEFT', $db->qn('#__siak_matakuliah', 'm').' ON m.id=p.matakuliah')
        ;

        $query->select('pr.title as prodi')
            ->join('LEFT', $db->qn('#__siak_prodi', 'pr').' ON pr.id=p.prodi')
        ;

        $query->select('j.title as jurusan')
            ->join('LEFT', $db->qn('#__siak_jurusan', 'j').' ON j.id=p.jurusan')
        ;

        $query->select('k.title as kelas')
            ->join('LEFT', $db->qn('#__siak_kelas_mahasiswa', 'k').' ON k.id=p.kelas')
        ;

        $query->select('s.title as semester')
            ->join('LEFT', $db->qn('#__siak_semester', 's').' ON s.id=p.semester')
        ;

        if ('default' == $layout) {
            if (!empty($search)) {
                $search = $db->quote('%'.$search.'%');

                $searchs = [];
                $searchs[] = $db->qn('u.name').' LIKE '.$search;
                $searchs[] = $db->qn('u.username').' LIKE '.$search;
                $query->where('('.implode(' OR ', $searchs).' )');
            } else {
                $query->where($db->qn('u.id').' = 0');
            }
        } else {
            $query->where($db->qn('p.matakuliah').' = '.$db->q($search));
            $query->where($db->qn('p.ta').' = '.$db->q($ta));
        }

        $query->where($db->qn('p.status').'>= 1');

        return $query;
    }

    protected function populateState($order = null, $dir = 'ASC')
    {
        $layout = \JFactory::getApplication()->input->get('layout', 'default', 'cmd');
        $this->setState('filter.ta', $this->getUserStateFromRequest($this->context.'.filter.ta', 'filter_ta', '', 'string'));

        'mahasiswa' == $layout ? $order = 'p.title' : $order = 'u.username';

        $params = JComponentHelper::getParams('com_siak');
        $grpMhas = $params->get('grpMahasiswa');
        $grpUser = JFactory::getUser()->get('groups');
        if (in_array($grpMhas, $grpUser)) {
            $this->setState('filter.search', \JFactory::getUser()->username);
        }
        parent::populateState($order, $dir);
    }
}
