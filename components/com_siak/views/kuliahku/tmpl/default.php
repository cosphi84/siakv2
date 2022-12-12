<?php

defined('_JEXEC') or exit();

JHtml::_('behavior.multiselect');
JHtml::_('formbehavior.chosen', 'select');
?>

<form
    action="<?php echo JRoute::_('index.php?option=com_siak&view=kuliahku'); ?>"
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
                        <?php echo JText::_('COM_SIAK_TA_TITLE_LABEL'); ?>
                    </th>
                    <th>
                        <?php echo JText::_('COM_SIAK_MATKUL_FIELD_KODE_LABEL'); ?>
                    </th>
                    <th>
                        <?php echo JText::_('COM_SIAK_MATKUL_FIELD_NAMA_LABEL'); ?>
                    </th>
                    <th>
                        <?php echo JText::_('COM_SIAK_MATKUL_FIELD_SKS_LABEL'); ?>
                    </th>
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <td colspan="5">
                        <?php echo $this->pagination->getListFooter(); ?>
                    </td>
                </tr>
            </tfoot>
            <tbody>
                <?php foreach ($this->items as $i => $item) { ?>
                <tr class="row<?php echo $i % 2; ?>">
                    <td class="center">
                        <?php echo $this->pagination->getRowOffset($i); ?>
                    </td>
                    <td>
                        <?php echo $item->tahun_ajaran; ?>
                    </td>
                    <td>
                        <?php echo $item->kodemk; ?>
                    </td>
                    <td>
                        <?php echo $item->matakuliah; ?>
                    </td>
                    <td>
                        <?php echo $item->sks; ?>
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