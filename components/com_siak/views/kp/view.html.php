<?php

defined('_JEXEC') or exit;

class SiakViewKp extends JViewLegacy
{
    protected $form;
    protected $item;

    public function display($tpl = null)
    {
        $app = JFactory::getApplication();
        $doc = JFactory::getDocument();
        $layout = $app->input->get('layout', 'edit', 'cmd');
        $this->form = $this->get('Form');
        $this->item = $this->get('Item');
        $errors = $this->get('Errors');
        $params = JComponentHelper::getParams('com_siak');
        $grpkaprodi = $params->get('grpKaprodi');
        $grpMhas = $params->get('grpMahasiswa');
        $user = JFactory::getUser();
        $grpUser = $user->get('groups');

        if (in_array($grpkaprodi, $grpUser)) {
            if (empty($layout)) {
                $this->setLayout('kaprodi');
            } else {
                $this->setLayout($layout);
            }
        } elseif (in_array($grpMhas, $grpUser)) {
            /*
            if (empty($this->item->id) && ($this->item->user_id !== $user->id)) {
                $fields = $this->form->getFieldset();
                foreach ($fields as $k => $val) {
                    $this->form->setFieldAttribute($val->getAttribute('name'), 'readonly', 'true');
                }
            } */

            switch ($layout) {
                case 'edit':
                    $this->form->removeField('judul_laporan');
                    $this->form->removeField('file_laporan');

                    break;

                case 'laporan':
                    $layout = 'edit';

                break;

                default:
                    $layout = 'detail';
            }
            $this->setLayout($layout);
        } else {
            $this->setLayout('detail');
        }

        // manipulasi form mahasiswa

        if (count($errors) > 0) {
            throw new Exception(implode("\n", $errors), 500);

            return false;
        }

        $title = JText::_('COM_SIAK_KP_PAGE_TITLE');
        $title = JText::sprintf('JPAGETITLE', $title, $app->get('sitename'));
        $doc->setTitle($title);

        parent::display($tpl);
    }
}
