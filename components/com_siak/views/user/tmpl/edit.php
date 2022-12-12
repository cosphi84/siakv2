<?php

defined('_JEXEC') or die;

JHtml::_('behavior.formvalidator');
JHtml::_('script', 'com_siak/submitbutton.js', ['version' => 'auto', 'relative' => true]);

?>
<form
    action="<?php echo JRoute::_('index.php?option=com_siak&view=user&layout=edit'); ?>"
    method="post" name="adminForm" id="adminForm" class="form-validate" enctype="multipart/form-data">

    <div class="form-horizontal">
        <fieldset class="adminform">
            <legend><?php echo JText::_('COM_SIAK_USER_EDIT_BIODATA_LEGEND_DETAILS'); ?>
            </legend>
            <div class="row-fluid">
                <div class="span6">
                    <?php echo $this->form->renderFieldset('frmUser'); ?>
                    <?php

                        echo '<div class="control-group">';
                        echo '  <div class="control-label">';
                        echo '      <label id="img-fot0" for="img-foto">Foto Diri</label>';
                        echo '  </div>';
                        echo '  <div class="control">';
                        if (!empty($this->item->foto)) {
                            echo '      <img id="img-foto" alt="'.$this->item->user_id.'" src="'.JURI::root().'media/com_siak/files/foto_user/'.$this->item->foto.'" style="width:72px; height:96px; margin-left:15px;">';
                        } else {
                            if (empty($this->item->jenis_kelamin) || ('LAKI LAKI' == $this->item->jenis_kelamin)) {
                                echo '      <img id="img-foto" alt="'.$this->item->user_id.'" src="'.JURI::root().'media/com_siak/files/foto_user/dummy-user-l.png" style="width:72px; height:96px; margin-left:15px;">';
                            } else {
                                echo '      <img id="img-foto" alt="'.$this->item->user_id.'" src="'.JURI::root().'media/com_siak/files/foto_user/dummy-user-p.png" style="width:72px; height:96px; margin-left:15px;">';
                            }
                        }
                        echo '  </div>';
                        echo '</div>';

                    ?>
                </div>
            </div>
        </fieldset>
    </div>

    <div class="btn-toolbar">
        <div class="btn-group">
            <button type="button" class="btn btn-primary" onclick="Joomla.submitbutton('user.save')">
                <span class="icon-ok"></span><?php echo JText::_('JSAVE'); ?>
            </button>
        </div>
        <div class="btn-group">
            <button type="button" class="btn" onclick="Joomla.submitbutton('user.cancel')">
                <span class="icon-cancel"></span><?php echo JText::_('JCANCEL'); ?>
            </button>
        </div>
    </div>

    <input type="hidden" name="task" />
    <?php echo JHtml::_('form.token'); ?>
</form>