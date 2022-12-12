<?php

defined('_JEXEC') or exit;

class SiakControllerBobots extends JControllerAdmin
{
    public function getModel($name = 'Bobot', $prefix = 'SiakModel', $config = [])
    {
        return parent::getModel($name, $prefix, $config);
    }
}
