<?php

defined('_JEXEC') or exit;

class SiakViewMatkul extends JViewLegacy
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

        $title = ${$this}->item->kode.' ('.$this->item->matkul.')';
        JToolbarHelper::title(
            $isNew ? JText::_('COM_SIAK_MATKUL_NEW_PAGE_TITLE') : JText::plural('COM_SIAK_MATKUL_EDIT_PAGE_TITLE', $title)
        );

        if ($canDo->get('core.create')) {
            JToolbarHelper::apply('matkul.apply');
            JToolbarHelper::save('matkul.save');
            JToolbarHelper::save2new('matkul.save2new');
        }

        if (empty($this->item->id)) {
            JToolbarHelper::cancel('matkul.cancel');
        } else {
            JToolbarHelper::cancel('matkul.cancel', 'JTOOLBAR_CLOSE');
        }
    }
}
