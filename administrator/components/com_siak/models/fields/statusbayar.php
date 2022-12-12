<?php

defined('_JEXEC') or exit;

JFormHelper::loadFieldClass('list');

class JFormFieldStatusbayar extends JFormFieldList
{
    protected $type = 'Statusbayar';
    protected $loadExternally = 0;

    public function getOptions()
    {
        $opts[] = JHtmlSelect::option('2', 'Lunas');
        $opts[] = JHtmlSelect::option('1', 'Belum Lunas');
        $opts[] = JHtmlSelect::option('0', 'Belum Bayar');
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
