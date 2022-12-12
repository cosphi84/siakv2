<?php

defined('_JEXEC') or die;

JFormHelper::loadFieldClass('list');

class JFormFieldStatusdu extends JFormFieldList
{
    protected $type = 'Statusdu';
    protected $loadExternally = 0;

    public function getOptions()
    {
        $opts[] = JHtmlSelect::option('1', 'Aktif');
        $opts[] = JHtmlSelect::option('0', 'Tidak Aktif');
        $opts[] = JHtmlSelect::option('-1', 'Cuti');
        $opts[] = JHtmlSelect::option('-2', 'Pindah');
        $opts[] = JHtmlSelect::option('-3', 'Keluar');

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
