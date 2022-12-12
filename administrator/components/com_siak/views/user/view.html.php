<?php

defined('_JEXEC') or exit;

class SiakViewUser extends JViewLegacy
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
        if ('biodata' !== $this->getLayout()) {
            $this->addTolbar();
        }
        parent::display($tpl);
    }

    protected function addTolbar()
    {
        JFactory::getApplication()->input->set('hidemainmenu', true);

        $user = JFactory::getUser();
        $canDo = JHelperContent::getActions('com_siak');
        $isNew = (0 == $this->item->id);

        JToolbarHelper::title(
            JText::plural('COM_SIAK_USER_EDIT_PAGE_TITLE', $this->item->user),
            'users user'
        );

        if ($canDo->get('core.edit')) {
            JToolbarHelper::apply('user.apply');
            JToolbarHelper::save('user.save');
        }

        if (empty($this->item->id)) {
            JToolbarHelper::cancel('user.cancel');
        } else {
            JToolbarHelper::cancel('user.cancel', 'JTOOLBAR_CLOSE');
        }
    }
}
