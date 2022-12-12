<?php

defined('_JEXEC') or exit;

JLoader::register('Dosen', JPATH_COMPONENT_ADMINISTRATOR.'/helpers/dosen.php');

use Joomla\CMS\Date\Date;

class SiakControllerSp extends JControllerForm
{
    public function cancel($key = null)
    {
        JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
        parent::cancel($key);
        $this->setRedirect(JRoute::_('index.php', false));

        return true;
    }

    public function save($key = null, $urlVar = null)
    {
        $this->checkToken();

        $app = \JFactory::getApplication();
        $user = \JFactory::getUser();

        $id = $app->input->get('id', 0, 'int');
        if ($id <= 0) {
            $app->enqueueMessage('ID tidak ada!', 'error');
            $app->redirect(JRoute::_('index.php?option=com_siak&view=transkip'));

            return;
        }

        $model = $this->getModel();
        $nilai = $model->loadNilai($id);
        if ($nilai->user_id !== $user->id) {
            $app->enqueueMessage('Record Authentication Error!', 'error');
            $app->redirect(JRoute::_('index.php?option=com_siak&view=transkip'));

            return;
        }

        $date = new Date();
        $dosen = new Dosen();

        $nilai->nilai_id = $nilai->id;
        $nilai->dosen = $dosen->getDosenByMk($nilai->prodi, $nilai->jurusan, $nilai->kelas, $nilai->matakuliah, $nilai->tahun_ajaran);
        unset($nilai->id, $nilai->state);

        $nilai->tanggal_daftar = $date->toSQL();

        if ($model->save($nilai)) {
            $app->enqueueMessage('Pendaftaran Remidial / SP Sukses!');
            $app->redirect(JRoute::_('index.php?option=com_siak&view=transkip'));
        }
    }
}
