<?php

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Form\FormHelper;

FormHelper::loadFieldClass('list');

class JFormFieldKelasmhs extends JFormFieldList
{
    protected $type = 'Kelasmhs';
    protected $loadExternally = 0;

    public function getOptions()
    {
        $db = Factory::getDbo();
        $query = $db->getQuery(true);

        $query->select(
            array(
                $db->quoteName('id', 'value'),
                $db->quoteName('title', 'text')
            )
        );
        $query->from($db->quoteName('#__siak_kelas_mahasiswa'))
        ->where($db->quoteName('state'). ' = '. 1)
        ->order($db->quoteName('id'). ' ASC');

        $db->setQuery($query);

        try {
            $prodis = $db->loadObjectList();
        } catch(RuntimeException $e) {
            Factory::getApplication()->enqueueMessage($e->getMessage());
        }

        foreach ($prodis as $prodi) {
            $opts[] = JHtmlSelect::option($prodi->value, $prodi->text);
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
