<?php

defined('_JEXEC') or exit;

JHtml::_('behavior.multiselect');
JHtml::_('formbehavior.chosen', 'select');
?>

<form
    action="<?php echo JRoute::_('index.php?option=com_siak&view=matkuls'); ?>"
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
            <table class="table table-striped" id="classesList">
                <thead>
                    <tr>
                        <th class="nowrap center">
                            <?php echo JHtml::_('grid.checkall'); ?>
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
                            <?php echo JText::_('COM_SIAK_MATKUL_FIELD_PEMILIK_LABEL'); ?>
                        </th>
                        <th class="nowrap center hidden-phone">
                            <?php echo JText::_('JPUBLISHED'); ?>
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
                            <?php echo JHtml::_('grid.id', $i, $item->id); ?>
                        </td>
                        <td class="nowrap">
                            <?php echo $item->kode; ?> <br />
                            <div class="small">
                                <?php echo $item->matkul; ?>
                            </div>
                        </td>

                        <td class="nowrap">
                            <?php echo $item->sks; ?>
                        </td>
                        <td class="nowrap">
                            <?php echo $item->tipe_mk; ?>
                        </td>
                        <td class="nowrap">
                            <?php echo $item->prodi; ?> <br />
                            <div class="small">
                                <?php echo $item->jurusan; ?>
                            </div>
                        </td>
                        <td class="nowrap center hidden-phone">
                            <?php echo JHtml::_('jgrid.published', $item->published, $i, 'matkuls.', true, 'cb'); ?>
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
