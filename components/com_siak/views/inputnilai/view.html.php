<?php

defined('_JEXEC') or die;

class SiakViewInputnilai extends JViewLegacy
{
    protected $form;
    protected $items;
    protected $totalRec;
    protected $bobotNilai;

    public function display($tpl = null)
    {
        $user = JFactory::getUser();
        $app = JFactory::getApplication();
        if (!SiakHelper::auth($user, 'dosen')) {
            $app->enqueueMessage(JText::_('COM_SIAK_WRONG_MENU'), 'error');
            $app->redirect(JRoute::_('index.php?option=com_siak', false));

            return false;
        }
        $this->form = $this->get('Form');
        $this->items = $this->get('Items');
        $this->totalRec = $this->get('Total');
        $this->bobotNilai = $this->get('BobotNilai');
        $errors = $this->get('Errors');

        if (count($errors) > 0) {
            throw new Exception(implode('<br \\>', $errors), 500);

            return false;
        }

        $doc = JFactory::getDocument();
        $title = JText::_('COM_SIAK_INPUT_NILAI_PAGE_TITLE');
        //$title = JText::sprintf(JPAGETITLE, $title, $app->get('sitename'));
        $doc->setTitle($title);

        parent::display($tpl);
    }
}
