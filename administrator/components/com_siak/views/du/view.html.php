<?php

defined('_JEXEC') or exit;

class SiakViewDu extends JViewLegacy
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
            $isNew ? JText::_('COM_SIAK_DAFTARULANG_NEW_PAGE_TITLE') : JText::sprintf('COM_SIAK_DAFTARULANG_EDIT_PAGE_TITLE', JFactory::getUser($this->item->user_id)->name)
        );

        if ($canDo->get('core.edit')) {
            JToolbarHelper::apply('du.apply');
            JToolbarHelper::save('du.save');
        }

        if (empty($this->item->id)) {
            JToolbarHelper::cancel('du.cancel');
        } else {
            JToolbarHelper::cancel('du.cancel', 'JTOOLBAR_CLOSE');
        }
    }
}
