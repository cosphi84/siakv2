<?php

defined('_JEXEC') or exit;

class SiakViewBobot extends JViewLegacy
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

        $canDo = JHelperContent::getActions('com_siak');
        $isNew = (0 == $this->item->id);

        JToolbarHelper::title(
            $isNew ? JText::_('COM_SIAK_BOBOT_NEW_PAGE_TITLE') : JText::sprintf('COM_SIAK_BOBOT_EDIT_PAGE_TITLE', $this->item->alias)
        );

        if ($canDo->get('core.create')) {
            JToolbarHelper::apply('bobot.apply');
            JToolbarHelper::save('bobot.save');
            JToolbarHelper::save2new('bobot.save2new');
        }

        if (empty($this->item->id)) {
            JToolbarHelper::cancel('bobot.cancel');
        } else {
            JToolbarHelper::cancel('bobot.cancel', 'JTOOLBAR_CLOSE');
        }
    }
}
