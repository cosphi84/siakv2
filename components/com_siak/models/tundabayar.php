<?php

defined('_JEXEC') or exit;

class SiakModelTundabayar extends JModelAdmin
{
    public function getForm($data = [], $loadData = true)
    {
        $form = $this->loadForm('com_siak.tundabayar', 'tundabayar', ['control' => 'jform', 'load_data' => $loadData]);

        if (empty($form)) {
            return false;
        }

        return $form;
    }

    protected function loadFormData()
    {
        $app = JFactory::getApplication();

        return $this->getItem();
    }
}
