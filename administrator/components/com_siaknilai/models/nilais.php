<?php

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\MVC\Model\ListModel;
use Joomla\Utilities\ArrayHelper;

class SiaknilaiModelNilais extends ListModel
{
    public function __construct($config = array())
    {
        if (empty($config['filter_fields'])) {
            $config['filter_fields'] = array(
                'id', 'a.matakuliah', 'c.title', 'c.alias', 'd.alias', 'd.alias', 'semester'
            );
        }

        parent::__construct($config);
    }

    protected function getListQuery()
    {
        $db = $this->getDbo();
        $query = $db->getQuery(true);
        $search = $this->getState('filter.search', '0');

        $query->select('a.*')
            ->from($db->quoteName('#__siak_nilai', 'a'))
            ->leftJoin('#__users AS b ON b.id = a.user_id');

        $query->select(array('d.title AS smstr', 'd.alias as sm'))
            ->leftJoin('#__siak_semester AS d ON d.id = a.semester');



        if (strpos($search, ":") > 0) {
            $keywords = explode(':', $search);
            $query->where($db->quoteName('b.username'). ' = '. $db->quote($keywords[0]));
            $query->where($db->quoteName('c.title'). ' = '. $db->quote($keywords[1]));
        } else {
            $query->where($db->quoteName('b.username'). ' = '. $db->quote($search));
        }


        $query->select(array('c.title AS kodemk', 'c.alias AS mk', 'c.sks'))
            ->leftJoin('#__siak_matakuliah AS c ON c.id = a.matakuliah');

        $smt = $this->getState('filter.semester');
        if (is_numeric($smt)) {
            $query->where($db->quoteName('d.alias'). ' = '. (int) $smt);
        }

        $state = $this->getState('filter.state');

        if (is_numeric($state)) {
            $query->where($db->quoteName('a.state'). ' = '. (int) $state);
        } else {
            $query->where($db->quoteName('a.state'). ' = '. 1);
        }

        $orderCol = $this->getState('list.ordering', 'd.title');
        $orderDir = $this->getState('list.direction', 'ASC');

        $query->order($db->escape($orderCol). ' '. $db->escape($orderDir));

        return $query;
    }

    protected function populateState($ordering = 'd.title', $direction = 'ASC')
    {
        $doc = Factory::getDocument();
        $type = $doc->getType();

        parent::populateState($ordering, $direction);

        if ($type !== 'html') {
            $this->setState('list.limit', null);
        }
    }


    public function getMahasiswa()
    {
        $npm = $this->getState('filter.search');
        $mhs = array();
        if (strpos($npm, ':')) {
            $mhs = explode(':', $npm);
        } else {
            $mhs[0] = $npm;
        }

        if (empty($mhs[0])) {
            $return = new stdClass();
            $return->uid = 0;
            $return->name = 'N/a';
            return $return;
        }

        $db = $this->getDbo();
        $query = $db->getQuery(true);
        $query->select(array('u.id as uid', 'u.name', 'u.username'))
            ->from($db->quoteName('#__users', 'u'))
            ->where($db->quoteName('u.username'). ' = '. $db->quote($mhs[0]));

        $query->select('s.*')->leftJoin('#__siak_user AS s ON s.user_id = u.id');
        $query->select('p.alias AS programstudi')->leftJoin('#__siak_prodi AS p ON p.id=s.prodi');
        $query->select('j.alias AS konsentrasi')->leftJoin('#__siak_jurusan AS j ON j.id=s.jurusan');
        try {
            $db->setQuery($query);
            $result = $db->loadObject();
        } catch(RuntimeException $error) {
            $this->setError('Load Mahasiswa Error : '. $error->getMessage());
            return false;
        }

        return $result;
    }

    public function getTugasAkhir()
    {
        $mahasiswa = $this->getMahasiswa();

        $table = $this->getTable('Siakta', 'Table');
        $table->load(array('mahasiswa_id'=>$mahasiswa->uid));
        $data = $table->getProperties(1);

        return ArrayHelper::toObject($data);
    }
}
