<?php

use Joomla\CMS\Date\Date;

defined('_JEXEC') or die;

JHtml::_('behavior.formvalidator');
JHtml::_('script', 'com_siak/submitbutton', ['version' => 'auto', 'relative' => true]);
?>

<div class="container-fluid">
    <legend><?php echo JText::_('COM_SIAK_KP_KAPRODI_LEGEND'); ?>
    </legend>
</div>
<form method="post"
    action="<?php echo JRoute::_('index.php?option=com_siak&view=kaprodi&id='.$this->item->id, false); ?>"
    class="form-validate form-horizontal well" id="adminForm" name="adminForm">

    <div class="row-fluid">
        <div class=" form-horizontal">
            <fieldset class="adminform">
                <div class=" row-fluid">
                    <table class="table table-striped">
                        <tr>
                            <td>
                                <?php echo Jtext::_('COM_SIAK_FIELD_NAMA_TITLE'); ?>
                            </td>
                            <td>
                                <?php echo $this->escape(ucfirst($this->item->nama)); ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <?php echo Jtext::_('COM_SIAK_FIELD_NPM_TITLE'); ?>
                            </td>
                            <td>
                                <?php echo $this->escape(ucfirst($this->item->npm)); ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <?php echo Jtext::_('COM_SIAK_PRODI_TITLE_LABEL'); ?>
                            </td>
                            <td>
                                <?php echo $this->escape(ucfirst($this->item->nama_prodi)); ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <?php echo Jtext::_('COM_SIAK_JURUSAN_TITLE_LABEL'); ?>
                            </td>
                            <td>
                                <?php echo $this->escape(ucfirst($this->item->nama_jurusan)); ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <?php echo Jtext::_('COM_SIAK_KELAS_TITLE_LABEL'); ?>
                            </td>
                            <td>
                                <?php echo $this->escape(ucfirst($this->item->nama_kelas)); ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <?php echo JText::_('COM_SIAK_TA_TITLE_LABEL'); ?>
                            </td>
                            <td>
                                <?php echo $this->item->tahun_ajaran; ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <?php echo JText::_('COM_SIAK_KP_FIELD_INSTANSI_LABEL'); ?>
                            </td>
                            <td>
                                <?php echo $this->item->instansi; ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <?php echo JText::_('COM_SIAK_KP_FIELD_PERIODE_LABEL'); ?>
                            </td>
                            <td>
                                <?php
                                $tanggal = new Date($this->item->tanggal_mulai);
                                echo $tanggal->format('d F Y'); ?>
                                s/d
                                <?php
                                $tanggal = new Date($this->item->tanggal_selesai);
                                echo $tanggal->format('d F Y'); ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <?php echo JText::_('COM_SIAK_DOSBING_FIELD_LABEL'); ?>
                            </td>
                            <td>
                                <?php echo $this->item->dosbing; ?>
                            </td>
                        </tr>
                    </table>
                    <?php echo $this->form->renderFieldset('frmKpKaprodi'); ?>
                </div>
            </fieldset>
            <div class="btn-toolbar">
                <div class="btn-group">
                    <button type="button" class="btn btn-primary" onclick="Joomla.submitbutton('kp.save')">
                        <span class="icon-ok"></span><?php echo JText::_('JSAVE'); ?>
                    </button>
                </div>
                <div class="btn-group">
                    <button type="button" class="btn" onclick="Joomla.submitbutton('kp.cancel')">
                        <span class="icon-cancel"></span><?php echo JText::_('JCANCEL'); ?>
                    </button>
                </div>
            </div>

        </div>
        <input type="hidden" name="task" />
        <?php echo JHtml::_('form.token'); ?>
    </div>
</form>