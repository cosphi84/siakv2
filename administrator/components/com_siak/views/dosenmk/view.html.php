<?php

defined('_JEXEC') or exit;

class SiakViewDosenmk extends JViewLegacy
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
            $isNew ? JText::_('COM_SIAK_DOSENMK_NEW_PAGE_TITLE') : JText::_('COM_SIAK_DOSENMK_EDIT_PAGE_TITLE')
        );

        if ($canDo->get('core.edit')) {
            JToolbarHelper::apply('dosenmk.apply');
            JToolbarHelper::save('dosenmk.save');
            JToolbarHelper::save2new('dosenmk.save2new');
        }

        if (empty($this->item->id)) {
            JToolbarHelper::cancel('dosenmk.cancel');
        } else {
            JToolbarHelper::cancel('dosenmk.cancel', 'JTOOLBAR_CLOSE');
        }
    }
}
