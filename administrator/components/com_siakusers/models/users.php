<?php

defined('_JEXEC') or die;

use Joomla\CMS\MVC\Model\ListModel;

class SiakusersModelUsers extends ListModel
{
    public function __construct($config = array())
    {
        if (empty($config['filter_fields'])) {
            $config['filter_fields'] = array(
                'id', 'prodi_id', 'konsentrasi_id', 'member_id', 'kelas_id',
                'a.name'
            );
        }

        parent::__construct($config);
    }

    protected function getListQuery()
    {
        $db = $this->getDbo();
        $query = $db->getQuery(true);

        $query->select(
            $this->getState(
                'list.select',
                array(
                $db->quoteName('a.id'),
                $db->quoteName('a.angkatan'),
                $db->quoteName('a.nik'),
                $db->quoteName('a.nidn'),
                $db->quoteName('a.state'),
                $db->quoteName('u.name', 'nama'),
                $db->quoteName('u.username', 'npm'),
                $db->quoteName('u.block'),
                $db->quoteName('p.alias', 'prodi'),
                $db->quoteName('k.alias', 'konsentrasi'),
                $db->quoteName('kls.title', 'kelas_mhs'),
            )
            )
        )
        ->from($db->quoteName('#__siak_user', 'a'))
        ->join('LEFT', $db->quoteName('#__users', 'u'). ' ON '. $db->quoteName('u.id'). ' = '. $db->quoteName('a.user_id'))
        ->join('LEFT', $db->quoteName('#__siak_prodi', 'p'). ' ON '. $db->quoteName('p.id'). ' = '. $db->quoteName('a.prodi'))
        ->join('LEFT', $db->quoteName('#__siak_jurusan', 'k'). ' ON '. $db->quoteName('k.id'). ' = '. $db->quoteName('a.jurusan'))
        ->join('LEFT', $db->quoteName('#__siak_kelas_mahasiswa', 'kls'). ' ON '. $db->quoteName('kls.id'). ' = '. $db->quoteName('a.kelas'));

        $mode = $this->getUserStateFromRequest('com_siakuser.mode', 'mode', 0, 'int');
        $this->setState('mode', $mode);

        if ($mode == 0) {
            $query->where($db->quoteName('a.tipe_user'). ' = '. (int)  1);
        } else {
            $query->where($db->quoteName('a.tipe_user'). ' > '. (int)  1);
        }

        $prodi = $this->getState('filter.prodi_id');
        if (is_numeric($prodi)) {
            $query->where($db->quoteName('a.prodi'). ' = '. (int) $prodi);
        }

        $konsentrasi = $this->getState('filter.konsentrasi_id');

        if (is_numeric($konsentrasi)) {
            $query->where($db->quoteName('a.jurusan'). ' = '. (int) $konsentrasi);
        }

        $kelas = $this->getState('filter.kelas_id');
        if (is_numeric($kelas)) {
            $query->where($db->quoteName('a.kelas'). ' = '. (int) $kelas);
        }
        //echo $query->dump();
        return $query;
    }

    /**
     * Get the filter form
     *
     * @param   array    $data      data
     * @param   boolean  $loadData  load current data
     *
     * @return  \JForm|boolean  The \JForm object or false on error
     *
     * @since   3.2
     */
    public function getFilterForm($data = array(), $loadData = true)
    {
        $mode = $this->getUserStateFromRequest('com_siakuser.mode', 'mode', 0, 'int');
        $form = null;

        // Try to locate the filter form automatically. Example: ContentModelArticles => "filter_articles"
        if (empty($this->filterFormName)) {
            $classNameParts = explode('Model', get_called_class());

            if (count($classNameParts) == 2) {
                $this->filterFormName = 'filter_' . strtolower($classNameParts[1]);
            }
        }

        if (!empty($this->filterFormName)) {
            // Get the form.
            $form = $this->loadForm($this->context . '.filter', $this->filterFormName, array('control' => '', 'load_data' => $loadData));
        }

        if ($mode) {
            $form->removeField('prodi_id', 'filter');
            $form->removeField('konsentrasi_id', 'filter');
            $form->removeField('kelas_id', 'filter');
        }

        return $form;
    }
}
