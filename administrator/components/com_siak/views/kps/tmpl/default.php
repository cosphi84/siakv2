<?php

defined('_JEXEC') or die('Restricted Access');
JHtml::_('behavior.multiselect');
JHtml::_('formbehavior.chosen', 'select');
$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn = $this->escape($this->state->get('list.direction'));
?>

<form action="index.php?option=com_siak&view=kps" method="post" id="adminForm" name="adminForm">
    <div id="j-sidebar-container" class="span2">
        <?php echo JHtmlSidebar::render(); ?>
    </div>
    <div id="j-main-container" class="span10">
        <div class="row-fluid">
            <div class="span10">
                <?php echo JLayoutHelper::render('joomla.searchtools.default', ['view' => $this]); ?>
            </div>
        </div>
        <?php if (empty($this->items)) { ?>
        <div class="alert alert-no-items">
            <?php echo JText::_('JGLOBAL_NO_MATCHING_RESULTS'); ?>
        </div>
        <?php } else { ?>
        <table class="table table-striped table-hover" id="KpList">
            <thead>
                <tr>
                    <th class="nowrap center">
                        <?php echo JHtml::_('grid.checkall'); ?>
                    </th>
                    <th class="nowrap">
                        <?php echo JText::_('COM_SIAK_MAHASISWA_FIELD_TITLE_LABEL'); ?>
                    </th>
                    <th class="nowrap">
                        <?php echo JText::_('COM_SIAK_PRODI_FIELD_TITLE_LABEL'); ?>
                    </th>

                    <th class="nowrap">
                        <?php echo JText::_('COM_SIAK_FIELD_TAHUN_AJARAN_LABEL'); ?>
                    </th>
                    <th class="nowrap">
                        <?php echo JText::_('COM_SIAK_KP_TANGGAL_DAFTAR_LABEL'); ?>
                    </th>
                    <th class="nowrap">
                        <?php echo JText::_('COM_SIAK_FIELD_KULIAH_KP_LABEL'); ?>
                    </th>
                    <th class="nowrap">
                        <?php echo JText::_('COM_SIAK_FIELD_TANGGAL_SIDANG_KP_LABEL'); ?>
                    </th>
                    <th class="nowrap">
                        <?php echo JText::_('COM_SIAK_KP_SURAT_PENGANTAR_NO_LABEL'); ?>
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
                    <td class="center">
                        <?php echo JHtml::_('grid.id', $i, $item->id); ?>
                    </td>
                    <td>
                        <?php echo $item->npm; ?> <br />
                        <span class="small break-word">
                            <?php echo $item->mahasiswa; ?>
                        </span>
                    </td>
                    <td>
                        <?php echo $item->prodi; ?> <br />
                        <span class="small break-word">
                            <?php echo $item->jurusan; ?> :
                            <?php echo $item->kelas; ?>
                        </span>
                    </td>
                    <td>
                        <?php echo $item->ta; ?>
                    </td>
                    <td>
                        <?php echo JHtml::_('date', $item->tanggal_daftar, 'd.m.Y H:i:s'); ?>
                    </td>
                    <td>
                        <?php echo $item->tempatKP; ?> <br />
                        <span class="small break-word">
                            <?php echo JHtml::_('date', $item->start, 'd.m.Y'); ?>
                            s/d
                            <?php echo JHtml::_('date', $item->finish, 'd.m.Y'); ?>
                            <br />
                            Dosbing: <?php echo $item->dosbing; ?>
                        </span>
                    </td>
                    <td>

                        <?php
                        if ('0000-00-00' == $item->tanggal_seminar) {
                            echo '-';
                        } else {
                            echo JHtml::_('date', $item->tanggal_seminar, 'd.m.Y'); ?>
                        <?php if ('' != $item->file_laporan) { ?>
                        <div class="small">
                            <a
                                href="<?php echo JURI::root().'media/com_siak/files/kp/'.$item->file_laporan; ?>">Laporan
                                KP</a>
                        </div>
                        <?php }
                        } ?>
                    </td>
                    <td>
                        <?php echo $item->no_surat; ?>
                    </td>

                    <td class="nowrap center hidden-phone">
                        <?php echo JHtml::_('jgrid.published', $item->state, $i, 'kps.', true, 'cb'); ?>
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