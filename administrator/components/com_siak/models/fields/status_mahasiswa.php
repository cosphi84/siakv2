<?php

defined('_JEXEC') or die;

JFormHelper::loadFieldClass('list');

class JFormFieldStatus_mahasiswa extends JFormFieldList
{
    protected $type = 'Status_mahasiswa';
    protected $loadExternally = 0;

    public function getOptions()
    {
        $opts[] = JHtmlSelect::option('Lulus', '2');
        $opts[] = JHtmlSelect::option('Aktif', '1');
        $opts[] = JHtmlSelect::option('Tidak Aktif', '0');
        $opts[] = JHtmlSelect::option('Cuti', '-1');
        $opts[] = JHtmlSelect::option('Pindah', '-2');
        $opts[] = JHtmlSelect::option('Drop Out', '-3');

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
