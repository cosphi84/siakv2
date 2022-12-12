<?php

defined('_JEXEC') or exit;

JFormHelper::loadFieldClass('list');

class JFormFieldJurusans extends JFormFieldList
{
    protected $type = 'Jurusans';
    protected $loadExternally = 0;

    public function getOptions()
    {
        $app = JFactory::getApplication();
        $input = $app->input;
        $com = $input->get('option', '', 'cmd');
        $view = $input->get('view', '', 'cmd');
        $context = $com.'.'.$view;
        $prodi = $app->getUserStateFromRequest($context.'.default.filter.prodi', 'filter_prodi', '', 'string');

        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select(['j.id, j.title, j.alias', 'p.title as prodi']);
        $query->from($db->qn('#__siak_jurusan', 'j'));
        $query->join('LEFT', $db->qn('#__siak_prodi', 'p').' ON '.$db->qn('p.id').' = '.$db->qn('j.prodi'));
        if (!empty($prodi)) {
            $query->where($db->qn('p.id').' = '.(int) $prodi);
        }
        $query->where($db->qn('j.state').' = '.$db->q('1'));
        $query->order($db->qn('j.prodi').' ASC');

        $db->setQuery($query);

        try {
            $result = $db->loadObjectList();
        } catch (RuntimeException $err) {
            return $err->getMessage();
        }

        foreach ($result as $res) {
            $opts[] = JHtmlSelect::option($res->id, $res->prodi.': '.$res->title);
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
