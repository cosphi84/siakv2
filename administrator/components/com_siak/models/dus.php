<?php

defined('_JEXEC') or exit('Direct Access Forbidden!');

JLoader::register('Siak', JPATH_COMPONENT_ADMINISTRATOR.'/helpers/siak.php');

class SiakModelDus extends JModelList
{
    public function __construct($config = [])
    {
        if (empty($config['filter_fields'])) {
            $config['filter_fields'] = [
                'published',
                'confirmed',
                'prodi',
                'm.state',
                'u.npm',
                'ta',
            ];
        }
        parent::__construct($config);
    }

    protected function getListQuery()
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $search = $this->getState('filter.search');
        $prodi = $this->getState('filter.prodi');
        $jurusan = $this->getState('filter.jurusan');
        $semester = $this->getState('filter.semester');
        $status = $this->getState('filter.published');
        $confirm = $this->getState('filter.confirm');
        $ta = $this->getState('filter.ta');

        empty($ta) ? $ta = Siak::getTA() : $ta;

        $query->select(['ms.id', 'ms.create_date', 'ms.status', 'ms.ta', 'ms.confirm'])
            ->from($db->qn('#__siak_mhs_status', 'ms'))
        ;
        $query->where($db->qn('ms.prodi').' = '.(int) $prodi);
        $query->where($db->qn('ms.ta').' = '.$db->q($ta));

        if (!empty($semester)) {
            $query->where($db->qn('ms.semester').' = '.(int) $semester);
        }

        if (is_numeric($status)) {
            $query->where($db->qn('ms.status').' = '.(int) $status);
        }

        if (is_numeric($confirm)) {
            $query->where($db->qn('ms.confirm').' = '.(int) $confirm);
        } else {
            $query->where($db->qn('ms.confirm').' IN (0,1)');
        }

        $query->select(['u.name as mahasiswa, u.username as npm'])
            ->leftJoin('#__users AS u ON u.id = ms.user_id')
        ;

        if (!empty($search)) {
            $search = $db->quote('%'.$search.'%');
            $searchs = [];
            $searchs[] = $db->qn('u.name').' LIKE '.$search;
            $searchs[] = $db->qn('u.username').' LIKE '.$search;
            $query->where('('.implode(' OR ', $searchs).' )');
        }

        $query->select('p.title as prodi')
            ->leftJoin('#__siak_prodi AS p ON p.id=ms.prodi')
        ;

        $query->select('j.title as jurusan')
            ->leftJoin('#__siak_jurusan AS j ON j.id=ms.jurusan')
        ;
        $query->select('s.title AS semester')
            ->leftJoin('#__siak_semester AS s ON s.id = ms.semester')
        ;

        $query->select('k.title as kelas')
            ->leftJoin('#__siak_kelas_mahasiswa AS k ON k.id=ms.kelas')
        ;

        $query->order($db->qn('s.title').','.$db->qn('u.username'));

        return $query;
    }

    protected function populateState($ordering = 's.title,u.name', $direction = 'ASC')
    {
        $app = JFactory::getApplication('administrator');
        if ($layout = $app->input->get('layout', 'default', 'cmd')) {
            $this->context .= '.'.$layout;
        }
        $format = $app->input->get('format', 'html', 'cmd');

        $this->setState('filter.prodi', $this->getUserStateFromRequest($this->context.'.filter.prodi', 'filter_prodi', '', 'string'));
        $this->setState('filter.jurusan', $this->getUserStateFromRequest($this->context.'.filter.jurusan', 'filter_jurusan', '', 'string'));
        $this->setState('filter.ta', $this->getUserStateFromRequest($this->context.'.filter.ta', 'filter_ta', '', 'string'));
        $this->setState('filter.semester', $this->getUserStateFromRequest($this->context.'.filter.semester', 'filter_semester', '', 'string'));
        $this->setState('filter.published', $this->getUserStateFromRequest($this->context.'.filter.published', 'filter_published', '', 'string'));
        $this->setState('filter.confirm', $this->getUserStateFromRequest($this->context.'.filter.confirmed', 'filter_confirmed', '', 'string'));

        parent::populateState($ordering, $direction);

        if ('html' !== $format) {
            $this->setState('list.limit', '');
        }
    }

    protected function getStoreId($id = '')
    {
        // Compile the store id.
        $id .= ':'.$this->getState('filter.search');
        $id .= ':'.$this->getState('filter.published');
        $id .= ':'.$this->getState('filter.confirm');

        return parent::getStoreId($id);
    }
}
