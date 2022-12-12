<?php

defined('_JEXEC') or exit;

class SiakViewPaketmk extends JViewLegacy
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

        $title = $this->item->kode.' ('.$this->item->matkul.')';
        JToolbarHelper::title(
            $isNew ? JText::_('COM_SIAK_PAKET_MK_NEW_PAGE_TITLE') : JText::plural('COM_SIAK_PAKET_MK_EDIT_PAGE_TITLE', $title)
        );

        if ($canDo->get('core.edit')) {
            JToolbarHelper::apply('paketmk.apply');
            JToolbarHelper::save('paketmk.save');
            JToolbarHelper::save2new('paketmk.save2new');
        }

        if (empty($this->item->id)) {
            JToolbarHelper::cancel('paketmk.cancel');
        } else {
            JToolbarHelper::cancel('paketmk.cancel', 'JTOOLBAR_CLOSE');
        }
    }
}
