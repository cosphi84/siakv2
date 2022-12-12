<?php

defined('_JEXEC') or die;

JFormHelper::loadFieldClass('list');

class JFormFieldJenisbayar extends JFormFieldList
{
    protected $type = 'Jenisbayar';
    protected $loadExternally = 0;

    public function getOptions()
    {
        $opts[] = JHtmlSelect::option('SPP', 'SPP');
        $opts[] = JHtmlSelect::option('PRAKTIKUM', 'PRAKTIKUM');
        $opts[] = JHtmlSelect::option('KP', 'KP');
        $opts[] = JHtmlSelect::option('KKM', 'KKM');
        $opts[] = JHtmlSelect::option('TA', 'TA');
        $opts[] = JHtmlSelect::option('Lain Lain', 'Lain Lain');

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
