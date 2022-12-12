<?php

defined('_JEXEC') or exit;

class SiakViewUjian extends JViewLegacy
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

        $title = $this->item->title;
        JToolbarHelper::title(
            $isNew ? JText::_('COM_SIAK_VIEW_UJIAN_NEW_PAGE_TITLE') : JText::plural('COM_SIAK_VIEW_UJIAN_EDIT_PAGE_TITLE', $title)
        );

        if ($canDo->get('core.edit')) {
            JToolbarHelper::apply('ujian.apply');
            JToolbarHelper::save('ujian.save');
        }

        if (empty($this->item->id)) {
            JToolbarHelper::cancel('ujian.cancel');
        } else {
            JToolbarHelper::cancel('ujian.cancel', 'JTOOLBAR_CLOSE');
        }
    }
}
