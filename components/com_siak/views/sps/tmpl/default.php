<?php

defined('_JEXEC') or exit;
use Joomla\CMS\HTML\HTMLHelper;

JHtml::_('formbehavior.chosen', 'select');
JHtml::_('behavior.core');
JHtml::_('behavior.tooltip');
$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn = $this->escape($this->state->get('list.direction'));

?>
<form
    action="<?php echo JRoute::_('index.php?option=com_siak&view=sps'); ?>"
    method="post" name="adminForm" id="adminForm">

    <div class="j-main-container">
        <legend><?php echo JText::_('COM_SIAK_SPS_LEGEND'); ?>
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
                        <th class="center">
                            <?php echo JText::_('COM_SIAK_MAHASISWA_LABEL'); ?>
                        </th>
                        <th>
                            <?php echo JText::_('COM_SIAK_FIELD_MATAKULIAH_TITLE'); ?>
                        </th>
                        <th>
                            <?php echo JText::_('COM_SIAK_FIELD_NAMA_DOSEN_TITLE'); ?>
                        </th>
                        <th>
                            <?php echo JText::_('COM_SIAK_TANGGAL_DAFTAR_LABEL'); ?>
                        </th>
                        <th>
                            <?php echo JText::_('JGLOBAL_ARCHIVE_OPTIONS'); ?>
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
                            <?php echo $item->npm; ?><br />
                            <div class="small">
                                <?php echo $item->mahasiswa; ?>
                            </div>
                        </td>
                        <td>
                            <?php echo $item->kodeMK; ?><br />
                            <div class="small">
                                <?php echo $item->matakuliah; ?>
                            </div>
                        </td>
                        <td>
                            <?php echo $item->dosen; ?>
                        </td>
                        <td>
                            <?php echo HtmlHelper::date($item->tanggal_daftar, 'd/m/Y H:i:s'); ?>
                        </td>
                        <td>
                            <?php
                                if ($item->dosid == JFactory::getUser()->id) {
                                    echo JHtml::tooltip(
                                        'Isi Nilai',
                                        'Nilai',
                                        'tooltip.png',
                                        'Isi Nilai',
                                        JRoute::_('index.php?option=com_siak&view=remidial&layout=edit&id='.$item->id),
                                        true
                                    );
                                }
                                ?>
                        </td>
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