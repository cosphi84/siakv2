<?php

use Joomla\CMS\MVC\Model\ListModel;

defined('_JEXEC') or exit;

class SiakModelTranskip extends ListModel
{
    public function __construct($config = [])
    {
        if (empty($config['filter_fields'])) {
            $config['filter_fields'] = [
                'search',
            ];
        }
        parent::__construct($config);
    }

    protected function getListQuery()
    {
        $db = JFactory::getDbo();

        $query = $db->getQuery(true);
        $user = JFactory::getUser();
        $format = $this->getState('doc.format');

        $query->select(['n.id', 'n.user_id', 'n.nilai_akhir', 'n.nilai_angka', 'n.nilai_mutu', 'n.status'])
            ->from($db->qn('#__siak_nilai', 'n'))
        ;

        $search = $this->getState('filter.search');

        if (!is_array($search)) {
            $search = [0];
        }
        foreach ($search as $key => $val) {
            $searchs[] = $val;
        }

        if ('html' == $format) {
            $query->where($db->qn('n.semester').' in ('.implode(',', $search).')');
        }

        /*
        $query->select(['sp.nilai_akhir_remid', 'sp.nilai_remid_angka', 'sp.nilai_remid_mutu'])
            ->leftJoin('#__siak_sp AS sp ON sp.nilai_id = n.id')
        ;
        */
        $query->select(['u.name as mahasiswa', 'u.username as npm'])
            ->innerJoin('#__users AS u ON u.id = n.user_id')
        ;

        $query->where($db->qn('n.user_id').' = '.(int) $user->id);

        $query->select(['m.title as kodemk', 'm.alias as mk', 'm.sks'])
            ->leftJoin('#__siak_matakuliah AS m ON m.id = n.matakuliah')
        ;

        $query->select('jmk.title as jenisMK')
            ->leftJoin('#__siak_jenis_mk AS jmk ON jmk.id = m.type')
        ;

        $query->select(['s.id AS sid', 's.title as semester', 's.alias as smt'])
            ->leftJoin('#__siak_semester AS s ON s.id=n.semester')
        ;

        $query->order($db->qn('n.semester').' ASC');

        return $query;
    }

    protected function populateState($dir = 'n.semester', $order = 'Asc')
    {
        $filter = $this->getUserStateFromRequest($this->context.'.filter.search', 'filter_search', '', 'string');

        $this->setState('filter.search', $filter);
        $app = JFactory::getApplication();
        $params = JComponentHelper::getParams('com_siak');
        $format = $app->input->get('format', 'html', 'cmd');
        $this->setState('params', $params);
        $this->setState('doc.format', $format);
        parent::populateState($dir, $order);

        $this->setState('list.limit', '');
    }
}
