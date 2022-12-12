<?php

defined('_JEXEC') or exit;

JFormHelper::loadFieldClass('list');

class JFormFieldSemesterdinamis extends JFormFieldList
{
    protected $type = 'Semesterdinamis';
    protected $loadExternally = 0;

    public function getOptions()
    {
        $app = JFactory::getApplication();
        $uri = JUri::getInstance();
        $context = $uri->getVar('option').'.'.$uri->getVar('view').'.filter.prodi';
        $prodi = $app->getUserStateFromRequest($context, 'filter_prodi', '', 'string');

        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select(['s.id', 's.title'])->from($db->qn('#__siak_semester', 's'));
        $query->select('j.title as jurusan');
        $query->select('p.title as prodi');
        $query->join('LEFT', $db->qn('#__siak_jurusan', 'j').' ON '.$db->qn('j.id').' = '.$db->qn('s.jurusan'));
        $query->join('LEFT', $db->qn('#__siak_prodi', 'p').' ON '.$db->qn('p.id').' = '.$db->qn('s.prodi'));

        if ($prodi > 0) {
            $query->where($db->qn('s.prodi').' = '.(int) $prodi);
        }

        $query->where('s.state = 1');

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
