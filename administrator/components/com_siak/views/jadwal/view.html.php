<?php

defined('_JEXEC') or exit;

class SiakViewJadwal extends JViewLegacy
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

        JToolbarHelper::title(JText::_('COM_SIAK_JADWALS_PAGE_TITLE'), 'calendar');

        if ($canDo->get('core.edit')) {
            JToolbarHelper::apply('jadwal.apply');
            JToolbarHelper::save('jadwal.save');
            JToolbarHelper::save2new('jadwal.save2new');
        }

        if (empty($this->item->id)) {
            JToolbarHelper::cancel('jadwal.cancel');
        } else {
            JToolbarHelper::cancel('jadwal.cancel', 'JTOOLBAR_CLOSE');
        }
    }
}
