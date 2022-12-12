<?php

defined('_JEXEC') or exit;

class SiakViewKrs extends JViewLegacy
{
    protected $form;
    protected $item;

    public function display($tpl = null)
    {
        $this->form = $this->get('Form');
        $this->item = $this->get('Item');
        $error = $this->get('Errors');
        if (count($error) > 0) {
            throw new Exception(implode('<br \\>', $error), 500);

            return false;
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
            JText::_('COM_SIAK_KRS_EDIT_PAGE_TITLE'),
            'briefcase'
        );

        if ($canDo->get('core.edit')) {
            JToolbarHelper::save('krs.save');
        }

        if (empty($this->item->id)) {
            JToolbarHelper::cancel('krs.cancel');
        } else {
            JToolbarHelper::cancel('krs.cancel', 'JTOOLBAR_CLOSE');
        }
    }
}
