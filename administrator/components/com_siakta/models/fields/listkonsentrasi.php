<?php

use Joomla\CMS\Factory;

defined('_JEXEC') or exit;

JFormHelper::loadFieldClass('list');

class JFormFieldListkonsentrasi extends JFormFieldList
{
    protected $type = 'Listkonsentrasi';
    protected $loadExternally = 0;

    public function getOptions()
    {
        $db = Factory::getDbo();
        $query = $db->getQuery(true);


        $query = $db->getQuery(true);
        $query->select(array('a.id','a.title','a.alias', 'b.title as prodi'))
                ->from($db->quoteName('#__siak_jurusan', 'a'));

        $query->order($db->quoteName('a.prodi'). ' ASC');
        $query->leftJoin('#__siak_prodi AS b ON b.id = a.prodi');
        $query->where($db->quoteName('a.state'). ' = '. 1);
        $db->setQuery($query);

        try {
            $result = $db->loadObjectList();
        } catch (RuntimeException $e) {
            throw new Exception($e->getMessage());
        }



        foreach ($result as $key => $value) {
            $opts[] = JHtmlSelect::option($value->id, $value->prodi .' : '.$value->title.' ('.$value->alias.')');
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
