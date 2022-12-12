<?php

defined('_JEXEC') or die;

class SiakControllerBobot extends JControllerForm
{
    protected $text_prefix = 'COM_SIAK_BOBOT';

    public function getModel($name = 'Bobot', $prefix = 'SiakModel', $config = [])
    {
        return parent::getModel($name, $prefix, $config);
    }
}
