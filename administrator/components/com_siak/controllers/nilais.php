<?php

defined('_JEXEC') or exit();

use Joomla\Utilities\ArrayHelper;

class SiakControllerNilais extends JControllerAdmin
{
    public function getModel($name = 'nilai', $prefix = 'SiakModel', $config = [])
    {
        return parent::getModel($name, $prefix, $config);
    }

    public function excel()
    {
        $this->checkToken();
        $this->setRedirect('index.php?option=com_siak&view=nilais&format=xlsx');
    }

    public function pdf()
    {
        $this->checkToken();
        $app = JFactory::getApplication();
        $cids = (array) $this->input->get('cid', [], 'array');

        if (count($cids) < 1) {
            $app->enqueueMessage(JText::_('JGLOBAL_NO_ITEM_SELECTED'), 'notice');
        } else {
            $cids = ArrayHelper::toInteger($cids);
            $url = 'index.php?option=com_siak&view=transkip&format=pdf&id='.$cids[0];
        }

        $this->setRedirect($url);
    }
}
