<?php

defined('_JEXEC') or die;

//JLoader::register('Siak', JPATH_COMPONENT.'/helpers/siak.php');

class SiakViewRevisinilai extends JViewLegacy
{
    protected $form;
    protected $data;

    public function display($tpl = null)
    {
        $app = JFactory::getApplication();
        $this->data = $this->get('Data');
        $this->form = $this->get('Form');
        $user = JFactory::getUser();
        $errors = $this->get('Errors');

        if (!SiakHelper::auth($user, 'dosen')) {
            $app->enqueueMessage(JText::_('COM_SIAK_WRONG_MENU'), 'error');
            $app->redirect(JRoute::_('index.php?option=com_siak&view=dashboard', false));

            return false;
        }

        if (count($errors) > 0) {
            throw new Exception(implode('<br />', $errors), 500);

            return false;
        }

        $doc = JFactory::getDocument();
        $title = JText::_('COM_SIAK_REVISI_NILAI_PAGE_TITLE');
        $title = JText::sprintf('JPAGETITLE', $title, $app->get('sitename'));
        $doc->setTitle($title);

        parent::display($tpl);
    }
}
