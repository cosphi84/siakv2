<?php

defined('_JEXEC') or exit;

class SiakViewProdi extends JViewLegacy
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

        $title = $this->item->title.' ('.$this->item->alias.')';
        JToolbarHelper::title(
            $isNew ? JText::_('COM_SIAK_PRODI_NEW_PAGE_TITLE') : JText::plural('COM_SIAK_PRODI_EDIT_PAGE_TITLE', $title)
        );

        if ($canDo->get('core.edit')) {
            JToolbarHelper::apply('prodi.apply');
            JToolbarHelper::save('prodi.save');
            JToolbarHelper::save2new('prodi.save2new');
        }

        if (empty($this->item->id)) {
            JToolbarHelper::cancel('prodi.cancel');
        } else {
            JToolbarHelper::cancel('prodi.cancel', 'JTOOLBAR_CLOSE');
        }
    }
}
