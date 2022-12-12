<?php

defined('_JEXEC') or exit;

JFormHelper::loadFieldClass('list');

class JFormFieldJurusan extends JFormFieldList
{
    protected $type = 'Jurusan';
    protected $loadExternally = 0;

    public function getOptions()
    {
        $app = JFactory::getApplication();
        $mhsState = $app->getUserState('com_siak.mahasiswa.status', '');

        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select(['j.id', 'j.title as jurusan'])->from($db->qn('#__siak_jurusan', 'j'));
        $query->select('p.title as prodi')
            ->join('LEFT', $db->qn('#__siak_prodi', 'p').' ON p.id = j.prodi')
        ;
        $query->order($db->qn('j.prodi').' ASC');

        if (is_numeric($mhsState->prodi)) {
            $query->where($db->qn('j.prodi').' = '.$db->q($mhsState->prodi));
        }

        $query->where($db->qn('j.state').' = 1');

        $db->setQuery($query);

        try {
            $result = $db->loadObjectList();
        } catch (RuntimeException $err) {
            $app->enqueueMessage($err->getMessage(), 'error');

            return false;
        }

        foreach ($result as $key => $value) {
            $opts[] = JHtmlSelect::option($value->id, $value->prodi.' - '.$value->jurusan);
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
