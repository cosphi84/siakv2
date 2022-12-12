<?php

defined('_JEXEC') or exit;

class SiakControllerKrs extends JControllerForm
{
    protected $text_prefix = 'COM_SIAK_KRS';
    protected $view_list = 'Krss';
    protected $view_item = 'Krs';

    public function getModel($name = 'Krs', $prefix = 'SiakModel', $config = [])
    {
        return parent::getModel($name, $prefix, $config);
    }
}
