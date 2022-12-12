<?php

defined('_JEXEC') or die;

JFormHelper::loadFieldClass('list');

class JFormFieldTipebayar extends JFormFieldList
{
    protected $type = 'Tipebayar';
    protected $loadExternally = 0;

    public function getOptions()
    {
        $opts[] = JHtmlSelect::option('CASH', 'Cash');
        $opts[] = JHtmlSelect::option('TRANSFER', 'Transfer');

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
