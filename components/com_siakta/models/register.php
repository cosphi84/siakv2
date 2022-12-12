<?php
/**
 * @package     Joomla.Siak
 * @subpackage  Siakta Register
 *
 * @copyright   (C) 2022 @ Risam, S.T
 * @license     Limited for FT-UNTAG Cirebon use Only
 */

use Joomla\CMS\Date\Date;
use Joomla\CMS\Factory;
use Joomla\CMS\MVC\Model\AdminModel;

 defined('_JEXEC') or die;

 class SiaktaModelRegister extends AdminModel
 {
    protected $context; 

    public function __construct($config = array())
    {
        $app = Factory::getApplication();
        $option = $app->input->get('option', 'com_siakft');
        $name = $app->input->get('view', 'register');
        $this->context = $option.'.'.$name;
        parent::__construct($config);
    }

    public function getForm($data = array(), $loadData = true)
    {
        $form = $this->loadForm(
                    $this->context.'.register',
                    'register', 
                    array('control'=>'jform', 'load_data'=>$loadData)
                );

        if(empty($form)) {
            
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
        $app = Factory::getApplication();
        $data = $app->getUserState($this->context.'.edit.data', array());
        $tblUser = $this->getTable('Siakusers');
        empty($data) ? $data = $this->getItem() : $data;

        if(empty($data->id))
        {
            $user = Factory::getUser();
            $data->mahasiswa_id = $user->id;
            if($tblUser->load(array('user_id'=>$user->id)))
            {
                $dataUser = $tblUser->getProperties(1);
                $data->prodi_id = $dataUser['prodi'];
                $data->konsentrasi_id = $dataUser['jurusan'];
            }

            $data->tahun = Date::getInstance()->year;
            
        }
        return $data;
    }

    public function save($data)
    {
        $table = $this->getTable();
        if(empty($data['mahasiswa_id']))
        {
            $this->setError('Invalid data Content!');
            return false;
        }
        
        $key = $table->getKeyName();
		$pk = (!empty($data[$key])) ? $data[$key] : (int) $this->getState($this->getName() . '.id');
        
        try {

            if ($pk == 0) {
                if($table->load(array('mahasiswa_id'=>$data['mahasiswa_id'])))
                {
                    $old =  $table->getProperties(1);
                    if($old['state'] != 0)
                    {
                        $this->setError('Pengajuan TA sebelumnya sudah diproses!.');
                        return false;
                    }
                
                    $data['update_judul_on'] = Factory::getDate()->toSql();
                }
			}else{
                $table->load($pk);
            }

            if (!$table->bind($data)) {
                $this->setError($table->getError());

                return false;
            }

            // Prepare the row for saving
			$this->prepareTable($table);

			// Check the data.
			if (!$table->check())
			{
				$this->setError($table->getError());

				return false;
			}

			
			// Store the data.
			if (!$table->store())
			{
				$this->setError($table->getError());

				return false;
			}

			// Clean the cache.
			$this->cleanCache();
            
        }catch(Exception $e) 
        {
            $this->setError($e->getMessage());
            return false;
        }
    
        return true;
    }
 }