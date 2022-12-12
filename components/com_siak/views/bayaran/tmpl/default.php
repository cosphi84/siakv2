<?php

defined('_JEXEC') or exit;

use Joomla\CMS\HTML\HTMLHelper;

JHtml::_('formbehavior.chosen', 'select');
JHtml::_('behavior.core');
$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn = $this->escape($this->state->get('list.direction'));

?>
<form
    action="<?php echo JRoute::_('index.php?option=com_siak&view=bayaran'); ?>"
    method="post" name="adminForm" id="adminForm">

    <div class="j-main-container">
        <legend><?php echo JText::_('COM_SIAK_BAYARAN_LEGEND'); ?>
        </legend>
        <div class="clearfix"></div>
        <?php echo JLayoutHelper::render('joomla.searchtools.default', ['view' => $this]); ?>

        <div class="row-flex well">
            <?php if (empty($this->items)) { ?>
            <div class="alert alert-no-items">
                <?php echo JText::_('JGLOBAL_NO_MATCHING_RESULTS'); ?>
            </div>
            <?php } else { ?>
            <table class="table table-striped" id="classeslist">
                <thead>
                    <tr>
                        <th>
                            <?php echo JText::_('COM_SIAK_NO_URUT'); ?>
                        </th>
                        <th>
                            <?php echo JText::_('SOM_SIAK_BAYARAN_NO_REF_FIELD_TITLE'); ?>
                        </th>
                        <th>
                            <?php echo JText::_('COM_SIAK_BAYARAN_PEMBAYARAN_FIELD_TITLE'); ?>
                        </th>
                        <th>
                            <?php echo JText::_('COM_SIAK_BAYARAN_TANGGAL_BAYAR_FIELD_TITLE'); ?>
                        </th>
                        <th>
                            <?php echo JText::_('COM_SIAK_FIELD_SEMESTER_LABEL'); ?>
                        </th>
                        <th>
                            <?php echo JText::_('COM_SIAK_FIELD_JUMLAH_TITLE'); ?>
                        </th>
                        <th>
                            <?php echo JText::_('COM_SIAK_FIELD_LUNAS_TITLE'); ?>
                        </th>
                        <th>
                            <?php echo JText::_('COM_SIAK_KONF_FIELD_STATUS_LABEL'); ?>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($this->items as $i => $item) { ?>
                    <tr class="row<?php echo $i % 2; ?>">
                        <td class="center">
                            <?php echo $this->pagination->getRowOffset($i); ?>
                        </td>
                        <td>
                            <a href="javascript:void(0)"
                                onclick="Joomla.popupWindow('<?php echo JURI::root().'media/com_siak/files/kuitansi/'.$this->escape($item->kuitansi); ?>', 'FotoKuitansi', 800, 500, 'true')">
                                <?php echo $item->no_ref; ?>
                            </a>
                        </td>
                        <td>
                            <?php echo $item->pembayaran; ?>
                            <?php if ('PRAKTIKUM' === $item->pembayaran) {
    echo '<br />';
    echo '<div class="small">';
    echo $item->kodeMK.' / '.$item->mk;
    echo '</div>';
}?>
                        </td>
                        <td>
                            <?php echo HTMLHelper::date($item->tanggal_bayar, 'd/m/Y'); ?>
                        </td>
                        <td>
                            <?php echo $item->Sem; ?>
                        </td>
                        <td>
                            <?php echo $item->jumlah; ?>
                        </td>
                        <td>
                            <?php if ((bool) $item->lunas) {
    echo 'LUNAS';
} else {
    echo 'Belum LUNAS';
}
                            ?>
                        </td>
                        <?php
                            if ((bool) $item->confirm) {
                                echo '<td class="hijau">';
                                echo 'Confirmed';
                            } else {
                                echo '<td class="merah">';
                                echo 'UnConfirmed';
                            }
                        ?>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="6">
                            <?php echo $this->pagination->getListFooter(); ?>
                        </td>
                    </tr>
                </tfoot>
            </table>
            <?php } ?>
        </div>
    </div>
    <input type="hidden" name="task" value="" />
    <input type="hidden" name="boxchecked" value="0" />
    <?php echo JHtml::_('form.token'); ?>
</form>