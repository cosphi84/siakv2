<?php

defined('_JEXEC') or exit;

JFormHelper::loadFieldClass('list');

class JFormFieldSemester extends JFormFieldList
{
    protected $type = 'Semester';
    protected $loadExternally = 0;

    public function getOptions()
    {
        $app = JFactory::getApplication();
        $mhsState = $app->getUserState('com_siak.mahasiswa.status', '');

        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select(['s.id', 's.title'])->from($db->qn('#__siak_semester', 's'));
        $query->select('j.title as jurusan');
        $query->select('p.title as prodi');
        $query->join('LEFT', $db->qn('#__siak_jurusan', 'j').' ON '.$db->qn('j.id').' = '.$db->qn('s.jurusan'));
        $query->join('LEFT', $db->qn('#__siak_prodi', 'p').' ON '.$db->qn('p.id').' = '.$db->qn('s.prodi'));

        if (is_numeric($mhsState->prodi)) {
            $query->where($db->qn('s.prodi').' = '.(int) $mhsState->prodi);
        }
        /*
        if (is_numeric($mhsState->jurusan)) {
            $query->where($db->qn('s.jurusan').' = '.(int) $mhsState->jurusan);
        }
        
        } else {
            $query->group($db->qn('s.prodi'));
        }
		*/
        $query->where('s.state = 1');
        $query->order('s.prodi, s.title  ASC');

        $db->setQuery($query);

        try {
            $result = $db->loadObjectList();
        } catch (RuntimeException $err) {
            $app->enqueueMessage($err->getMessage(), 'error');

            return false;
        }

        foreach ($result as $key => $value) {
            $opts[] = JHtmlSelect::option($value->id, $value->title.' ('.$value->prodi.':'.$value->jurusan.')');
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
