<?php

defined('_JEXEC') or exit;

JHtml::_('behavior.keepalive');
JHtml::_('behavior.formvalidator');
JHtml::_('formbehavior.chosen', 'select');
JHtml::_('script', 'com_siak/submitbutton.js', ['version' => 'auto', 'relative' => true]);

JText::script('ERROR');

?>
<form method="post" name="adminForm" id="adminForm" class="form-validate"
    action="<?php echo JRoute::_('index.php?option=com_siak&layout=edit&id='.(int) $this->item->id); ?>">
    <fieldset class="adminForm">
        <div class="row-fluid form-horizontal-desktop">

            <div class="form-horizontal">
                <?php echo $this->form->renderFieldset('frmMahasiswa'); ?>
            </div>

        </div>
    </fieldset>
    <input type="hidden" name="task" value="" />
    <?php echo JHtml::_('form.token'); ?>
</form>