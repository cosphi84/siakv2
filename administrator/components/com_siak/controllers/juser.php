<?php

defined('_JEXEC') or exit;

class SiakControllerJuser extends JControllerForm
{
    protected $text_prefix = 'COM_SIAK_JENIS_USER';

    public function getModel($name = 'Juser', $prefix = 'SiakModel', $config = [])
    {
        return parent::getModel($name, $prefix, $config);
    }
}
