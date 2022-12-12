<?php

defined('_JEXEC') or exit;

class SiakViewIndustri extends JViewLegacy
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

        $title = $this->item->nama;
        JToolbarHelper::title(
            $isNew ? JText::_('COM_SIAK_INDUSTRI_NEW_PAGE_TITLE') : JText::plural('COM_SIAK_INDUSTRI_EDIT_PAGE_TITLE', $title)
        );

        if ($canDo->get('core.edit')) {
            JToolbarHelper::apply('industri.apply');
            JToolbarHelper::save('industri.save');
            JToolbarHelper::save2new('industri.save2new');
        }

        if (empty($this->item->id)) {
            JToolbarHelper::cancel('industri.cancel');
        } else {
            JToolbarHelper::cancel('industri.cancel', 'JTOOLBAR_CLOSE');
        }
    }
}
