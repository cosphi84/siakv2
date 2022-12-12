<?php

defined('_JEXEC') or exit;

JHtml::_('behavior.multiselect');
JHtml::_('formbehavior.chosen', 'select');
JHtml::_('script', 'com_siak/siak.js', ['relative' => true]);
$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn = $this->escape($this->state->get('list.direction'));

?>
<form method="POST" action="index.php?option=com_siak&view=mahasiswas" name="adminForm" id="adminForm">
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
                            <?php echo JHtml::_('searchtools.sort', 'COM_SIAK_PRODI_FIELD_TITLE_LABEL', 'n.prodi', $listDirn, $listOrder); ?>
                        </th>
                        <th>
                            <?php echo JText::_('COM_SIAK_SEMESTER_FIELD_TITLE_LABEL'); ?>
                        </th>
                        <th>
                            <?php echo JText::_('COM_SIAK_KELAS_FIELD_TITLE_LABEL'); ?>
                        </th>
                        <th>
                            <?php echo JText::_('COM_SIAK_MAHASISWA_FIELD_STATUS_LABEL'); ?>
                        </th>
                        <th>
                            <?php echo JText::_('COM_SIAK_FIELD_TAHUN_AJARAN_LABEL'); ?>
                        </th>
                        <th class="center">
                            <?php echo JText::_('COM_SIAK_CONFIRM'); ?>
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
                    <?php foreach ($this->items as $a => $b) { ?>
                    <tr class="row<?php echo $a % 2; ?>">
                        <td class="center">
                            <?php echo JHtml::_('grid.id', $a, $b->id); ?>
                        </td>
                        <td>
                            <?php echo $b->npm; ?>
                            <span class="small break-word"><br />
                                <?php echo JText::_($this->escape($b->mahasiswa)); ?>
                            </span>
                        </td>
                        <td>
                            <?php echo $b->prodi; ?>
                            <span class="small break-word"><br />
                                <?php echo $this->escape($b->jurusan); ?>
                            </span>
                        </td>
                        <td>
                            <?php echo $b->semester; ?>
                        </td>
                        <td>
                            <?php echo $b->kelas; ?>
                        </td>
                        <td>
                            <?php echo Siak::statusMahasiswa($b->status); ?>
                        </td>
                        <td>
                            <?php echo $b->ta; ?>
                        </td>
                        <td class="center">
                            <?php echo JHtml::_('jgrid.published', $b->confirm, $a, 'mahasiswas.', true, 'cb'); ?>
                        </td>
                    </tr>
                    <?php } ?>
                    <input type="hidden" name="task" value="" />
                    <input type="hidden" name="boxchecked" value="0" />
                    <?php echo JHtml::_('form.token'); ?>
                </tbody>
            </table>
            <?php } ?>
</form>