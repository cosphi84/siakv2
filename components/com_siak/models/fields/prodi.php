<?php

defined('_JEXEC') or exit;

JFormHelper::loadFieldClass('list');

class JFormFieldProdi extends JFormFieldList
{
    protected $type = 'Prodi';
    protected $loadExternally = 0;

    public function getOptions()
    {
        $app = JFactory::getApplication();
        $mhsState = $app->getUserState('com_siak.mahasiswa.status', '');

        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select(['id', 'title as prodi', 'alias'])->from($db->qn('#__siak_prodi'));

        if (is_numeric($mhsState->prodi)) {
            $query->where($db->qn('id').' = '.$db->q($mhsState->prodi));
        }
        $query->where($db->qn('title').' != '.$db->q('Non Prodi'));
        //$query->where($db->qn('s.jurusan').' = '.$db->q($mhsState['jurusan']));
        $query->where($db->qn('state').' = 1');

        $db->setQuery($query);

        try {
            $result = $db->loadObjectList();
        } catch (RuntimeException $err) {
            $app->enqueueMessage($err->getMessage(), 'error');

            return false;
        }

        foreach ($result as $key => $value) {
            $opts[] = JHtmlSelect::option($value->id, $value->prodi.' ('.$value->alias.')');
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
