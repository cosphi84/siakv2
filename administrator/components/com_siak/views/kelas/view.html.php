<?php

defined('_JEXEC') or exit;

class SiakViewKelas extends JViewLegacy
{
    protected $form;
    protected $item;

    public function display($tpl = null)
    {
        $this->form = $this->get('Form');
        $this->item = $this->get('Item');

        if (count($errors = $this->get('Errors'))) {
            throw new Exception(implode('/n', $errors), 500);
        }

        $this->addTolbar();
        parent::display($tpl);
    }

    protected function addTolbar()
    {
        JFactory::getApplication()->input->set('hidemainmenu', true);

        $user = JFactory::getUser();
        $canDo = JHelperContent::getActions('com_siak');
        $isNew = (0 == $this->item->id);

        JToolbarHelper::title(
            $isNew ? JText::_('COM_SIAK_KELAS_NEW_PAGE_TITLE') : JText::plural('COM_SIAK_KELAS_EDIT_PAGE_TITLE', $this->item->title)
        );

        if ($canDo->get('core.edit')) {
            JToolbarHelper::apply('kelas.apply');
            JToolbarHelper::save('kelas.save');
            JToolbarHelper::save2new('kelas.save2new');
        }

        if (empty($this->item->id)) {
            JToolbarHelper::cancel('kelas.cancel');
        } else {
            JToolbarHelper::cancel('kelas.cancel', 'JTOOLBAR_CLOSE');
        }
    }
}
