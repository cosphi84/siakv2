<?php

defined('_JEXEC') or exit;

JHtml::_('behavior.formvalidator');
JHtml::_('script', 'com_siak/submitbutton.js', ['version' => 'auto', 'relative' => true]);

?>

<div class="container-fluid">
    <legend><?php echo JText::_('COM_SIAK_REVISI_NILAI_LEGEND'); ?>
    </legend>
</div>
<form method="post"
    action="<?php echo JRoute::_('index.php?option=com_siak&view=revisinilai&id='.$this->data->id, false); ?>"
    class="form-validate form-horizontal well" id="adminForm" name="adminForm">

    <div class="row-fluid">
        <div class=" form-horizontal">
            <fieldset class="adminform">
                <div class=" row-fluid">
                    <?php echo $this->form->renderFieldset('frmRevisiNilai'); ?>
                </div>
            </fieldset>
            <div class="btn-toolbar">
                <div class="btn-group">
                    <button type="button" class="btn btn-primary" onclick="Joomla.submitbutton('revisinilai.save')">
                        <span class="icon-ok"></span><?php echo JText::_('JSAVE'); ?>
                    </button>
                </div>
                <div class="btn-group">
                    <button type="button" class="btn" onclick="Joomla.submitbutton('revisinilai.cancel')">
                        <span class="icon-cancel"></span><?php echo JText::_('JCANCEL'); ?>
                    </button>
                </div>
            </div>

        </div>
        <input type="hidden" name="task" />
        <?php echo JHtml::_('form.token'); ?>
    </div>
</form>