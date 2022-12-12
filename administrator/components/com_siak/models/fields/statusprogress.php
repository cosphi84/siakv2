<?php

defined('_JEXEC') or die;

JFormHelper::loadFieldClass('list');

class JFormFieldStatusprogress extends JFormFieldList
{
    protected $type = 'Statusprogress';
    protected $loadExternally = 0;

    public function getOptions()
    {
        $opts[] = JHtmlSelect::option('3', 'Selesai');
        $opts[] = JHtmlSelect::option('2', 'Disetujui');
        $opts[] = JHtmlSelect::option('1', 'Diterima');
        $opts[] = JHtmlSelect::option('0', 'Submit');
        $opts[] = JHtmlSelect::option('-1', 'Draft');
        $opts[] = JHtmlSelect::option('-2', 'Revisi');
        $opts[] = JHtmlSelect::option('-3', 'Ditolak');
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
