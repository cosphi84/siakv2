<?php

defined('_JEXEC') or exit;
use Joomla\CMS\HTML\HTMLHelper;

JHtml::_('behavior.multiselect');
JHtml::_('formbehavior.chosen', 'select');
$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn = $this->escape($this->state->get('list.direction'));

?>

<form action="index.php?option=com_siak&view=ujians" method="post" name="adminForm" id="adminForm">

    <div id="j-sidebar-container" class="span2">
        <?php echo $this->sidebar; ?>
    </div>
    <div id="j-main-container" class="span10">

        <?php echo JLayoutHelper::render('joomla.searchtools.default', ['view' => $this]); ?>

        <?php if (empty($this->items)) { ?>
        <div class="alert alert-no-items">
            <?php echo JText::_('JGLOBAL_NO_MATCHING_RESULTS'); ?>
        </div>
        <?php } else { ?>
        <table class="table table-striped" id="classesList">
            <thead>
                <tr>
                    <th class="nowrap center">
                        <?php echo JHtml::_('grid.checkall'); ?>
                    </th>
                    <th class="nowrap center">
                        <?php echo JText::_('COM_SIAK_KELAS_FIELD_TITLE_LABEL'); ?>
                    </th>
                    <th class="nowrap center">
                        <?php echo JText::_('COM_SIAK_GENERAL_DAY'); ?><br />
                        <?php echo JText::_('JDATE'); ?>
                    </th>
                    <th class="nowrap center">
                        <?php echo JText::_('COM_SIAK_GENERAL_TIME'); ?>
                    </th>
                    <th class="center">
                        <?php echo JText::_('COM_SIAK_VIEW_UJIANS_SEMESTER_LABEL'); ?>
                    </th>
                    <th class="nowrap center">
                        <?php echo JText::_('COM_SIAK_MATKUL_FIELD_NAMA_LABEL'); ?>
                    </th>
                    <th class="nowrap center">
                        <?php echo JText::_('COM_SIAK_VIEW_UJIANS_PENGAWAS_LABEL'); ?>
                    </th>
                    <th class="nowrap center hidden-phone">
                        <?php echo JText::_('JPUBLISHED'); ?>
                    </th>
                </tr>
            </thead>
            <tfoot>
                <td colspan="8" class="center">
                    <?php echo $this->pagination->getListFooter(); ?>
                </td>
            </tfoot>
            <tbody>
                <?php foreach ($this->items as $k => $item) {  ?>
                <tr class="row<?php echo $k % 2; ?>">
                    <td class="center">
                        <?php echo JHtml::_('grid.id', $k, $item->id); ?>
                    </td>
                    <td>
                        <?php echo $item->kelas; ?>
                    </td>
                    <td class="nowrap">
                        <?php echo JText::_($item->hari); ?><br />
                        <?php echo HtmlHelper::date($item->tanggal, 'd/m/Y'); ?>
                    </td>
                    <td class="nowrap">
                        <?php echo $item->jam_mulai; ?> -
                        <?php echo $item->jam_akhir; ?><br>
                        <?php echo $item->ruangan; ?>
                    </td>
                    <td>
                        <?php echo $item->semester; ?>
                    </td>
                    <td class="nowrap">
                        <?php echo $item->kodemk; ?><br />
                        <div class="small"><?php echo $item->matakuliah; ?>
                            <br />
                            <?php echo $item->dosen; ?>
                        </div>
                    </td>
                    <td class="nowrap">
                        <?php echo $item->pengawas; ?>
                    </td>
                    <td class="nowrap center hidden-phone">
                        <?php echo JHtml::_('jgrid.published', $item->state, $k, 'ujians.', true, 'cb'); ?>
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