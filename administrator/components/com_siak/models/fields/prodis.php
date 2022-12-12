<?php

defined('_JEXEC') or exit;

JFormHelper::loadFieldClass('list');

class JFormFieldProdis extends JFormFieldList
{
    protected $type = 'Prodis';
    protected $loadExternally = 0;

    public function getOptions()
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select(['id, title, alias']);
        $query->from($db->qn('#__siak_prodi'));
        //$query->where($db->qn('title').' != '.$db->q('Non Prodi'));
        $query->where($db->qn('state').' = '.$db->q('1'));

        $db->setQuery($query);

        try {
            $result = $db->loadObjectList();
        } catch (RuntimeException $err) {
            return $err->getMessage();
        }

        foreach ($result as $res) {
            $opts[] = JHtmlSelect::option($res->id, $res->title.' - '.$res->alias);
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
