<?php
defined('_JEXEC') or die;

?>

<div class="well">
    <?php
    if (empty($this->items)) {
        ?>
    <div class="alert alert-no-items">
        <?php echo JText::_('JGLOBAL_NO_MATCHING_RESULTS'); ?>
    </div>
    <?php
    } else {  ?>
    <table class="table table-striped" id="classesList">
        <thead>
            <tr>
                <th><?php echo JText::_('COM_SIAK_FIELD_MATAKULIAH_TITLE'); ?>
                </th>
                <th class="hidden-phone"><?php echo JText::_('COM_SIAK_FIELD_SKS_TITLE'); ?>
                </th>
                <th><?php echo JText::_('COM_SIAK_FIELD_NAMA_DOSEN_TITLE'); ?>
                </th>
                <th class="hidden-phone"><?php echo JText::_('COM_SIAK_KELAS_TITLE_LABEL'); ?>
                </th>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <td colspan="4">
                    <?php echo $this->pagination->getListFooter(); ?>
                </td>
            </tr>
        </tfoot>
        <tbody>
            <?php foreach ($this->items as $i => $item) {
        ?>
            <tr class="row<?php echo $i % 2; ?>">
                <td><?php echo $item->kode; ?><br>
                    <?php echo $item->matakuliah; ?>
                </td>
                <td class="hidden-phone"><?php echo $item->sks; ?>
                </td>
                <td><?php echo $item->dosen; ?>
                </td>
                <td class="hidden-phone"><?php echo $item->kelas; ?>
                </td>
            </tr>
            <?php
    } ?>
        </tbody>
    </table>
    <?php
    }
     ?>
</div>