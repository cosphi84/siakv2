<?php

defined('_JEXEC') or exit;

JHtml::_('behavior.formvalidator');
JHtml::_('script', 'com_siak/submitbutton', ['version' => 'auto', 'relative' => true]);
?>

<div class="container-fluid">
    <legend><?php echo JText::_('COM_SIAK_KONFIRMASI_LEGEND'); ?>
    </legend>
</div>
<form method="post"
    action="<?php echo JRoute::_('index.php?option=com_siak'); ?>"
    class="form-validate form-horizontal well" id="adminForm" name="adminForm">

    <div class="row-fluid">
        <p>
            <?php echo JText::_('COM_SIAK_KONFIRMASI_PARAGRAF_AWAL'); ?>
        </p>
        <p>
        <div class="control-group">
            <div class="control-label">
                <label id="jform_nama-lbl" for="jform_nama">
                    Nama</label>
            </div>
            <div class="controls">
                <input type="text"
                    value="<?php echo $this->user->name; ?>"
                    disabled="true" clas="input input-medium" />
            </div>
        </div>
        <div class="control-group">
            <div class="control-label">
                <label id="jform_nama-lbl" for="jform_nama">
                    N P M</label>
            </div>
            <div class="controls">
                <input type="text"
                    value="<?php echo $this->user->username; ?>"
                    disabled="true" clas="input input-medium" />
            </div>
        </div>
        <?php
                echo $this->form->renderField('prodi');
                echo $this->form->renderField('jurusan');
                echo $this->form->renderField('kelas');
            ?>
        <p>
            <?php
            echo JText::_('COM_SIAK_KONFIRMASI_PARAGRAF_DUA');
            echo $this->form->renderField('status');
            echo 'Pada :';
            echo $this->form->renderField('semester');
            echo $this->form->renderField('ta');
        ?>
        </p>
        <br />
        <p>
            Demikain pernyatan ini saya buat sebenar benarnya, dan bersama ini saya siap menunaikan kewajiban kewajiban
            saya dan akan mematuhi semua peraturan yang ada di Fakultas Teknik Universitas 17 Agustus 1945 Cirebon.
        </p>
        <div class="btn-toolbar">
            <div class="btn-group">
                <button type="button" class="btn btn-primary" onclick="Joomla.submitbutton('konfirmasi.save')">
                    <span class="icon-ok"></span><?php echo JText::_('JREGISTER'); ?>
                </button>
            </div>
            <div class="btn-group">
                <button type="button" class="btn" onclick="Joomla.submitbutton('konfirmasi.cancel')">
                    <span class="icon-cancel"></span><?php echo JText::_('JCANCEL'); ?>
                </button>
            </div>
        </div>
        <input type="hidden" name="task" />
        <?php echo JHtml::_('form.token'); ?>
    </div>
</form>