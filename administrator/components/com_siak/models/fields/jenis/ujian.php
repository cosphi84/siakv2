<?php

defined('_JEXEC') or exit;

JFormHelper::loadFieldClass('list');

class JFormFieldJenis_ujian extends JFormFieldList
{
    protected $type = 'Jenis_ujian';
    protected $loadExternally = 0;

    public function getOptions()
    {
        $opts[] = JHtmlSelect::option('KBM', 'KBM');
        $opts[] = JHtmlSelect::option('UTS', 'UTS');
        $opts[] = JHtmlSelect::option('UTS-S', 'UTS Susulan');
        $opts[] = JHtmlSelect::option('UAS', 'UAS');
        $opts[] = JHtmlSelect::option('UAS-S', 'UAS Susulan');
        $opts[] = JHtmlSelect::option('REMIDIAL', 'Remidial');
        $opts[] = JHtmlSelect::option('SKP', 'Sidang KP');

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
