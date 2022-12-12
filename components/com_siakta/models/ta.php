<?php
/**
 * @package     Joomla.Siak
 * @subpackage  Siakta Register
 *
 * @copyright   (C) 2022 @ Risam, S.T
 * @license     Limited for FT-UNTAG Cirebon use Only
 */



defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\MVC\Model\FormModel;

class SiaktaModelTa extends FormModel
{
    public function getForm($data = array(), $loadData = true)
    {
        $form = $this->loadForm('com_siakta.ta', 'ta', array('control'=>'jform', 'load_data'=>$loadData));

        if (empty($form)) {
            return false;
        }

        return $form;
    }

    public function getItem($pk = null)
    {
        $app = Factory::getApplication();
        $id =  $app->input->get('id', 0);

        $db = $this->getDbo();
        $query = $db->getQuery(true);

        $query->select('a.*')
            ->from($db->quoteName('#__siak_ta', 'a'))
            ->where($db->quoteName('a.id'). ' = '. (int) $id);

        $query->select('b.alias as prodi')
            ->leftJoin('#__siak_prodi AS b ON b.id = a.prodi_id');

        $query->select('c.alias as konsentrasi')
            ->leftJoin('#__siak_jurusan AS c ON c.id = a.konsentrasi_id');

        $db->setQuery($query);

        try {
            $result = $db->loadObject();
        } catch(RuntimeException $e) {
            $this->setError('Error get Item : '. $e->getMessage());
            return false;
        }

        return $result;
    }

   protected function loadFormData()
   {
       $data = $this->getItem();

       $data->mahasiswa = Factory::getUser($data->mahasiswa_id)->name;
       $data->dosbing1 = Factory::getUser($data->dosbing1)->name;
       $data->dosbing2 = Factory::getUser($data->dosbing2)->name;
       $data->penguji1 = Factory::getUser($data->penguji1)->name;
       $data->penguji2 = Factory::getUser($data->penguji2)->name;
       $data->penguji3 = Factory::getUser($data->penguji3)->name;
       $data->penguji4 = Factory::getUser($data->penguji4)->name;
       $data->sidang_proposal = HTMLHelper::date($data->sidang_proposal, 'd M Y');
       $data->sidang_akhir = HTMLHelper::date($data->sidang_akhir, 'd M Y');
       $data->tanggal_lulus == '0000-00-00' ? $data->tanggal_lulus = '00-00-0000' : $data->tanggal_lulus = HTMLHelper::date($data->tanggal_lulus, 'd M Y');

       return $data;
   }
}
