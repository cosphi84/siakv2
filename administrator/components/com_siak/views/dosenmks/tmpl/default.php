<?php

defined('_JEXEC') or exit;

JHtml::_('behavior.multiselect');
JHtml::_('formbehavior.chosen', 'select');
$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn = $this->escape($this->state->get('list.direction'));

?>

<form
    action="<?php echo JRoute::_('index.php?option=com_siak&view=dosenmks'); ?>"
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
                        <th class="center">
                            <?php echo JHtml::_('grid.checkall'); ?>
                        </th>
                        <th class="nowrap">
                            <?php echo JText::_('COM_SIAK_FIELD_TAHUN_AJARAN_LABEL'); ?>
                        </th>
                        <th class="nowrap">
                            <?php echo JHtml::_('searchtools.sort', 'COM_SIAK_DOSENS_FIELD_NAME_LABEL', 'u.name', $listDirn, $listOrder); ?>
                        </th>
                        <th class="nowrap">
                            <?php echo JText::_('COM_SIAK_MATKUL_FIELD_NAMA_MK_LABEL'); ?>
                        </th>
                       
                        <th class="nowrap">
                            <?php echo Jtext::_('COM_SIAK_PRODI_FIELD_TITLE_LABEL'); ?>
                        </th>
                        <th class="nowrap">
                            <?php echo JText::_('COM_SIAK_KELAS_FIELD_TITLE_LABEL'); ?>
                        </th>
                        <th class="nowrap center hidden-phone">
                            <?php echo JText::_('JPUBLISHED'); ?>
                        </th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <td colspan="7" class="center">
                            <?php echo $this->pagination->getListFooter(); ?>
                        </td>
                    </tr>
                </tfoot>
                <tbody>
                    <?php foreach ($this->items as $key => $item) { ?>
                    <tr class="row<?php echo $key % 2; ?>">
                        <td class="center nowrap">
                            <?php echo JHtml::_('grid.id', $key, $item->id); ?>
                        </td>
                        <td>
                            <?php echo $item->tahun_ajaran; ?>
                        </td>
                        <td>
                            <?php echo $item->dosen; ?>
                        </td>
                        <td>
                            <?php echo $item->kodeMK; ?>
                            <span class="small break-world">
                                <div class="show-detail">
                                    <?php echo $item->MK; ?>
                                </div>
                            </span>
                        </td>
                        
                        <td class="nowrap">
                            <?php echo $item->prodi.' : '.$item->jurusan; ?>
                        </td>
                        <td>
                            <?php echo $item->kelas; ?>
                        </td>
                        <td class="nowrap center hidden-phone">
                            <?php echo JHtml::_('jgrid.published', $item->state, $key, 'dosenmks.', true, 'cb'); ?>
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
    </div>
</form>