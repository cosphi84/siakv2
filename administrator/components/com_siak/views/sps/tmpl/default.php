<?php

defined('_JEXEC') or exit;
use Joomla\CMS\HTML\HTMLHelper;

JHtml::_('behavior.multiselect');
JHtml::_('formbehavior.chosen', 'select');
?>

<form method="POST" action="index.php?option=com_siak&view=sps" name="adminForm" id="adminForm">
    <?php if (!empty($this->sidebar)) { ?>
    <div id="j-sidebar-container" class="span2">
        <?php echo $this->sidebar; ?>
    </div>
    <div id="j-main-container" class="span10">
        <?php } else { ?>
        <div id="j-main-container">
            <?php } ?>

            <?php echo JLayoutHelper::render('joomla.searchtools.default', ['view' => $this]); ?>

            <?php if (empty($this->items)) { ?>
            <div class="alert alert-no-items">
                <?php echo JText::_('JGLOBAL_NO_MATCHING_RESULTS'); ?>
            </div>
            <?php } else { ?>
            <table class="table table-striped" id="classesList">
                <thead>
                    <tr>
                            <th> 
                                <?php echo JHtml::_('grid.checkall'); ?>
                            </th>
                            <th>
                                <?php echo JText::_('COM_SIAK_MAHASISWA_FIELD_TITLE_LABEL'); ?>
                            </th>
                            <th>
                                <?php echo JText::_('COM_SIAK_PRODI_FIELD_TITLE_LABEL'); ?>
                            </th>
                            <th>
                                <?php echo JText::_('COM_SIAK_MATKUL_FIELD_NAMA_LABEL'); ?>
                            </th>
                            <th>
                                <?php echo JText::_('COM_SIAK_SPS_NILAI_SEBELUM_REMIDIAL_LABEL'); ?>
                            </th>
                            <th>
                                <?php echo JText::_('COM_SIAK_SPS_NILAI_SETELAH_REMIDIAL_LABEL'); ?>
                            </th>
                            <th>
                                <?php echo JText::_('COM_SIAK_KP_TANGGAL_DAFTAR_LABEL'); ?>
                            </th>
                            <th>
                                <?php echo JText::_('JPUBLISHED'); ?>
                            </th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <td colspan="8" class="center">
                                <?php echo $this->pagination->getListFooter(); ?>
                        </td>
                    </tr>
                </tfoot>
                <tbody>
                    <?php foreach ($this->items as $i => $item) { ?>
                    <tr class="row<?php echo $i % 2; ?>">
                        <td class="center">
                            <?php echo JHtml::_('grid.id', $i, $item->id); ?>
                        </td>
                        <td class="nowrap">
                            <?php echo $item->npm; ?><br />
                            <div class="small">
                                <?php echo $item->mahasiswa; ?>
                            </div>
                        </td>
                        <td class="nowrap">
                            <?php echo $item->prodi.' / '.$item->jurusan; ?><br />
                            <?php echo $item->smt.' / '.$item->kelas; ?><br />
                            <?php echo $item->tahun_ajaran; ?>
                        </td>
                        <td class="nowrap">
                            <?php echo $item->kodemk.' / '.$item->sks.' SKS'; ?>
                            <div class="small">
                                <?php echo $item->matakuliah; ?><br />
                                <?php echo $item->dosen; ?>
                            </div>
                        </td>
                        <td class="nowrap">
                            <?php echo $item->nilai_akhir.' / '.$item->nilai_mutu.' / '.$item->nilai_angka; ?>
                            <br />
                            <?php if (empty($item->created_by)) {
    echo 'Input oleh: --';
} else {
    echo 'Input oleh: '.JFactory::getUser($item->created_by)->name.'<br />pada: '.JHTML::date($item->created_date, 'd F Y H:i:s');
}
?>
                        </td>
                        <td class="nowrap">
<?php echo $item->nilai_akhir_remid.' / '.$item->nilai_remid_mutu.' / '.$item->nilai_remid_angka; ?>
<div class="small">
<?php if (empty($item->input_nilai_by)) {
    echo 'Input oleh: --';
} else {
    echo 'Input oleh: '.JFactory::getUser($item->input_nilai_by)->name.'<br/>pada: '.JHTML::date($item->input_nilai_time, 'd F Y H:i:s');
}
?>
</div>
                        </td>
                        <td class="nowrap">
                            <?php echo HTMLHelper::date($item->tanggal_daftar, 'd/m/Y H:i:s'); ?>
                        </td>
                        <td class="center">
                            <?php echo JHtml::_('jgrid.published', $item->state, $i, 'sps.', true, 'cb'); ?>
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
