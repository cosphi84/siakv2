<?php

defined('_JEXEC') or exit;

JFormHelper::loadFieldClass('list');

class JFormFieldHari extends JFormFieldList
{
    protected $type = 'Hari';
    protected $loadExternally = 0;

    public function getOptions()
    {
        $opts[] = JHtmlSelect::option('1', 'Minggu');
        $opts[] = JHtmlSelect::option('2', 'Senin');
        $opts[] = JHtmlSelect::option('3', 'Selasa');
        $opts[] = JHtmlSelect::option('4', 'Rabu');
        $opts[] = JHtmlSelect::option('5', 'Kamis');
        $opts[] = JHtmlSelect::option('6', 'Jumat');
        $opts[] = JHtmlSelect::option('7', 'Sabtu');

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
