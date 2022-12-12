<?php
/**
 * @package     Joomla.Siak
 * @subpackage  com_siaknilai
 *
 * @copyright   (C) 2022 @ Risam, S.T
 * @license     Limited for FT-UNTAG Cirebon use Only
 */

use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\Helper\ContentHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Menu\Node\Component;

defined('_JEXEC') or die;

class SiaknilaiHelper extends ContentHelper
{
    public static function subMenuSiak($vName)
    {
        $canDo = ContentHelper::getActions('com_siaknilai');
        if ($canDo->get('core.manage')) {
            JHtmlSidebar::addEntry(
                Text::_('COM_SIAKNILAI_SUBMENU_MANAGE_MODULE'),
                'index.php?option=com_config&view=component&component=com_siaknilai'
            );
        }

        JHtmlSidebar::addEntry(
            Text::_('COM_SIAKNILAI_SUBMENU_NILAIS'),
            'index.php?option=com_siaknilai&view=nilais',
            $vName == 'nilais'
        );
    }

    public static function log($user = null, $tran_id = 0, $id = 0)
    {
        $extension = ComponentHelper::getComponent('com_siaknilai');
    }

    /**
     * get Dekan
     * @return object Dekan name & nik
     */
    public static function getDekan()
    {
        $params = ComponentHelper::getParams('com_siak');
        $dekanID = $params->get('dekan');
        $return = new stdClass();
        $return->name = Factory::getUser($dekanID)->name;

        $db = Factory::getDbo();
        $query = $db->getQuery(true)
            ->select($db->quoteName('nik'))->from($db->quoteName('#__siak_user'))->where($db->quoteName('user_id'). ' = '. (int) $dekanID);
        $db->setQuery($query);
        try {
            $res = $db->loadResult();
        } catch(RuntimeException $e) {
            Factory::getApplication()->enqueueMessage($e->getMessage());
        }

        $return->nik = $res;

        return $return;
    }
}
