<?php

defined('_JEXEC') or exit;

JHtml::_('behavior.multiselect');
JHtml::_('formbehavior.chosen', 'select');
$hari = [
    '1' => 'SUNDAY',
    '2' => 'MONDAY',
    '3' => 'TUESDAY',
    '4' => 'WEDNESDAY',
    '5' => 'THURSDAY',
    '6' => 'FRIDAY',
    '7' => 'SATURDAY',
];
?>

<form
    action="<?php echo JRoute::_('index.php?option=com_siak&view=jadwals', false); ?>"
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
                    <th class="nowrap">
                        <?php echo JText::_('COM_SIAK_FIELD_WAKTU_LABEL'); ?>
                    </th>
                    <th class="nowrap">
                        <?php echo JText::_('COM_SIAK_MATKUL_FIELD_NAMA_LABEL'); ?>
                    </th>

                    <th class="nowrap">
                        <?php echo JText::_('COM_SIAK_FIELD_NAMA_DOSEN_TITLE'); ?>
                    </th>
                    <th class="nowrap hidden-phone">
                        <?php echo JText::_('COM_SIAK_PRODI_TITLE_LABEL'); ?>
                    </th>
                    <th class="nowrap hidden-phone">
                        <?php echo JText::_('COM_SIAK_KELAS_TITLE_LABEL'); ?>
                    </th>
                    <th class="nowrap hidden-phone">
                        <?php echo JText::_('COM_SIAK_FIELD_SEMESTER_LABEL'); ?>
                    </th>
                    <th class="nowrap">
                        <?php echo JText::_('COM_SIAK_RUANGAN_FIELD_TITLE_LABEL'); ?>
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

                    <td class="nowrap">
                        <?php echo JText::_($hari[$item->hari]); ?><br />
                        <div class="small">
                            <?php echo $item->jam; ?>
                        </div>
                    </td>
                    <td class="nowrap">
                        <?php echo $item->kodeMK; ?><br />
                        <div class="small break-word">
                            <?php echo $item->matakuliah; ?>
                        </div>
                    </td>
                    <td class="nowrap">
                        <?php if (!empty($item->dosenID) && is_numeric($item->dosenID)) {
    echo JFactory::getUser($item->dosenID)->name;
} else {
    echo '';
}
                            ?>
                    </td>
                    <td class="nowrap  hidden-phone">
                        <?php echo $item->prodi; ?><br />
                        <div class="small">
                            <?php echo $item->konsentrasi; ?>
                        </div>
                    </td>

                    <td class="nowrap hidden-phone">
                        <?php echo $item->kelas; ?><br />
                    </td>

                    <td class="nowrap hidden-phone">
                        <?php echo $item->semester; ?><br />
                    </td>

                    <td class="nowrap">
                        <?php echo ucfirst($item->ruangan); ?>
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