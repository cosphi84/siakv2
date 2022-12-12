<?php

defined('_JEXEC') or exit;

class SiakModelKp extends JModelAdmin
{
    public function getForm($data = [], $load_data = true)
    {
        $form = $this->loadForm('com_siak.kp', 'kp', ['control' => 'jform', 'load_data' => $load_data]);
        if (empty($form)) {
            return false;
        }

        return $form;
    }

    public function getItem($id = null)
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        null == $id ? $id = JFactory::getApplication()->input->get('id', 0, 'int') : $id;

        $query->select('kp.*')
            ->from($db->qn('#__siak_kp', 'kp'))
        ;

        $query->select('p.title AS nama_prodi')
            ->join('LEFT', $db->qn('#__siak_prodi', 'p').' ON p.id = kp.prodi')
        ;

        $query->select('j.title AS nama_jurusan')
            ->join('LEFT', $db->qn('#__siak_jurusan', 'j').' ON j.id = kp.jurusan')
        ;

        $query->select('k.title AS nama_kelas')
            ->join('LEFT', $db->qn('#__siak_kelas_mahasiswa', 'k').' ON k.id = kp.kelas')
        ;

        $query->select(['u.name AS nama', 'u.username as npm'])
            ->join('LEFT', $db->qn('#__users', 'u').' ON u.id = kp.user_id')
        ;

        $query->select('i.nama as instansi')
            ->leftJoin('#__siak_industri AS i ON i.id = kp.instansi')
        ;

        $query->select('t.name AS dosbing')
            ->join('LEFT', $db->qn('#__users', 't').' ON t.id = kp.dosbing')
        ;

        $query->where($db->qn('kp.id').' = '.(int) $id);

        try {
            $db->setQuery($query);
            $result = $db->loadObject();
        } catch (RuntimeException $err) {
            $this->setError($err->getMessage());

            return false;
        }

        return $result;
    }

    protected function loadFormData()
    {
        $apk = JFactory::getApplication();
        $params = JComponentHelper::getParams('com_siak');
        $grpMahasiswa = $params->get('grpMahasiswa');
        $user = JFactory::getUser();
        $id = $apk->input->get('id', 0, 'int');
        $table = $this->getTable();

        if (in_array($grpMahasiswa, $user->get('groups'))) {
            $data = $apk->getUserState('com_siak.mahasiswa.status');
            $data->nama = $user->name;
            $data->npm = $user->username;
            $data->tahun_ajaran = SiakHelper::getTA();
            if (0 == $id && $table->load(['user_id' => $user->id, 'tahun_ajaran' => SiakHelper::getTA()])) {
                $apk->enqueueMessage(JText::sprintf('COM_SIAK_KP_SUDAH_DAFTAR', SiakHelper::getTA()), 'warning');
            }
        }

        if (0 != $id) {
            return $this->getItem($id);
        }

        return $data;
    }
}
