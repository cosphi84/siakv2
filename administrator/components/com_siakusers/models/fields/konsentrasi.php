<?php

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Form\FormHelper;

FormHelper::loadFieldClass('list');

class JFormFieldKonsentrasi extends JFormFieldList
{
    protected $type = 'Konsentrasi';
    protected $loadExternally = 0;

    public function getOptions()
    {
        $db = Factory::getDbo();
        $query = $db->getQuery(true);

        $prodi = Factory::getApplication()->getUserStateFromRequest('com_siakusers.filter.prodi_id', 'filter.prodi_id');

        $query->select(
            array(
                $db->quoteName('id', 'value'),
                $db->quoteName('alias', 'text')
            )
        );
        $query->from($db->quoteName('#__siak_jurusan'))
        ->where($db->quoteName('state'). ' = '. 1)
        ->order($db->quoteName('id'). ' ASC');


        $query->where($db->quoteName('prodi'). ' = '. (int) $prodi);


        $db->setQuery($query);

        try {
            $jurusans = $db->loadObjectList();
        } catch(RuntimeException $e) {
            Factory::getApplication()->enqueueMessage($e->getMessage());
        }

        if (!count($jurusans)) {
            return parent::getOptions();
        }

        foreach ($jurusans as $jurusan) {
            $opts[] = JHtmlSelect::option($jurusan->value, $jurusan->text);
        }

        if (!$this->loadExternally) {
            $opts = array_merge(parent::getOptions(), $opts);
        }

        return $opts;
    }

    public function getOptionsExternally()
    {
        $this->loadExternally = 1;

        return $this->getOptions();
    }
}
