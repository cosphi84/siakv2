<?php

defined('_JEXEC') or die;

JFormHelper::loadFieldClass('list');

class JFormFieldUsersiak extends JFormFieldList
{
    protected $type = 'Usersiak';
    protected $loadExternally = 0;

    public function getOptions()
    {
        $params = JComponentHelper::getParams('com_siak');
        $opts[] = JHtmlSelect::option($params->get('grpMahasiswa'), 'Mahasiswa');
        $opts[] = JHtmlSelect::option($params->get('grpDosen'), 'Dosen');
        $opts[] = JHtmlSelect::option($params->get('grpAdmin'), 'Admin Fakultas');

        if (!$this->loadExternally) {
            $opts = array_merge(parent::getOptions(), $opts);
        }

        return $opts;
    }

    public function getOptionsExternally()
    {
        $this->loadExternally = 1;

        return $this->getOptions();
    }
}
