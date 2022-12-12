<?php

defined('_JEXEC') or exit;

class SiakModelPraktikum extends JModelAdmin
{
    public function getForm($data = [], $loadData = true)
    {
        $form = $this->loadForm('com_siak.nilai', 'nilai', ['control' => 'jform', 'load_data' => $loadData]);
        if (empty($form)) {
            return false;
        }

        return $form;
    }
}
