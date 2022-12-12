<?php

defined('_JEXEC') or die;

class SiakModelPraktikum extends JModelAdmin
{
    public function getForm($data = [], $loadData = true)
    {
        $form = $this->loadForm('com_siak.praktikum', 'praktikum', ['control' => 'jform', 'load_data' => $loadData]);

        if (empty($form)) {
            return false;
        }

        return $form;
    }

    protected function loadFormData()
    {
        $app = JFactory::getApplication();
        $dataMhs = $app->getUserState('com_siak.mahasiswa.status');
        $user = JFactory::getUser();
        $dataMhs->nama = $user->name;
        $dataMhs->npm = $user->username;

        return $dataMhs;
    }
}
