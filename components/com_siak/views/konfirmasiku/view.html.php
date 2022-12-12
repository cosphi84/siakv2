<?php

defined('_JEXEC') or die;

class SiakViewKonfirmasiku extends JViewLegacy
{
    protected $items;
    protected $pagination;

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
        $this->items = $this->get('Items');
        $this->pagination = $this->get('Pagination');

        if (count($errors = $this->get('Errors')) > 0) {
            throw new Exception(implode('\n'), 500);

            return false;
        }
        $app = JFactory::getApplication();
        $doc = JFactory::getDocument();
        $title = JText::_('COM_SIAK_KONFIRMASIKU_PAGE_TITLE');
        $title = JText::sprintf('JPAGETITLE', $title, $app->get('sitename'));

        $doc->setTitle($title);
        $doc->addStyleSheet(JURI::root().'media/com_siak/css/siak.css');
        parent::display($tpl);
    }
}
