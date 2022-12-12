<?php

defined('_JEXEC') or exit;

class SiakControllerJadwal extends JControllerForm
{
    protected $text_prefix = 'COM_SIAK_JADWAL';

    public function getModel($name = 'Jadwal', $prefix = 'SiakModel', $config = [])
    {
        return parent::getModel($name, $prefix, $config);
    }
}
