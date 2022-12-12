<?php

defined('_JEXEC') or exit;

class SiakControllerKp extends JControllerForm
{
    protected $text_prefix = 'COM_SIAK_KP';

    public function getModel($name = 'Kp', $prefix = 'SiakModel', $config = [])
    {
        return parent::getModel($name, $prefix, $config);
    }
}
