<?php

defined('_JEXEC') or die;

class SiakControllerBayaran extends JControllerForm
{
    protected $text_prefix = 'COM_SIAK_BAYARAN';

    public function getModel($name = 'Bayaran', $prefix = 'SiakModel', $config = [])
    {
        return parent::getModel($name, $prefix, $config);
    }
}
