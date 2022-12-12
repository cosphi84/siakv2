<?php

defined('_JEXEC') or exit;

class SiakControllerJusers extends JControllerAdmin
{
    public function getModel($name = 'Jusers', $prefix = 'SiakModel', $config = [])
    {
        return parent::getModel($name, $prefix, $config);
    }
}
