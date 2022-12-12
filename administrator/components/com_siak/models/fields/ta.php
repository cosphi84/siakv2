<?php

use Joomla\CMS\Date\Date;

defined('_JEXEC') or exit;

JFormHelper::loadFieldClass('list');

class JFormFieldTa extends JFormFieldList
{
    protected $type = 'Ta';
    protected $loadExternally = 0;

    public function getOptions()
    {
        $tahunIni = new Date('now + 1 year');
        $start = new Date('now - 10 Year');
        for ($i = (int) $start->year; $i <= (int) $tahunIni->year; ++$i) {
            if ($tahunIni->month <= 7) {
                $ta[] = ($i - 1).'-'.$i;
            } else {
                $ta[] = $i.'-'.($i + 1);
            }
        }

        foreach ($ta as $res) {
            $opts[] = JHtmlSelect::option($res, $res);
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
