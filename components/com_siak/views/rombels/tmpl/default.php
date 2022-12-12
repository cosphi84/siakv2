<?php

defined('_JEXEC') or exit;
JHtml::_('bootstrap.framework');
JHtml::_('jquery.framework');
JHtml::_('behavior.keepalive');
JHtml::_('behavior.core');
JHtml::_('formbehavior.chosen', 'select');

?>

<div class="container-fluid">
    <legend><?php echo JText::_('COM_SIAK_ROMBELS_MATAKULIAH_LEGEND'); ?>
    </legend>
    <div class="clearfix"></div>
    <div class="row-fluid">

        <form method="POST" id="adminForm" name="adminForm" class="form-horizontal"
            action="<?php echo JRoute::_('index.php?option=com_siak&view=rombels', false); ?>">

            <?php echo $this->form->renderFieldset('frmFilter', ['class' => 'cols']); ?>

            <div class="btn-toolbar center">
                <div class="btn-group">
                    <button type="button" class="btn btn-primary" onclick="this.form.submit();">
                        <span class="icon-ok"></span><?php echo JText::_('JSHOW'); ?>
                    </button>
                </div>
            </div>
        </form>
    </div>
    <div class="clearfix"></div>
    <div class="well">
        <?php if (empty($this->items)) { ?>
        <div class="alert alert-no-items">
            <?php echo JText::_('JGLOBAL_NO_MATCHING_RESULTS'); ?>
        </div>
        <?php } else { ?>
        <table class="table table-bordered table-striped" id="adminList">
            <thead>
                <tr>
                    <th class="center" width="10%">
                        <?php echo JText::_('COM_SIAK_NO_URUT'); ?>
                    </th>
                    <th>
                        <?php echo JText::_('COM_SIAK_FIELD_NAMA_TITLE'); ?>
                    </th>
                    <th>
                        <?php echo JText::_('COM_SIAK_FIELD_NPM_TITLE'); ?>
                    </th>
                    <th>
                        <?php echo JText::_('COM_SIAK_FIELD_SEMESTER_LABEL'); ?>
                    </th>
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <td colspan="4" class="center">
                        <?php echo $this->pagination->getListFooter(); ?>
                    </td>
                </tr>
            </tfoot>
            <tbody>
                <?php
                    foreach ($this->items as $i => $j) {
                        echo '<tr class="row'.($i % 2).'">';
                        echo '<td class="center">'.$this->pagination->getRowOffset($i);
                        echo '</td><td>';
                        echo $j->mahasiswa;
                        echo '</td><td>';
                        echo $j->npm;
                        echo '</td>';
                        echo '<td>';
                        echo $j->semester;
                        echo '</td>';
                        echo '</tr>';
                    }?>
            </tbody>
        </table>

        <?php } ?>
    </div>
</div>