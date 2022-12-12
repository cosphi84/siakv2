<?php
/**
 * @package     Joomla.Siak
 * @subpackage  com_siakta
 *
 * @copyright   (C) 2022 @ Risam, S.T
 * @license     Limited for FT-UNTAG Cirebon use Only
 */

use Joomla\CMS\Factory;
use Joomla\CMS\MVC\Model\AdminModel;

defined('_JEXEC') or die();

class SiaktaModelTa extends AdminModel
{
    public function getForm($data = array(), $loadData = true)
    {
        $form = $this->loadForm(
            'com_siakta.ta',
            'ta',
            array('control'=>'jform', 'load_data'=>$loadData)
        );

        if (empty($form)) {
            return false;
        }

        return $form;
    }

    public function getTable($name = 'Siakta', $prefix = 'Table', $options = array())
    {
        return parent::getTable($name, $prefix, $options);
    }

    protected function loadFormData()
    {
        $data =  $this->getItem();
        if(!empty($data->id))
        {
            $data->npm = Factory::getUser($data->mahasiswa_id)->username;
        }
        
        return $data;
    }

    /**
	 * Method to Ovveride save the form data.
	 *
	 * @param   array  $data  The form data.
	 *
	 * @return  boolean  True on success, False on error.
	 *
	 * @since   1.6
	 */
	public function save($data)
	{
		if(empty($data['prodi_id']) && empty($data['konsentrasi_id']))
        {
            $mhs = $this->getMahasiswaData($data['mahasiswa_id']);

            $data['prodi_id'] = $mhs->prodi;
            $data['konsentrasi_id'] = $mhs->jurusan;
        }
        
       if(!parent::save($data))
       {
        return false;
       }

       return true;
	}


    protected function getMahasiswaData($id = 0)
    {
        if($id <= 0) 
        {
            return false;
        }
        $db = $this->getDbo();
        $query = $db->getQuery(true);
        $query->select(array('prodi', 'jurusan', 'kelas'))
            ->from($db->quoteName('#__siak_user'))
            ->where($db->quoteName('user_id'). ' = '. (int) $id);

        $db->setQuery($query);
        try{
            $result = $db->loadObject();
        }catch(RuntimeException $err)
        {
            $this->setError('Load Data mahasiswa Error : '. $err->getMessage());
            return false;
        }

        return $result;
    }
  
}
