<?php

defined('_JEXEC') or exit;

class SiakViewKrs extends JViewLegacy
{
    protected $form;
    protected $item;
    protected $mks;
    protected $isPindahan;
    protected $isMahasiswa = false;

    public function display($tpl = null)
    {
        $app = JFactory::getApplication();
        $id = $app->input->get('id', 0, 'int');
        $layout = $app->input->get('layout', 'mahasiswa');
        $doc = JFactory::getDocument();
        $user = JFactory::getUser();
        $params = JComponentHelper::getParams('com_siak');
        $this->item = $this->get('Item');
        $this->form = $this->get('Form');
        $errors = $this->get('Errors');

        if (count($errors) > 0) {
            throw new Exception(implode("\n", $errors), 500);

            return false;
        }
        //$grpMhs = $params->get('grpMahasiswa');
        // $usrGrp = $user->get('');

        if (in_array($params->get('grpMahasiswa'), $user->get('groups'))) {
            $this->isMahasiswa = true;

            if (0 != $id && $this->item->user_id !== $user->id) {
                $app->enqueueMessage('Mencoba mengedit KRS mahasiswa lain adalah tindakan Ilegal!<br>Kami mencatat kejadian ini di Log Server!', 'error');
                $app->redirect(JRoute::_('index.php?option=com_siak&view=dashboard', false));

                return false;
            }
        }

        if (!SiakHelper::auth($user, 'mahasiswa') && 0 == $id) {
            //buat proteknsi agar hanya user mahasiswa saja yang boleh akses halaman KRS baru
            $app->redirect(JRoute::_('index.php?option=com_siak&view=dashboard', false), JText::_('COM_SIAK_WRONG_MENU'), 'warning');

            return false;
        }

        // Cek apakah data user_id ada atau tidak.
        // jika edit, pasti ada. maka kita load biodata pemilik KRS
        // jika baru, pasti kosong, maka kta load user sekarang -> pasti mahasiswa
        empty($this->item->user_id) ? $uid = $user->id : $uid = $this->item->user_id;
        $biodata = SiakHelper::loadBiodata($uid);
        2 == $biodata->user_id ? $this->isPindahan = true : $this->isPindahan = false;

        if ('mk' == $layout || 'mks' == $layout) {
            ($id > 0) && (!empty($this->item->ttl_sks)) ? $this->mks = $this->get('MyMK') : $this->mks = $this->get('PaketMK');
        }

        $title = JText::_('COM_SIAK_KRS_FRM_PAGE_TITLE');
        $title = JText::sprintf('JPAGETITLE', $title, $app->get('sitename'));
        $doc->setTitle($title);

        parent::display($tpl);
    }
}
