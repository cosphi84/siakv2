<?php

defined('_JEXEC') or die();

use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\Form\FormHelper;

FormHelper::loadFieldClass('list');

class JFormFieldListdosen extends JFormFieldList
{
    protected $type = 'Listdosen';
    protected $loadExternally = 0;

    public function getOptions()
    {
        $siak = ComponentHelper::getParams('com_siak');
        $grpMhs = $siak->get('grpDosen', 0);

        $db = Factory::getDbo();
        $query = $db->getQuery(true);


        $query = $db->getQuery(true);
        $query->select(array('a.id as value','a.name as text'))
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
            $opts[] = JHtmlSelect::option($value->value, $value->text);
        }

        if (!$this->loadExternally) {
            $opts = array_merge(parent::getOptions(), $opts);
        }

        return $opts;

    }

    public function getOptionsExternally()
    {
        $this->loadExternally = 1;

        return $this->addOption();
    }
}
