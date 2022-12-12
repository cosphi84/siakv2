<?php

defined('_JEXEC') or exit;

JFormHelper::loadFieldClass('list');

class JFormFieldSemesters extends JFormFieldList
{
    protected $type = 'Semesters';
    protected $loadExternally = 0;

    public function getOptions()
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $app = JFactory::getApplication();
        $option = $app->input->get('option', '', 'cmd');
        $view = $app->input->get('view', '', 'cmd');
        $context = $option.'.'.$view;
        $prodi = $app->getUserStateFromRequest($context.'.default.filter.prodi', 'filter_prodi', '', 'string');
        $jurusan = $app->getUserStateFromRequest($context.'.default.filter.jurusan', 'filter_jurusan', '', 'string');

        $query->select(['s.id, s.title']);
        $query->from($db->qn('#__siak_semester', 's'));
        $query->select('p.title as prodi')
            ->join('LEFT', $db->qn('#__siak_prodi', 'p').' ON p.id = s.prodi')
        ;
        $query->select('j.title as jurusan')
            ->join('LEFT', $db->qn('#__siak_jurusan', 'j').' ON j.id=s.jurusan')
        ;

        if (!empty($prodi)) {
            $query->where($db->qn('s.prodi').' = '.(int) $prodi);
        }

        if (!empty($jurusan)) {
            $query->where($db->qn('s.jurusan').' = '.(int) $jurusan);
        }

        $query->where($db->qn('s.state').' = '.$db->q('1'));
        $query->order($db->qn('s.prodi').' ASC, '.$db->qn('s.title'));

        $db->setQuery($query);

        try {
            $result = $db->loadObjectList();
        } catch (RuntimeException $err) {
            return $err->getMessage();
        }

        foreach ($result as $res) {
            $opts[] = JHtmlSelect::option($res->id, $res->title.'('.$res->prodi.'-'.$res->jurusan.')');
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
