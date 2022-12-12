<?php

defined('_JEXEC') or exit;

JHtml::_('behavior.multiselect');
JHtml::_('formbehavior.chosen', 'select');
$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn = $this->escape($this->state->get('list.direction'));

?>
<form method="POST" action="index.php?option=com_siak&view=rombels" name="adminForm" id="adminForm">
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
                        <th>
                            <?php echo JHtml::_('searchtools.sort', 'COM_SIAK_MAHASISWA_FIELD_TITLE_LABEL', 'u.name', $listDirn, $listOrder); ?>
                        </th>
                        <th>
                            <?php echo JHtml::_('searchtools.sort', 'COM_SIAK_PRODI_FIELD_TITLE_LABEL', 'r.prodi', $listDirn, $listOrder); ?>
                        </th>
                        <th>
                            <?php echo JText::_('COM_SIAK_KELAS_FIELD_TITLE_LABEL'); ?>
                        </th>
                        <th>
                            <?php echo JText::_('COM_SIAK_FIELD_TAHUN_AJARAN_LABEL'); ?>
                        </th>
                        <th class="center">
                            <?php echo JText::_('COM_SIAK_CONFIRM'); ?>
                        </th>
                        <th width="1%" class="nowrap hidden-phone">
                            <?php echo JText::_('JGRID_HEADING_ID'); ?>
                        </th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <td colspan="5" class="center">
                            <?php echo $this->pagination->getListFooter(); ?>
                        </td>
                    </tr>
                </tfoot>
                <tbod>
                    <?php foreach ($this->items as $i => $j) { ?>
                    <tr class="row<?php echo $i % 2; ?>">
                        <td class="center">
                            <?php echo JHtml::_('grid.id', $i, $j->id); ?>
                        </td>
                        <td>
                            <?php echo $j->npm; ?><br>
                            <span class="small break-word">
                                <?php echo $j->mahasiswa; ?>
                            </span>
                        </td>
                        <td>
                            <?php echo $j->prodi; ?><br>
                            <span class="small break-word">
                                <?php echo JText::sprintf('COM_SIAK_ALIAS_JURUSAN', $this->escape($j->jurusan)); ?>
                            </span>
                        </td>
                        <td>
                            <?php echo $j->kelas; ?>
                        </td>
                        <td>
                            <?php echo $j->ta; ?>
                        </td>
                        <td class="center">
                            <?php echo JHtml::_('jgrid.published', $j->state, $i, 'rombels.', true, 'cb'); ?>
                        </td>
                        <td class="center">
                            <?php echo $j->id; ?>
                        </td>
                    </tr>
                    <?php } ?>
                </tbod>
            </table>
            <?php } ?>
            <input type="hidden" name="task" value="" />
            <input type="hidden" name="boxchecked" value="0" />
            <?php echo JHtml::_('form.token'); ?>
        </div>
</form>