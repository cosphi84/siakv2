<?php

defined('_JEXEC') or die;

class SiakControllerDosens extends JControllerForm
{
    public function dosens()
    {
        $this->checkToken();
        $app = JFactory::getApplication();
        $data = $app->input->get('jform', [], 'array');
        $url = JUri::getInstance();

        if (!empty($data['prodi'])) {
            $app->setUserState('com_siak.dosens.filter.prodi', $data['prodi']);
            $app->setUserState('com_siak.dosens.default.filter.prodi', $data['prodi']);
        }
        if (!empty($data['jurusan'])) {
            $app->setUserState('com_siak.dosens.filter.jurusan', $data['jurusan']);
        }
        if (!empty($data['kelas'])) {
            $app->setUserState('com_siak.dosens.filter.kelas', $data['kelas']);
        }
        if (!empty($data['ta'])) {
            $app->setUserState('com_siak.dosens.filter.ta', $data['ta']);
        }
        $this->setRedirect($url);

        return true;
    }

    public function clear()
    {
        $app = JFactory::getApplication();
        $this->checkToken();
        $url = JUri::getInstance();

        $app->setUserState('com_siak.dosens.filter.prodi', '');
        $app->setUserState('com_siak.dosens.default.filter.prodi', '');
        $app->setUserState('com_siak.dosens.filter.jurusan', '');
        $app->setUserState('com_siak.dosens.filter.ta', '');
        $app->setUserState('com_siak.dosens.filter.kelas', '');
        $this->setRedirect($url);

        return true;
    }
}
