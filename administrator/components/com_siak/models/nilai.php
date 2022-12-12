<?php

defined('_JEXEC') or exit;

class SiakModelNilai extends JModelAdmin
{
    protected $_item;

    public function getForm($data = [], $loadData = true)
    {
        $form = $this->loadForm('com_siak.nilai', 'nilai', ['control' => 'jform', 'load_data' => $loadData]);
        if (empty($form)) {
            return false;
        }

        return $form;
    }

    public function loadFormData()
    {
        $data = JFactory::getApplication()->getUserState('com_siak.edit.nilai.data', []);
        if (empty($data)) {
            $data = $this->getItem();
        }

        return $data;
    }

    public function getItem($pk = null)
    {
        $pk = (!empty($pk)) ? $pk : (int) $this->getState('nilai.id');

        if (null === $this->_item) {
            $this->_item = [];
        }

        if (!isset($this->_item[$pk])) {
            // load item from scratch
            $db = $this->getDbo();
            $query = $db->getQuery(true);
            $query->select('n.*')
                ->from($db->qn('#__siak_nilai', 'n'))
            ;
            $query->select(['u.username AS npm', 'u.name AS mahasiswa'])
                ->leftJoin('#__users AS u ON u.id = n.user_id')
            ;

            $query->select(['m.title AS kodeMK', 'm.alias AS namaMK'])
                ->leftJoin('#__siak_matakuliah AS m ON m.id = n.matakuliah')
            ;

            $query->where($db->qn('n.id').' = '.(int) $pk);

            try {
                $db->setQuery($query);
                $result = $db->loadObject();
            } catch (\Throwable $err) {
                $this->setError($err->getMessage());

                return false;
            }

            $this->_item[$pk] = $result;
        }

        return $this->_item[$pk];
    }

    public function save($data)
    {
        $pk = (!empty($data['id'])) ? $data['id'] : (int) $this->getState('nilai.id');
        $table = $this->getTable();
        $table->load($pk);
        $bobotNilai = [];
        $bobot = $this->getBobotNilai();
        //if (empty($data['nilai_akhir'])) {
        //$data['nilai_akhir'] = 0;
        //}

        /*
        unset($bobot);

        if (key_exists('kehadiran', $data)) {
            $data['nilai_akhir'] += ($data['kehadiran'] * ($bobotNilai['kehadiran'] / 100));
        }

        if (key_exists('tugas', $data)) {
            $data['nilai_akhir'] += ($data['tugas'] * ($bobotNilai['tm'] / 100));
        }

        if (key_exists('uts', $data)) {
            $data['nilai_akhir'] += ($data['uts'] * ($bobotNilai['uts'] / 100));
        }

        if (key_exists('uas', $data)) {
            $data['nilai_akhir'] += ($data['uas'] * ($bobotNilai['uas'] / 100));
        }
        */

        if (!(bool) $data['nilai_final']) {
            $data['nilai_akhir'] = 0;
            foreach ($bobot as $key => $val) {
                // $bobotNilai[strtolower($val->title)] = $val->bobot;
                $i = strtolower($val->title);
                if (key_exists($i, $data)) {
                    $data['nilai_akhir'] += ($data[$i] * ($val->bobot / 100));
                }
            }
        }

        $mutu = Siak::getNilaiMutu($data['nilai_akhir']);
        $data['nilai_angka'] = $mutu['huruf'];
        $data['nilai_mutu'] = $mutu['angka'];
        /*
        $data['nilai_angka'] = $mutu['huruf'];
        $data['nilai_mutu'] = $mutu['angka'];
        */

        if (!$table->bind($data)) {
            $this->setError($table->getError());

            return false;
        }

        if (!$table->save($data)) {
            $this->setError($table->getError());

            return false;
        }

        $this->setState('nilai.id', $pk);

        return true;
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
}
