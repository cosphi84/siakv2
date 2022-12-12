<?php

defined('_JEXEC') or exit;

class SiakViewSemester extends JViewLegacy
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
            $isNew ? JText::_('COM_SIAK_SEMESTER_NEW_PAGE_TITLE') : JText::plural('COM_SIAK_SEMESTER_EDIT_PAGE_TITLE', $this->item->title)
        );

        if ($canDo->get('core.edit')) {
            JToolbarHelper::apply('semester.apply');
            JToolbarHelper::save('semester.save');
            JToolbarHelper::save2new('semester.save2new');
        }

        if (empty($this->item->id)) {
            JToolbarHelper::cancel('semester.cancel');
        } else {
            JToolbarHelper::cancel('semester.cancel', 'JTOOLBAR_CLOSE');
        }
    }
}
