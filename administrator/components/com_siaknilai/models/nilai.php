<?php
/**
 * @package     Joomla.Siak
 * @subpackage  com_siaknilai
 *
 * @copyright   (C) 2022 @ Risam, S.T
 * @license     Limited for FT-UNTAG Cirebon use Only
 */

use Joomla\CMS\Factory;
use Joomla\CMS\MVC\Model\AdminModel;

defined('_JEXEC') or die();

class SiaknilaiModelNilai extends AdminModel
{
    public function getForm($data = array(), $loadData = true)
    {
        $form = $this->loadForm(
            'com_siaknilai.nilai',
            'nilai',
            array('control'=>'jform', 'load_data'=>$loadData)
        );

        if (empty($form)) {
            return false;
        }

        return $form;
    }

    public function getTable($name = 'Siaknilai', $prefix = 'Table', $options = array())
    {
        return parent::getTable($name, $prefix, $options);
    }

    protected function loadFormData()
    {
        $app = Factory::getApplication();
        $data =  $app->getUserState('com_siaknilai.edit.nilai.data', array());

        if (empty($data)) {
            $data = $this->getItem();
        }


        return $data;
    }
}
