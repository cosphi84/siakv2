<?php

defined('_JEXEC') or exit;

class SiakViewSttmhs extends JViewLegacy
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
            $isNew ? JText::_('COM_SIAK_STATUS_MHS_NEW_PAGE_TITLE') : JText::plural('COM_SIAK_STATUS_MHS_EDIT_PAGE_TITLE', $this->item->angkatan)
        );

        if ($canDo->get('core.edit')) {
            JToolbarHelper::apply('wali.apply');
            JToolbarHelper::save('wali.save');
            JToolbarHelper::save2new('wali.save2new');
        }

        if (empty($this->item->id)) {
            JToolbarHelper::cancel('wali.cancel');
        } else {
            JToolbarHelper::cancel('wali.cancel', 'JTOOLBAR_CLOSE');
        }
    }
}
