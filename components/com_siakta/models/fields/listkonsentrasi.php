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
        $app = Factory::getApplication();
        $option = $app->input->get('option');
        $name = $app->input->get('view');
        $context = $option.'.'.$name.'.prodi';

        $prodi = $app->getUserState($context);

        $user = Factory::getUser();
        $db = Factory::getDbo();
        $query = $db->getQuery(true);

        $query->select(array('a.jurusan as id', 'b.title', 'b.alias'))
                ->from($db->quoteName('#__siak_user', 'a'))
                ->leftJoin('#__siak_jurusan AS b ON b.id = a.jurusan')
                ->where($db->quoteName('user_id'). ' = '. (int) $user->id);
        $db->setQuery($query);

        try{
            $result = $db->loadObjectList();
        }catch(RuntimeException $e)
        {
            throw new Exception($e->getMessage());
        }

        if(empty($result))
        {
            $query = $db->getQuery(true);
            $query->select(array('id','title','alias'))
                ->from($db->quoteName('#__siak_jurusan'));

            if(is_numeric($prodi))
            {
                $query->where($db->quoteName('prodi'). ' = ' . (int) $prodi);
            }

            $query->where($db->quoteName('state'). ' = '. 1);
            $db->setQuery($query);

            try{
                $result = $db->loadObjectList();
            }catch(RuntimeException $e)
            {
                throw new Exception($e->getMessage());
            }            
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
