<?php

use Joomla\CMS\Factory;
use Joomla\CMS\Component\ComponentHelper;
defined('_JEXEC') or exit;

JFormHelper::loadFieldClass('list');

class JFormFieldMahasiswa extends JFormFieldList
{
    protected $type = 'Mahasiswa';
    protected $loadExternally = 0;

    public function getOptions()
    {
        $siak = ComponentHelper::getParams('com_siak');
        $grpMhs = $siak->get('grpMahasiswa', 0);

        $db = Factory::getDbo();
        $query = $db->getQuery(true);


        $query = $db->getQuery(true);
        $query->select(array('a.id as value','a.name as text', 'a.username as npm'))
                ->from($db->quoteName('#__users', 'a'));
        $query->where($db->quoteName('a.block'). ' = '. 0);
        $query->leftJoin($db->quoteName('#__user_usergroup_map', 'b').' ON '.$db->quoteName('b.user_id'). ' = '. $db->quoteName('a.id'));
        $query->where($db->quoteName('b.group_id'). ' = '. (int) $grpMhs);
        $db->setQuery($query);

        try {
            $result = $db->loadObjectList();
        } catch (RuntimeException $e) {
            throw new Exception($e->getMessage());
        }


        foreach ($result as $key => $value) {
            $opts[] = JHtmlSelect::option($value->value, $value->text .'( '. $value->npm .')');
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
