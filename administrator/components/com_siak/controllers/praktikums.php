<?php

defined('_JEXEC') or die;

class SiakControllerPraktikums extends JControllerAdmin
{
    public function getModel($name = 'praktikum', $prefix = 'SiakModel', $config = [])
    {
        return parent::getModel($name, $prefix, $config);
    }
}
