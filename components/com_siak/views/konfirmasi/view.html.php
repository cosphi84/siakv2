<?php

defined('_JEXEC') or die;

class SiakViewKonfirmasi extends JViewLegacy
{
    protected $form;
    protected $user;

    public function display($tpl = null)
    {
        // hanya untuk mahasiswa yaaa
        $user = Jfactory::getUser()->get('groups');
        $grp = JComponentHelper::getParams('com_siak')->get('grpMahasiswa');
        $app = JFactory::getApplication();
        if (!in_array($grp, $user)) {
            $app->redirect(JRoute::_('index.php?option=com_siak&view=dashboard', false), JText::_('COM_SIAK_WRONG_MENU'), 'warning');

            return false;
        }
        $this->form = $this->get('Form');
        $this->user = JFactory::getUSer();

        if (count($errors = $this->get('Errors')) > 0) {
            throw new Exception(implode('\n', $errors), 500);

            return false;
        }

        $document = JFactory::getDocument();
        $document->setTitle(JText::_('COM_SIAK_KONFIRMASI_PAGE_TITLE'));

        parent::display($tpl);
    }
}
