<?php

defined('_JEXEC') or exit;

JHtml::_('formbehavior.chosen', 'select');
JHtml::_('behavior.formvalidator');
JHtml::_('script', 'com_siak/submitbutton.js', ['version' => 'auto', 'relative' => true]);
JHtml::_('script', 'com_siak/nilai-remidi.js', ['version' => 'auto', 'relative' => true]);

?>

<div class="container-fluid">
    <legend><?php echo JText::_('COM_SIAK_FRM_REMIDIAL_LEGEND'); ?>
    </legend>
    <div class="clearfix"></div>

    <form
        action="<?php echo JRoute::_('index.php?option=com_siak&view=remidial&id='.$this->item->id); ?>"
        method="post" name="adminForm" id="adminForm" class="form-validate form-horizontal well" ">

        <div class=" form-horizontal">
        <fieldset class="adminform">
            <div class=" row-fluid">
                <?php echo $this->form->renderFieldset('frmRemidial'); ?>
            </div>
        </fieldset>
        <div class="btn-toolbar">
            <div class="btn-group">
                <button type="button" class="btn btn-primary" onclick="Joomla.submitbutton('remidial.save')">
                    <span class="icon-ok"></span><?php echo JText::_('JSAVE'); ?>
                </button>
            </div>
            <div class="btn-group">
                <button type="button" class="btn" onclick="Joomla.submitbutton('remidial.cancel')">
                    <span class="icon-cancel"></span><?php echo JText::_('JCANCEL'); ?>
                </button>
            </div>
        </div>

        <input type="hidden" name="task" />
        <?php echo JHtml::_('form.token'); ?>
</div>
</form>
</div>