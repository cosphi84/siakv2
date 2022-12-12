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
                <?php echo $this->form->renderFieldset('frmUser'); ?>

                <div class="control-group">
                    <div class="control-label"><label id="jform_foto" for="jform_foto">
                            <?php echo JText::_('COM_SIAK_USER_FIELD_FOTO_LABEL'); ?></label>
                    </div>
                    <div class="controls">
                        <?php
                    if (!empty($this->item->foto)) {
                        echo '<img src="'.JURI::root().'media/com_siak/files/foto_user/'.$this->item->foto.'" style="width:90px; height=120px;">';
                    } elseif ('LAKI-LAKI' === $this->item->jenis_kelamin || empty($this->item->jenis_kelamin)) {
                        echo '<img src="'.JURI::root().'media/com_siak/files/foto_user/dummy-user-l.png" style="width:90px; height=120px;">';
                    } else {
                        echo '<img src="'.JURI::root().'media/com_siak/files/foto_user/dummy-user-p.png" style="width:90px; height=120px;">';
                    }

                    ?>
                    </div>
                </div>
            </div>

        </div>
    </fieldset>
    <input type="hidden" name="task" value="" />
    <?php echo JHtml::_('form.token'); ?>
</form>