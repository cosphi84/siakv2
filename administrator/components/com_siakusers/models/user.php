<?php

defined('_JEXEC') or die;

use Joomla\CMS\MVC\Model\AdminModel;

class SiakusersModelUser extends AdminModel
{
    public function getForm($data = array(), $loadData = true)
    {
        $form = $this->loadForm('com_siakuser.user', 'user', array('control'=>'jform', 'load_data'=>$loadData));
        if (empty($form)) {
            return false;
        }

        return $form;
    }

    public function getTable($name = 'User', $prefix = 'SiakusersTable', $options = array())
    {
        return parent::getTable($name, $prefix, $options);
    }

    protected function loadFormData()
    {
        return $this->getItem();
    }
}
