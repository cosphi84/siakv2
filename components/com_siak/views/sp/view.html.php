<?php

defined('_JEXEC') or exit;

class SiakViewSp extends JViewLegacy
{
    protected $form;
    protected $item;
    protected $isMahasiswa = false;

    public function display($tpl = null)
    {
        $app = JFactory::getApplication();
        $user = JFactory::getUser();
        $this->form = $this->get('Form');
        $this->item = $this->get('Item');

        // Remidial hanya untuk mahasiswa
        if (!SiakHelper::auth($user)) {
            $app->redirect(
                JRoute::_('index.php?option=com_siak&view=dashboard', false),
                JText::_('COM_SIAK_WRONG_MENU'),
                'warning'
            );

            return false;
        }

        $errors = $this->get('Errors');

        if (count($errors)) {
            throw new Exception(implode('\n', $errors), 500);

            return false;
        }

        $doc = JFactory::getDocument();
        $title = JText::_('COM_SIAK_SP_PAGE_TITLE');
        $title = JText::sprintf('JPAGETITLE', $title, $app->get('sitename'));
        $doc->setTitle($title);
        parent::display($tpl);
    }
}
