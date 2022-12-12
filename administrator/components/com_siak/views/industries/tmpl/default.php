<?php

use Joomla\CMS\Date\Date;

defined('_JEXEC') or exit;

JHtml::_('behavior.multiselect');
JHtml::_('formbehavior.chosen', 'select');
$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn = $this->escape($this->state->get('list.direction'));
?>

<form
    action="<?php echo JRoute::_('index.php?option=com_siak&view=industries'); ?>"
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
                            <?php echo JHtml::_('searchtools.sort', 'COM_SIAK_INDUSTRI_NAMA_LABEL', 'i.nama', $listDirn, $listOrder); ?>
                        </th>
                        <th class="nowrap">
                            <?php echo JText::_('COM_SIAK_INDUSTRI_PIC_LABEL'); ?>
                        </th>
                        <th class="nowrap">
                            <?php echo JText::_('COM_SIAK_INDUSTRI_FIELD_NO_DOCUMENT_LABEL'); ?>
                        </th>
                        <th class="nowrap center">
                            <?php echo JText::_('COM_SIAK_INDUSTRI_FIELD_MASA_BERLAKU_TITLE'); ?>
                        </th>
                        <th class="nowrap">
                            <?php echo JText::_('COM_SIAK_FIELD_RECOREDED_BY_TITLE'); ?>
                        </th>
                        <th class="nowrap center hidden-phone">
                            <?php echo JText::_('JPUBLISHED'); ?>
                        </th>

                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <td colspan="6">
                            <?php echo $this->pagination->getListFooter(); ?>
                        </td>
                    </tr>
                </tfoot>
                <tbody>
                    <?php foreach ($this->items as $i => $item) { ?>
                    <?php $tglMulai = new Date($item->tanggal_kerjasama);
                            $tglFinish = new Date($item->tanggal_berakhir);
                            ?>
                    <tr class="row<?php echo $i % 2; ?>">
                        <td width="1%" class="center">
                            <?php echo JHtml::_('grid.id', $i, $item->id); ?>
                        </td>
                        <td class="nowrap">
                            <?php echo $item->nama; ?><br />
                            <div class="small">
                                <div class="break-word">
                                    <span class="icon-location"> <?php echo $item->alamat; ?> <br />
                                </div>
                                <span class="icon-home-2"></span> <?php echo $item->kabupaten.', '.$item->propinsi; ?><br />
                                <span class="icon-phone"></span> <?php echo $item->telepon; ?><br />
                                <span class="icon-envelope"></span> <?php echo $item->email; ?>
                            </div>
                        </td>
                        <td class="nowrap">
                            <span class="icon-user"></span> <?php echo $item->pic; ?><br>
                            <div class="small">
                                <span class="icon-tree-2"></span> <?php echo $item->jabatan_pic; ?><br />
                                <span class="icon-phone"></span> <?php echo $item->telepon_pic; ?>
                            </div>
                        </td>
                        <td class="nowrap">
                            <?php
                                if ('' != $item->dokumen_kerjasama) {
                                    echo '<a href="'.JURI::root().'media/com_siak/files/industri/'.$item->dokumen_kerjasama.'">';
                                    echo $item->no_dokumen_kerjasama;
                                    echo '</a>';
                                } else {
                                    echo $item->no_dokumen_kerjasama;
                                }?>
                        </td>
                        <td class="nowrap center">
                            <?php
                                if ('0000-00-00' != $item->tanggal_kerjasama) {
                                    echo JHtml::_('date', $item->tanggal_kerjasama, 'd F Y');
                                } else {
                                    echo 'N/a';
                                }

                                if ('0000-00-00' != $item->tanggal_berakhir) {
                                    echo '<br />s/d<br />';
                                    echo JHtml::_('date', $item->tanggal_berakhir, 'd F Y');
                                }
                                ?>
                        </td>
                        <td>
                            <?php echo $item->dibuat_oleh; ?> <br />
                            <span class="small">
                                <?php echo JHtml::_('date', $item->created_time, 'd.m.Y H:i:s', true, true); ?>
                            </span>
                        </td>
                        <td class="nowrap center hidden-phone">
                            <?php echo JHtml::_('jgrid.published', $item->state, $i, 'industries.', true, 'cb'); ?>
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