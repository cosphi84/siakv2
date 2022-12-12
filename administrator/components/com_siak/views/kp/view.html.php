<?php

defined('_JEXEC') or exit;

class SiakViewKp extends JViewLegacy
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
            $isNew ? JText::_('COM_SIAK_KP_NEW_PAGE_TITLE') : JText::plural('COM_SIAK_KP_EDIT_PAGE_TITLE', JFactory::getUser($this->item->user_id)->name),
            'briefcase'
        );

        if ($canDo->get('core.create')) {
            JToolbarHelper::apply('kp.apply');
            JToolbarHelper::save('kp.save');
            JToolbarHelper::save2new('kp.save2new');
        }

        if (empty($this->item->id)) {
            JToolbarHelper::cancel('kp.cancel');
        } else {
            JToolbarHelper::cancel('kp.cancel', 'JTOOLBAR_CLOSE');
        }
    }
}
