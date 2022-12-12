<?php

defined('_JEXEC') or exit;
use Joomla\CMS\HTML\HTMLHelper;

?>

<div class="container-fluid">
    <legend>
        <?php echo JText::_('COM_SIAK_VIEW_UJIANS_PAGE_LEGEND'); ?>
    </legend>

    <div class="clearfix"></div>
    <div class="row-well">
        <form
            action="<?php echo JRoute::_('index.php?option=com_siak&view=ujians'); ?>"
            method="post" name="adminForm" id="adminForm">
            <?php echo JLayoutHelper::render('joomla.searchtools.default', ['view' => $this]); ?>
            <input type="hidden" name="task" value="" />
            <input type="hidden" name="boxchecked" value="0" />
            <?php echo JHtml::_('form.token'); ?>
        </form>
        <?php if (empty($this->items)) { ?>
        <div class="alert alert-no-items">
            <?php echo JText::_('JGLOBAL_NO_MATCHING_RESULTS'); ?>
        </div>
        <?php } else { ?>
        <table class="table table-striped" id="list-jadwal">
            <thead>
                <tr>
                    <th class="nowrap center">
                        <?php echo JText::_('COM_SIAK_NO_URUT'); ?>
                    </th>
                    <th class="nowrap center">
                        <?php echo JText::_('COM_SIAK_KELAS_TITLE_LABEL'); ?>
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
                        <?php echo JText::_('COM_SIAK_FIELD_MATAKULIAH_TITLE'); ?>
                    </th>
                    <th class="nowrap center">
                        <?php echo JText::_('COM_SIAK_VIEW_UJIANS_PENGAWAS_LABEL'); ?>
                    </th>
                </tr>
            </thead>
            <tfoot>
                <td colspan="7" class="center">
                    <?php echo $this->pagination->getListFooter(); ?>
                </td>
            </tfoot>
            <tbody>
                <?php foreach ($this->items as $k => $item) {  ?>
                <tr class="row<?php echo $k % 2; ?>">
                    <td class="center">
                        <?php echo $this->pagination->getRowOffset($k); ?>
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
                        <?php echo $item->jam_akhir; ?>
                        <br /> <?php echo $item->ruangan; ?>
                    </td>
                    <td>
                        <?php echo $item->semester; ?>
                    </td>
                    <td class="nowrap">
                        <?php echo $item->kodemk; ?><br />
                        <div class="small">
                            <?php echo $item->matakuliah; ?>
                            <br />
                            <?php echo $item->dosen; ?>
                        </div>
                    </td>
                    <td class="nowrap">
                        <?php echo $item->pengawas; ?>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
        <?php } ?>

    </div>
</div>