<?php

defined('_JEXEC') or die;

JHtml::_('behavior.formvalidator');
JHtml::_('behavior.keepalive');

JHtml::_('script', 'com_siak/submitbutton.js', ['version' => 'auto', 'relative' => true]);

JText::script('ERROR');

JFactory::getDocument()->addScriptDeclaration('
        jQuery(document).ready(function(){
               let file  = jQuery("#jform_file").val();
               jQuery("#jform_fileSK").val(file);
        });
    ');
?>

<form method="post" name="adminForm" id="adminForm" class="form-validate"
    action="<?php echo JRoute::_('index.php?option=com_siak&layout=edit&id='.(int) $this->item->id); ?>"
    enctype="multipart/form-data">
    <div class="form-horizontal">

        <?php echo JHtml::_('bootstrap.startTabSet', 'myTab', ['active' => 'details']); ?>
        <?php
            echo JHtml::_(
    'bootstrap.addTab',
    'myTab',
    'details',
    empty($this->item->id) ? JText::_('COM_SIAK_SK_TAB_NEW_SK') : JText::_('COM_SIAK_TAB_EDIT_SK')
);
        ?>

        <fieldset class="adminform">
            <legend><?php echo JText::_('COM_SIAK_LEGEND_DETAILS'); ?>
            </legend>
            <div class="row-fluid">
                <div class="span6">
                    <?php echo $this->form->renderFieldset('frmSK'); ?>
                </div>
            </div>
        </fieldset>
        <?php echo JHtml::_('bootstrap.endTab'); ?>
        <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'permissions', JText::_('COM_SIAK_TAB_PERMISSIONS')); ?>
        <fieldset class="adminform">
            <legend><?php echo JText::_('COM_SIAK_LEGEND_PERMISSIONS'); ?>
            </legend>
            <div class="row-fluid">
                <div class="span12">
                    <?php echo $this->form->renderFieldset('accesscontrol'); ?>
                </div>
            </div>
            <?php echo JHtml::_('bootstrap.endTab'); ?>
            <?php echo JHtml::_('bootstrap.endTabSet'); ?>

    </div>
    <input type="hidden" name="task" value="helloworld.edit" />
    <?php echo JHtml::_('form.token'); ?>
    </fieldset>

    </div>
</form>