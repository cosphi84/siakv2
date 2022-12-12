<?php

defined('_JEXEC') or exit;

JFormHelper::loadFieldClass('list');

class JFormFieldUjian extends JFormFieldList
{
    protected $type = 'Ujian';
    protected $loadExternally = 0;

    public function getOptions()
    {
        $opts[] = JHtmlSelect::option('UTS', 'UTS');
        $opts[] = JHtmlSelect::option('UAS', 'UAS');
        $opts[] = JHtmlSelect::option('REMIDIAL', 'Remidial');

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
