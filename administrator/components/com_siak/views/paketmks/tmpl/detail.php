<?php

defined('_JEXEC') or exit;

?>
<div class="j-main-container">
    <?php if (empty($this->items)) { ?>
    <div class="alert alert-no-items">
        <?php echo JText::_('JGLOBAL_NO_MATCHING_RESULTS'); ?>
    </div>
    <?php } else { ?>
    <table class="table table-striped" id="classesList">
        <thead>
            <tr>
                <th class="nowrap center">
                    #
                </th>
                <th class="nowrap">
                    <?php echo JText::_('COM_SIAK_MATKUL_FIELD_KODE_LABEL'); ?>
                </th>
                <th class="nowrap">
                    <?php echo JText::_('COM_SIAK_MATKUL_FIELD_NAMA_LABEL'); ?>
                </th>
                <th class="nowrap">
                    <?php echo JText::_('COM_SIAK_MATKUL_FIELD_SKS_LABEL'); ?>
                </th>
                <th class="nowrap">
                    <?php echo JText::_('COM_SIAK_MATKUL_FIELD_JENIS_MK_LABEL'); ?>
                </th>
                <th class="nowrap">
                    <?php echo JText::_('COM_SIAK_SEMESTER_FIELD_TITLE_LABEL'); ?>
                </th>
                <th class="nowrap">
                    <?php echo JText::_('COM_SIAK_MATKUL_FIELD_PEMILIK_LABEL'); ?>
                </th>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <td colspan="8">
                    <?php echo $this->pagination->getListFooter(); ?>
                </td>
            </tr>
        </tfoot>
        <tbody>
            <?php foreach ($this->items as $i => $item) { ?>
            <tr class="row<?php echo $i % 2; ?>">
                <td width="1%" class="center">
                    <?php echo $this->pagination->getRowOffset($i); ?>
                </td>
                <td class="nowrap">
                    <?php echo $item->kodeMK; ?>
                </td>
                <td class="nowrap">
                    <?php echo $item->namaMK; ?>
                </td>
                <td class="nowrap">
                    <?php echo $item->sks; ?>
                </td>
                <td class="nowrap">
                    <?php echo $item->jenisMK; ?>
                </td>
                <td class="nowrap">
                    <?php echo $item->semester; ?>
                </td>
                <td class="nowrap">
                    <?php echo $item->prodi.' : '.$item->jurusan; ?>
                </td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
    <?php } ?>
</div>