<?php
defined('_JEXEC') or exit;

JHtml::_('behavior.multiselect');
JHtml::_('formbehavior.chosen', 'select');

?>

<form
    action="<?php echo JRoute::_('index.php?option=com_siak&view=nilai'); ?>"
    method="post" name="adminForm" id="adminForm">


    <div class="j-main-container">

        <?php echo JLayoutHelper::render('joomla.searchtools.default', ['view' => $this]); ?>
        <?php if (empty($this->items)) { ?>
        <div class="alert alert-no-items">
            <?php echo JText::_('JGLOBAL_NO_MATCHING_RESULTS'); ?>
        </div>
        <?php } else { ?>
        <table class="table table-striped" id="classesList">
            <thead>
                <tr>
                    <th class="center">
                        <?php echo JText::_('COM_SIAK_NO_URUT'); ?>
                    </th>
                    <th>
                        <?php echo JText::_('COM_SIAK_MAHASISWA_LABEL'); ?>
                    </th>
                    <th>
                        <?php echo JText::_('COM_SIAK_MATKUL_FIELD_NAMA_LABEL'); ?>
                    </th>

                    <th class="center">
                        <?php echo 'Nilai Reguler<br />(Nilai Akhir / Angka / Huruf)'; ?>
                    </th>

                    <th class="center">
                        <?php echo 'Nilai Perbaikan'; ?>
                    </th>


                </tr>
            </thead>
            <tfoot>
                <tr>
                    <td colspan="6">
                        <?php echo $this->pagination->getListFooter(); ?>
                    </td>
                </tr>
            </tfoot>
            <tbody>
                <?php foreach ($this->items as $i => $item) { ?>
                <tr class="row<?php echo $i % 2; ?>">
                    <td class="center" style="vertical-align : middle;">
                        <?php echo $this->pagination->getRowOffset($i); ?>
                    </td>
                    <td>
                        <?php echo $item->npm; ?>
                        <div class="small break-word">
                            <?php echo $item->mahasiswa; ?>
                        </div>
                    </td>
                    <td>
                        <?php echo $item->kodemk; ?>
                        <div class="small break-word">
                            <?php echo $item->mk; ?><br />
                            <?php echo $item->dosen; ?>
                        </div>
                    </td>
                    <td>
                        <?php echo $item->nilai_akhir; ?> /
                        <?php echo $item->nilai_mutu; ?> /
                        <?php echo $item->nilai_angka; ?><br />

                        <div class="small">
                            <?php if (!empty($item->created_by)) {
    ?>

                            Input oleh: <?php echo JFactory::getUser($item->created_by)->name; ?><br />
                            pada <?php echo JHTML::date($item->created_date, 'd F Y H:i:s'); ?>
                            <?php
} else {
        echo 'Input oleh: Belum ada datanya.<br/>';
    } ?>
                        </div>
                    </td>


                    <?php if ('MURNI' == $item->status) {
        echo '<td class="center">';
        echo '--';
    } else {
        echo '<td>';
        echo $item->nilai_akhir_remid.' / '.$item->nilai_remid_mutu.' / '.$item->nilai_remid_angka.'<br />';
        echo '<div class="small">';
        if (!empty($item->input_nilai_by)) {
            echo 'Input oleh: '.JFactory::getUser($item->input_nilai_by)->name.'<br />';
        } else {
            echo 'Input oleh: N/a <br />';
        }
        echo 'pada: '.JHTML::date($item->input_nilai_time, 'd F Y H:i:s');
        echo '</div>';
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