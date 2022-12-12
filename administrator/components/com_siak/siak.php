<?php

defined('_JEXEC') or exit;

if (!JFactory::getUser()->authorise('core.manage', 'com_siak')) {
    throw new JAccessExceptionNotallowed(JText::_('JERROR_ALERTNOAUTHOR'). 403);
}

JLoader::register('SiakHelper', __DIR__.'/helpers/siak.php');
JLoader::register('SiakSubmenu', JPATH_COMPONENT_ADMINISTRATOR.'/helpers/submenu.php');
$controller = JControllerLegacy::getInstance('Siak');
$controller->execute(JFactory::getApplication()->input->get('task'));
$controller->redirect();
