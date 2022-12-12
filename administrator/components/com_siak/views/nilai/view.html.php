<?php

defined('_JEXEC') or exit;

class SiakViewNilai extends JViewLegacy
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
        $mk = $this->item->kodeMK.'/'.$this->item->namaMK;
        JToolbarHelper::title(
            JText::sprintf('COM_SIAK_NILAI_EDIT_PAGE_TITLE', JFactory::getUser($this->item->user_id)->name, $mk)
        );

        if ($canDo->get('core.edit')) {
            JToolbarHelper::apply('nilai.apply');
            JToolbarHelper::save('nilai.save');
        }

        if (empty($this->item->id)) {
            JToolbarHelper::cancel('nilai.cancel');
        } else {
            JToolbarHelper::cancel('nilai.cancel', 'JTOOLBAR_CLOSE');
        }
    }
}
