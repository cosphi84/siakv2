<?php

defined('_JEXEC') or exit;

class SiakViewJurusan extends JViewLegacy
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

        $title = $this->item->title.' ('.$this->item->alias.')';
        JToolbarHelper::title(
            $isNew ? JText::_('COM_SIAK_JURUSAN_NEW_PAGE_TITLE') : JText::plural('COM_SIAK_JURUSAN_EDIT_PAGE_TITLE', $title)
        );

        if ($canDo->get('core.edit')) {
            JToolbarHelper::apply('jurusan.apply');
            JToolbarHelper::save('jurusan.save');
            JToolbarHelper::save2new('jurusan.save2new');
        }

        if (empty($this->item->id)) {
            JToolbarHelper::cancel('jurusan.cancel');
        } else {
            JToolbarHelper::cancel('jurusan.cancel', 'JTOOLBAR_CLOSE');
        }
    }
}
