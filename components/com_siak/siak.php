<?php

defined('_JEXEC') or exit;

$controller = JControllerLegacy::getInstance('Siak');
$controller->execute(JFactory::getApplication()->input->get('task', 'display'));
$controller->redirect();
