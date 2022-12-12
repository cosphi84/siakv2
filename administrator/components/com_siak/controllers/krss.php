<?php

defined('_JEXEC') or exit;

use Joomla\Utilities\ArrayHelper;

class SiakControllerKrss extends JControllerForm
{
    public function getModel($name = 'Krs', $prefix = 'SiakModel', $config = [])
    {
        return parent::getModel($name, $prefix, $config);
    }

    public function excel()
    {
        $this->checkToken();
        $this->setRedirect('index.php?option=com_siak&view=krss&format=xlsx');
    }

    public function pdf()
    {
        $this->checkToken();
        $app = JFactory::getApplication();
        $ids = $app->input->get('cid', [], 'array');
        if (count($ids) < 1) {
            $app->enqueueMessage(JText::_('JGLOBAL_NO_ITEM_SELECTED'), 'notice');
            $app->redirect('index.php?option=com_siak&view=krss');

            return false;
        }

        $app->redirect('index.php?option=com_siak&view=krs&format=pdf&id='.$ids[0]);

        return true;
    }

    public function detail()
    {
        $this->checkToken();
        $app = JFactory::getApplication();
        $ids = $app->input->get('cid', [], 'array');
        if (count($ids) < 1) {
            $app->enqueueMessage(JText::_('JGLOBAL_NO_ITEM_SELECTED'), 'notice');
            $app->redirect('index.php?option=com_siak&view=krss');

            return false;
        }

        $app->redirect('index.php?option=com_siak&view=krs&layout=detail&id='.$ids[0]);

        return true;
    }

    public function delete()
    {
        $this->checkToken();
        $app = JFactory::getApplication();
        $user = JFactory::getUser();
        $cids = (array) $this->input->get('cid', [], 'array');

        if (count($cids) < 1) {
            $app->enqueueMessage(JText::_('JGLOBAL_NO_ITEM_SELECTED'), 'notice');
        } else {
            foreach ($cids as $i => $id) {
                if (!$user->authorise('core.delete', 'com_siak')) {
                    unset($cids[$i]);
                    $app->enqueueMessage(JText::_('JLIB_APPLICATION_ERROR_DELETE_NOT_PERMITTED'), 'error');
                }
            }

            if (count($cids) > 0) {
                $model = $this->getModel();
                $cids = ArrayHelper::toInteger($cids);

                if (!$model->delete($cids)) {
                    $this->setMessage($model->getError(), 'error');
                } else {
                    $this->setMessage(JText::plural('COM_SIAK_N_DELETED', count($cids)));
                }
            }
        }
        $this->setRedirect('index.php?option=com_siak&view=krss');
    }
}
