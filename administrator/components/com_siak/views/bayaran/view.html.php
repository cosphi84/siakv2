<?php

defined('_JEXEC') or exit;

class SiakViewBayaran extends JViewLegacy
{
    protected $item;
    protected $form;

    public function display($tpl = null)
    {
        $this->item = $this->get('Item');
        $this->form = $this->get('Form');
        $error = $this->get('Errors');

        if (count($error) > 0) {
            throw new Exception(implode('<br />', $error), 500);

            return false;
        }
        JFactory::getApplication()->input->set('hidemainmenu', true);
        $this->addTolbar();
        JToolbarHelper::title(JText::_('COM_SIAK_BAYARAN_TITLE_PAGE'), 'credit-2');
        parent::display($tpl);
    }

    protected function addTolbar()
    {
        $canDo = JHelperContent::getActions('com_siak');

        if ($canDo->get('core.create')) {
            JToolbarHelper::save('bayaran.save');
        }

        if (empty($this->item->id)) {
            JToolbarHelper::cancel('bayaran.cancel');
        } else {
            JToolbarHelper::cancel('bayaran.cancel', 'JTOOLBAR_CLOSE');
        }
    }
}
