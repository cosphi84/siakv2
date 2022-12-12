<?php

defined('_JEXEC') or exit;

class SiakModelBayar extends JModelAdmin
{
    public function getForm($data = [], $loadData = true)
    {
        $form = $this->loadForm('com_siak.bayar', 'bayar', ['control' => 'jform', 'load_data' => $loadData]);
        if (empty($form)) {
            return false;
        }

        return $form;
    }

    public function getTable($type = 'Pembayaran', $prefix = 'Table', $config = [])
    {
        return JTable::getInstance($type, $prefix, $config);
    }

    /*
    protected function loadFormData()
    {
        $context = "{$this->option}.edit.{$this->context}";
        $app = JFactory::getApplication();

        return $app->getUserState($context);
    }
    */
}
