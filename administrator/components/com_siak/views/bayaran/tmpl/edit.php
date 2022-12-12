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
                <?php echo $this->form->renderFieldset('frmBayarAdmin'); ?>
                <?php if (!empty($this->item->kuitansi)) { ?>
                <div class="control-group">
                    <div class="control-label">
                        Kuitansi
                    </div>
                    <div class="controls">
                        <img src="<?php echo JURI::root().'/media/com_siak/files/kuitansi/'.$this->item->kuitansi; ?>"
                            width="500" height="300">
                    </div>
                </div>
                <?php } ?>
            </div>

        </div>
    </fieldset>
    <input type="hidden" name="task" value="" />
    <?php echo JHtml::_('form.token'); ?>
</form>