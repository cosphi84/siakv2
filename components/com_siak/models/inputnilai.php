<?php

use Joomla\CMS\Factory;
use Joomla\CMS\MVC\Model\AdminModel;

defined('_JEXEC') or exit;

class SiakModelInputnilai extends AdminModel
{
    protected $getNumRows = 0;

    public function getItems()
    {
        $db = $this->getDbo();
        $query = $db->getQuery(true);
        $app = Factory::getApplication();

        $dsdmID = $app->input->get('dsdm', 0, 'int');
        $tblDsdm = $this->getTable('dosenmk');
        if (!$tblDsdm->load($dsdmID)) {
            $this->setError('ID Dosen MK tidak ada');
            return false;
        }

        $dsdm = $tblDsdm->getProperties(true);


        $query->select(['n.id', 'n.nilai_final', 'n.kehadiran', 'n.tugas', 'n.uts', 'n.uas', 'n.nilai_akhir', 'n.nilai_angka', 'n.nilai_mutu'])
            ->from($db->qn('#__siak_nilai', 'n'))
        ;
        $query->select(['u.name as mahasiswa', 'u.username as npm'])
            ->leftJoin('#__users AS u ON u.id = n.user_id')
        ;
        $query->select('s.title as semester')
            ->leftJoin('#__siak_semester AS s ON s.id=n.semester')
        ;

        $query->where($db->qn('n.tahun_ajaran').' = '.$db->q($dsdm['tahun_ajaran']));
        $query->where($db->qn('n.kelas').' = '.$db->q($dsdm['kelas']));
        $query->where($db->qn('n.matakuliah').' = '.$db->q($dsdm['matakuliah']));
        $query->where($db->qn('n.state').' = \'1\'');

        $db->setQuery($query);


        try {
            $db->execute();
            $this->getNumRows = $db->getNumRows();
            $result = $db->loadObjectList();
        } catch (\Throwable $th) {
            $this->setError($th->getMessage());

            return false;
        }

        return $result;
    }

    public function getTotal()
    {
        return $this->getNumRows;
    }

    public function getForm($data = [], $loadData = true)
    {
        $form = $this->loadForm('com_siak.inputnilai', 'inputnilai', ['control' => 'jform', 'load_data' => $loadData]);
        if (empty($form)) {
            return false;
        }

        return $form;
    }

    public function getTable($type = 'Nilai', $prefix = 'Table', $config = [])
    {
        return JTable::getInstance($type, $prefix, $config);
    }

    public function getBobotNilai()
    {
        $db = JFactory::getDbo();

        $query = $db->getQuery(true);
        $query->select(['title', 'alias', 'bobot'])
            ->from($db->qn('#__siak_bobot_nilai'))
            ->where($db->qn('state').' = 1')
            ->order($db->qn('id').' ASC')
        ;

        $db->setQuery($query);

        try {
            $result = $db->loadObjectList();
        } catch (\Throwable $th) {
            $this->setError($th->getMessage());

            return false;
        }

        return $result;
    }

    public function save($datas = [])
    {
        $table = $this->getTable();

        foreach ($datas as $key => $data) {
            if (empty($data['id']) || 0 == $data['id']) {
                return false;
            }

            $pk = $data['id'];

            foreach ($data as $k => $v) {
                if ('' === $v) {
                    unset($data[$k]);
                }
            }

            try {
                if (!$table->load($pk)) {
                    $this->setError('Primary ID tidak ada!');

                    return false;
                }

                if (!$table->bind($data)) {
                    $this->setError($table->getError());

                    return false;
                }

                $this->prepareTable($table);

                if (!$table->check()) {
                    $this->setError($table->getError());

                    return false;
                }

                if (!$table->store()) {
                    $this->setError($table->getError());

                    return false;
                }

                $this->cleanCache();
            } catch (\Exception $e) {
                $this->setError($e->getMessage());

                return false;
            }
        }
    }
}
