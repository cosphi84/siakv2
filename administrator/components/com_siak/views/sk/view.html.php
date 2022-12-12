<?php

defined('_JEXEC') or exit;

class SiakViewSk extends JViewLegacy
{
    protected $form;
    protected $item;
    protected $canDo;

    public function display($tpl = null)
    {
        $this->form = $this->get('Form');
        $this->item = $this->get('Item');
        $this->canDo = JHelperContent::getActions('com_siak', 'sk', $this->item->id);

        if (count($errors = $this->get('Errors'))) {
            throw new Exception(implode('/n', $errors), 500);
        }

        // we need perform a lile form modification
        if ($this->item->id > 0) {
            // this id edit data
            $this->form->setFieldAttribute('file', 'required', 'false');
            $this->form->setFieldAttribute('file', 'type', 'hidden');
        } else {
            $this->form->removeField('fileSK');
            $this->form->removeField('filenew');
        }

        $this->addTolbar();
        parent::display($tpl);
    }

    protected function addTolbar()
    {
        JFactory::getApplication()->input->set('hidemainmenu', true);

        $user = JFactory::getUser();

        $isNew = (0 == $this->item->id);

        JToolbarHelper::title(
            $isNew ? JText::_('COM_SIAK_SK_NEW_PAGE_TITLE') : JText::plural('COM_SIAK_SK_EDIT_PAGE_TITLE', $this->item->title)
        );

        if ($this->canDo->get('core.create') || $this->canDo->get('core.edit')) {
            JToolbarHelper::apply('sk.apply');
            JToolbarHelper::save('sk.save');
            JToolbarHelper::save2new('sk.save2new');
        }

        if (empty($this->item->id)) {
            JToolbarHelper::cancel('sk.cancel');
        } else {
            JToolbarHelper::cancel('sk.cancel', 'JTOOLBAR_CLOSE');
        }
    }
}
