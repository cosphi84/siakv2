<?php

defined('_JEXEC') or die;
JHtml::_('behavior.formvalidator');
JHtml::_('script', 'com_siak/submitbutton.js', ['version' => 'auto', 'relative' => true]);

?>
<form
    action="<?php echo JRoute::_('index.php?option=com_siak&view=dosens', false); ?>"
    method="post" id="adminForm" name="adminForm">
    <div class="form-horizontal">
        <fieldset class="adminform">
            <legend><?php echo JText::_('COM_SIAK_DOSENS_LEGEND_DETAILS'); ?>
            </legend>
            <div class="row-fluid">
                <div class="span6">
                    <?php echo $this->form->renderFieldset('frmPilihDosenMK'); ?>
                </div>
            </div>
        </fieldset>
        <div class="btn-toolbar">
            <div class="btn-group">
                <button type="button" class="btn btn-primary" onclick="Joomla.submitbutton('dosens.dosens')">
                    <span class="icon-ok"></span><?php echo JText::_('JSHOW'); ?>
                </button>
            </div>
            <div class="btn-group">
                <button type="button" class="btn" onclick="Joomla.submitbutton('dosens.clear')">
                    <span class="icon-cancel"></span><?php echo JText::_('JCANCEL'); ?>
                </button>
            </div>
        </div>
    </div>
    <input type="hidden" name="task" />
    <?php echo JHtml::_('form.token'); ?>

</form>

<?php

echo $this->loadTemplate('result');
