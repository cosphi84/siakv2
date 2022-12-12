<?php

defined('_JEXEC') or die;

class SiakViewUser extends JViewLegacy
{
    protected $form;
    protected $item;
    protected $params;

    public function display($tpl = null)
    {
        $this->form = $this->get('Form');
        $this->item = $this->get('Item');
        $this->params = JComponentHelper::getParams('com_siak');

        if (count($errors = $this->get('Errors')) > 0) {
            throw new Exception(implode("\n", $errors), 500);
        }
        $this->prepareDoc();
        parent::display($tpl);
    }

    protected function prepareDoc()
    {
        $doc = JFactory::getDocument();
        $doc->setTitle(JText::_('COM_SIAK_USER_PAGE_TITLE'));
        $doc->addStyleSheet('media/com_siak/cs/siak.css');
    }
}
