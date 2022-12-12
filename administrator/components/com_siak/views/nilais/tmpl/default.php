<?php

defined('_JEXEC') or exit;

JHtml::_('behavior.multiselect');
JHtml::_('formbehavior.chosen', 'select');
$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn = $this->escape($this->state->get('list.direction'));
?>

<form
    action="<?php echo JRoute::_('index.php?option=com_siak&view=nilais'); ?>"
    method="post" name="adminForm" id="adminForm">

    <?php if (!empty($this->sidebar)) { ?>

    <div id="j-sidebar-container" class="span2">
        <?php echo $this->sidebar; ?>
    </div>
    <div id="j-main-container" class="span10">

        <?php } else { ?>
        <div class="j-main-container">
            <?php } ?>
            <?php echo JLayoutHelper::render('joomla.searchtools.default', ['view' => $this]); ?>
            <?php if (empty($this->items)) { ?>
            <div class="alert alert-no-items">
                <?php echo JText::_('JGLOBAL_NO_MATCHING_RESULTS'); ?>
            </div>
            <?php } else { ?>
            <table class="table table-striped table-bordered" id="classesList">
                <thead>
                    <tr>
                        <th class="nowrap center" style="vertical-align : middle;">
                            <?php echo JHtml::_('grid.checkall'); ?>
                        </th>
                        <th class="nowrap" style="vertical-align : middle;">
                            <?php echo JHtml::_('searchtools.sort', 'COM_SIAK_MAHASISWA_FIELD_TITLE_LABEL', 'u.name', $listDirn, $listOrder); ?>
                        </th>
                        <th class="nowrap" style="vertical-align : middle;">
                            <?php echo JText::_('COM_SIAK_MATKUL_FIELD_NAMA_LABEL'); ?>
                        </th>
                        <th class="nowrap" style="vertical-align : middle;">
                            <?php echo JText::_('COM_SIAK_SEMESTER_FIELD_TITLE_LABEL'); ?>
                        </th>


                        <th class="center" width="5%">
                            <?php echo JText::_('COM_SIAK_NILAI_FIELD_KEHADIRAN_LABEL'); ?>
                        </th>
                        <th class="center" width="5%">
                            <?php echo JText::_('COM_SIAK_NILAI_FIELD_TUGAS_LABEL'); ?>
                        </th>
                        <th class="center" width="5%">
                            <?php echo JText::_('COM_SIAK_NILAI_FIELD_UTS_LABEL'); ?>
                        </th>
                        <th class="center" width="5%">
                            <?php echo JText::_('COM_SIAK_NILAI_FIELD_UAS_LABEL'); ?>
                        </th>
                        <th class="center">
                            <?php echo 'Nilai'; ?>
                        </th>
                        <th class="center">
                            <?php echo 'Remidial'; ?>
                        </th>


                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <td colspan="10">
                            <?php echo $this->pagination->getListFooter(); ?>
                        </td>
                    </tr>
                </tfoot>
                <tbody>
                    <?php foreach ($this->items as $i => $item) { ?>
                    <tr class="row<?php echo $i % 2; ?>">
                        <td width="1%" class="center">
                            <?php echo JHtml::_('grid.id', $i, $item->id); ?>
                        </td>
                        <td class="nowrap">
                            <?php echo $item->npm; ?><br />
                            <div class="small break-word">
                                <?php echo $item->mahasiswa; ?>
                            </div>
                        </td>
                        <td class="nowrap">
                            <?php echo $item->kodemk; ?><br />
                            <div class="small break-word">
                                <?php echo $item->mk; ?>
                            </div>
                        </td>
                        <td class="nowrap" style="vertical-align : middle;">
                            <?php echo $item->semester; ?>
                        </td>
                        <?php
                        if ((bool) $item->nilai_final) {
                            ?>
                        <td colspan="4" class="nowrap center"></td>
                        <?php
                        } else {
                            ?>
                        <td class="nowrap center" style="vertical-align : middle;">
                            <?php echo $item->kehadiran; ?>
                        </td>
                        <td class="nowrap center" style="vertical-align : middle;">
                            <?php echo $item->tugas; ?>
                        </td>
                        <td class="nowrap center" style="vertical-align : middle;">
                            <?php echo $item->uts; ?>
                        </td>
                        <td class="center" style="vertical-align : middle;">
                            <?php echo $item->uas; ?>
                        </td>
                        <?php
                        }
                        ?>
                        <td class="center" style="vertical-align : middle;">
                            <div class="small">
                                <?php echo $item->nilai_akhir; ?> /
                                <?php echo $item->nilai_mutu; ?> /
                                <?php echo $item->nilai_angka; ?><br />

                                Input by: <?php echo JFactory::getUser($item->created_by)->name; ?><br />
                                Input Time: <?php echo JHTML::date($item->created_date, 'd F Y H:i:s'); ?>

                            </div>

                        </td>
                        <td class="center" style="vertical-align : middle;">
                            <?php if ('MURNI' != $item->status) {
                            echo '<div class="small">';
                            echo $item->nilai_akhir_remid.' / '.$item->nilai_remid_mutu.' / '.$item->nilai_remid_angka;
                            echo '<br />';

                            if (!empty($item->input_nilai_by)) {
                                echo 'Input oleh: '.JFactory::getUser($item->input_nilai_by)->name.'<br />';
                            } else {
                                echo 'Input oleh: N/a <br />';
                            }
                            echo '<br /> Input Time:'.JHTML::date($item->input_nilai_time, 'd F Y H:i:s');
                            echo '</div>';
                        } else {
                            echo '--';
                        }
                            ?>
                        </td>


                    </tr>
                    <?php } ?>
                </tbody>
            </table>
            <?php } ?>
            <input type="hidden" name="task" value="" />
            <input type="hidden" name="boxchecked" value="0" />
            <?php echo JHtml::_('form.token'); ?>
        </div>
</form>