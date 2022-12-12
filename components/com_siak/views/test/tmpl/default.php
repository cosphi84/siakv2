<?php

defined('_JEXEC') or die('mati di file default');
//JHtml::_('script', 'system/core.js', ['version' => 'auto', 'relative' => true]);
JHtml::_('jquery.ui');
JHtml::_('behavior.core');
JFactory::getDocument()->addScriptDeclaration(
    'jQuery(function(){'.
        'jQuery("#progressbar").progressbar({'.
            'value: 60'.
            '});'.
        '});'
);

?>


<hr class="hr-condensed" />
<!--
<div class="progress progress-striped active" id="install_progress">
    <div class="bar" style="width: 0%;"></div>
</div>
--> 

<div id="progressbar"></div>

<div class="spinner spinner-img" style="visibility: hide;"></div>
   