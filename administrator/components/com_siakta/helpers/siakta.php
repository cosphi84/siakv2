<?php
/**
 * @package     Joomla.Siak
 * @subpackage  com_siakta
 *
 * @copyright   (C) 2022 @ Risam, S.T
 * @license     Limited for FT-UNTAG Cirebon use Only
 */

use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Helper\ContentHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Menu\Node\Component;

defined('_JEXEC') or die;

class SiaktaHelper extends ContentHelper
{
    public static function subMenuSiak($vName)
    {
        $canDo = ContentHelper::getActions('com_siakta');
        if ($canDo->get('core.manage')) {
            JHtmlSidebar::addEntry(
                Text::_('COM_SIAKTA_SUBMENU_MANAGE_MODULE'),
                'index.php?option=com_config&view=component&component=com_siakta'
            );
        }

        JHtmlSidebar::addEntry(
            Text::_('COM_SIAKTA_SUBMENU_TAS'),
            'index.php?option=com_siakta&view=tas',
            $vName == 'tas'
        );
    }

    public static function log($user = null, $tran_id = 0, $id = 0)
    {
        $extension = ComponentHelper::getComponent('com_siakta');
    }
}
