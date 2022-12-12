<?php

use Joomla\CMS\Factory;

defined('_JEXEC') or exit;

JFormHelper::loadFieldClass('list');

class JFormFieldListprodi extends JFormFieldList
{
    protected $type = 'Listprodi';
    protected $loadExternally = 0;

    public function getOptions()
    {
        $user = Factory::getUser();
        $db = Factory::getDbo();
        $query = $db->getQuery(true);


        $query = $db->getQuery(true);
        $query->select(array('id','title','alias'))
                ->from($db->quoteName('#__siak_prodi'))
                ->where($db->quoteName('state'). ' = '. 1);
        $db->setQuery($query);

        try {
            $result = $db->loadObjectList();
        } catch (RuntimeException $e) {
            throw new Exception($e->getMessage());
        }



        foreach ($result as $key => $value) {
            $opts[] = JHtmlSelect::option($value->id, $value->title.' ('.$value->alias.')');
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
