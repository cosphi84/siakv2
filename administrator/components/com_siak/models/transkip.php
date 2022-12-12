<?php

defined('_JEXEC') or exit;
use Joomla\Utilities\ArrayHelper;

JLoader::register('TaHelpers', JPATH_COMPONENT_ADMINISTRATOR.'/helpers/ta.php');

class SiakModelTranskip extends JModelItem
{
    protected $context;

    public function __construct($config = [])
    {
        $this->context = $this->option.'.'.$this->name;
        parent::__construct($config);
    }

    public function getItem($pk = null)
    {
        if (empty($pk)) {
            $pk = $this->getState($this->context.'.id');
        }

        !empty($pk) ? $pk : (int) $this->getState($this->context.'.id');
        $db = JFactory::getDbo();

        $query = $db->getQuery(true);

        $query->select(['n.id', 'n.user_id', 'n.nilai_akhir', 'n.nilai_angka', 'n.nilai_mutu', 'n.status'])
            ->from($db->qn('#__siak_nilai', 'n'))
        ;

        $query->select(['sp.nilai_akhir_remid', 'sp.nilai_remid_angka', 'sp.nilai_remid_mutu'])
            ->leftJoin('#__siak_sp AS sp ON sp.nilai_id = n.id')
        ;

        $query->select(['u.name as mahasiswa', 'u.username as npm'])
            ->innerJoin('#__users AS u ON u.id = n.user_id')
        ;

        $query->where($db->qn('n.user_id').' = ( SELECT user_id FROM '.$db->qn('#__siak_nilai').' WHERE id = '.(int) $pk.')');

        $query->select(['m.title as kodemk', 'm.alias as mk', 'm.sks'])
            ->leftJoin('#__siak_matakuliah AS m ON m.id = n.matakuliah')
        ;

        $query->select('jmk.title as jenisMK')
            ->leftJoin('#__siak_jenis_mk AS jmk ON jmk.id = m.type')
        ;

        $query->select(['s.id AS sid', 's.title as semester', 's.alias as smt'])
            ->leftJoin('#__siak_semester AS s ON s.id=n.semester')
        ;

        // $query->where($db->qn('n.nilai_mutu').' > '. 0);

        $query->order($db->qn('s.alias'));

        try {
            $db->setQuery($query);
            $result = $db->loadAssocList();
        } catch (\Exception $err) {
            $this->setError($err->getMessage());

            return false;
        }
        $nilai = [];
        // menghitung IPK.
        // ipk = $sumBxS / sumSKS
        $sumSKS = 0;
        $sumBxS = 0;
        $nilai['ta'] = [];
        foreach ($result as $key => $value) {
            // menghitung ip
            // ip = Bxs / sum SKS
            $BxS = 0;
            $sks = 0;
            // Jika status nilai ternyata bukan nilai MURNI
            // DAN nilai asli lebih kecil dari nilai Remid,
            if ('MURNI' != $value['status'] && ($value['nilai_mutu'] < $value['nilai_remid_mutu'])) {
                // maka pakai nilai remid
                $value['nilai_akhir'] = $value['nilai_akhir_mutu'];
                $value['nilai_angka'] = $value['nilai_remid_angka'];
                $value['nilai_mutu'] = $value['nilai_remid_mutu'];
            }
            unset($value['nilai_remid_mutu'], $value['nilai_akhir_remid'], $value['nilai_remid_angka']);

            (int) $value['BxS'] = (int) $value['nilai_mutu'] * (int) $value['sks'];
            $BxS += $value['BxS'];
            $sumSKS += $value['sks'];

            if (8 == $value['smt']) {
                $ta = TaHelpers::getTA($value['user_id']);
                if (!empty($ta)) {
                    $nilai['ta'] = ArrayHelper::toObject($ta);
                }
            }

            $sumBxS += $BxS;
            $nilai['nilai'][] = ArrayHelper::toObject($value);
            $nilai['sumSKS'] = $sumSKS;
            $nilai['sumBxS'] = $sumBxS;
        }
        ksort($nilai);
        unset($result);

        return $nilai;
    }

    protected function populateState()
    {
        $app = JFactory::getApplication();
        $id = $app->input->get('id', 0, 'int');
        $this->setState($this->context.'.id', $id);
        $params = JComponentHelper::getParams('com_siak');
        $this->setState('params', $params);
    }
}
