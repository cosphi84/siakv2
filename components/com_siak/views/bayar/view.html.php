<?php

defined('_JEXEC') or exit;

class SiakViewBayar extends JViewLegacy
{
    protected $form;

    public function display($tpl = null)
    {
        $app = Jfactory::getApplication();
        $user = JFactory::getUser();
        $grups = $user->get('groups');

        $grpMhs = JComponentHelper::getParams('com_siak')->get('grpMahasiswa');
        if (!in_array($grpMhs, $grups)) {
            $app->enqueueMessage(JText::_('COM_SIAK_WRONG_MENU'), 'warning');
            $app->redirect(Jroute::_('index.php?option=com_siak&view=dashboard', false));

            return false;
        }
        $this->form = $this->get('Form');
        $errors = $this->get('Errors');
        if (count($errors) > 0) {
            throw new Exception(implode('\n', $errors), 500);

            return false;
        }

        $doc = JFactory::getDocument();
        $title = JText::_('COM_SIAK_FORM_KONFIRMASI_PEMBAYARAN');
        $title = JText::sprintf('JPAGETITLE', $title, $app->get('sitename'));
        $doc->setTitle($title);
        parent::display($tpl);
    }
}
