<?php

use Joomla\CMS\Date\Date;

defined('_JEXEC') or exit;

JHtml::_('behavior.multiselect');
JHtml::_('formbehavior.chosen', 'select');
JHtml::_('behavior.core');
$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn = $this->escape($this->state->get('list.direction'));
JFactory::getDocument()->addStyleSheet(JURI::root().'media/com_siak/css/siak.css');
JHtml::_('script', 'com_siak/siak.js', ['version' => 'auto', 'relative' => true]);
$stsLunas = ['Belum Bayar', 'Belum Lunas', 'Lunas'];
$stsKonfirmasi = ['<span class="icon-cancel-circle merah"> Belum<span>',
    '<span class="icon-checkmark-circle hijau"> Sudah<span>', ];

?>

<form
    action="<?php echo JRoute::_('index.php?option=com_siak&view=bayarans'); ?>"
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
                            <?php echo JHtml::_('searchtools.sort', 'COM_SIAK_MAHASISWA_FIELD_TITLE_LABEL', 'u.name', $listDirn, $listOrder); ?>
                        </th>
                        <th class="nowrap">
                            <?php echo JText::_('COM_SIAK_BAYARAN_TANGGAL_SETOR_LABEL'); ?>
                        </th>
                        <th class="nowrap">
                            <?php echo JText::_('COM_SIAK_BAYARAN_PEMBAYARAN_LABEL'); ?>
                        </th>
                        <th class="nowrap">
                            <?php echo JText::_('COM_SIAK_BAYARAN_JUMLAH_SETOR_LABEL'); ?>
                        </th>
                        </th>
                        <th class="nowrap">
                            <?php echo Jtext::_('COM_SIAK_SEMESTER_FIELD_TITLE_LABEL'); ?>
                        </th>
                        <th class="nowrap">
                            <?php echo JText::_('COM_SIAK_BAYARAN_STATUS_TAGIHAN_LABEL'); ?>
                        </th>
                        <th class="nowrap">
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
                    <?php foreach ($this->items as $key => $item) { ?>
                    <tr class="row<?php echo $key % 2; ?>">
                        <td class="center nowrap">
                            <?php echo JHtml::_('grid.id', $key, $item->id); ?>
                        </td>
                        <td>
                            <?php echo $item->npm; ?>
                            <span class="small break-word">
                                <div class="show-detail">
                                    <?php echo $item->mahasiswa; ?>
                                </div>
                            </span>
                        </td>
                        <td>
                            <?php $tgl = new Date($item->tanggal_bayar);
                                  echo $tgl->format('d F Y', true);
                            ?>

                        </td>
                        <td>
                            <?php echo $item->pembayaran;
                            if ('PRAKTIKUM' == $item->pembayaran) { ?><br />
                            <span class="small break-word">
                                <?php echo $item->kodemk.' : '.$item->mk; ?>
                            </span>
                            <?php } ?>
                        </td>
                        </td>
                        <td class="nowrap">
                            <div class="rupiah">
                                <?php echo $item->jumlah; ?>
                            </div>
                            <div class="small">
                                <a href="javascript:void(0);"
                                    onclick="Joomla.popupWindow('index.php?option=com_siak&view=bayaran&layout=kuitansi&tmpl=component&id=<?php echo $item->id; ?>', 'kuitansi', '750', '300', false )">
                                    Lihat Kuitansi</a>
                            </div>
                        </td>
                        <td>
                            <?php echo $item->semester; ?><br />
                            <span class="small break-word">
                                TA: <?php echo $item->ta; ?>
                            </span>
                        </td>
                        <td>
                            <?php echo $stsLunas[$item->lunas]; ?>
                        </td>
                        <td class="nowrap">
                            <?php echo $stsKonfirmasi[$item->confirm]; ?>
                            <?php if ((bool) $item->confirm) { ?>
                            <div class="small">
                                <?php $cfm = new Date($item->confirm_time);
                                    echo $cfm->format('d.m.Y H:i:s'); ?>
                            </div>
                            <?php } ?>

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