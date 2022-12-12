<?php

use Joomla\CMS\Router\Route;

defined('_JEXEC') or exit;

JHtml::_('bootstrap.framework');
JHtml::_('behavior.tooltip');
JHtml::_('formbehavior.chosen', 'select');

?>
<div class="container-flex">
    <legend>
        <?php echo JText::sprintf('COM_SIAK_MYMK_LEGEND', $this->user->name); ?>
    </legend>
    <form method="POST"
        action="<?php echo JRoute::_('index.php?option=com_siak&view=mymk'); ?>"
        id="adminForm" name="adminForm" class="well">
        <?php echo JLayoutHelper::render('joomla.searchtools.default', ['view' => $this]); ?>

        <?php if (count($this->items) < 1) { ?>
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
                        <?php echo JText::_('COM_SIAK_FIELD_MATAKULIAH_TITLE'); ?>
                    </th>
                    <th class="center">
                        <?php echo JText::_('COM_SIAK_SKS_TITLE'); ?>
                    </th>
                    <th>
                        <?php echo JText::_('COM_SIAK_PRODI_TITLE_LABEL'); ?>
                    </th>
                    <th>
                        <?php echo JText::_('COM_SIAK_KELAS_TITLE_LABEL'); ?>
                    </th>
                    <th>
                        <?php echo JText::_('JGLOBAL_ARCHIVE_OPTIONS'); ?>
                    </th>
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <td colspan="8" class="center">
                        <?php echo $this->pagination->getListFooter(); ?>
                    </td>
                </tr>
            </tfoot>
            <tbody>
                <?php $ttlSks = 0;
            foreach ($this->items as $j => $i) {
                ?>
                <tr class="row<?php echo $j % 2; ?>">
                    <td class="center">
                        <?php echo $this->pagination->getRowOffset($j); ?>
                    </td>

                    <td class="nowrap">
                        <?php echo $i->KODEMK; ?>
                        <span class="small break-world">
                            <div class="show-detail">
                                <?php echo $i->MK; ?>
                            </div>
                        </span>
                    </td>

                    <td class="center">
                        <?php echo $i->sks; ?>
                        <?php $ttlSks += $i->sks; ?>
                    </td>
                    <td class="nowrap">
                        <?php echo $i->PRODI_MK; ?>
                        <div class="small">
                            <?php echo $i->JURUSAN_MK; ?>
                        </div>
                    </td>
                    <td>
                        <?php echo $i->KELAS_mhs; ?>
                    </td>
                    <td>
                        <a href="<?php echo Route::_('index.php?option=com_siak&view=inputnilai&layout=edit&dsdm='.$i->id, false); ?>"
                            class="btn btn-default">Input Nilai</a>
                    </td>
                </tr>
                <?php
            } ?>
                <tr>
                    <td colspan="3" style="text-align: right;">
                        <?php echo Jtext::_('COM_SIAK_JUMLAH_SKS'); ?>
                        :
                    </td>
                    <td><?php echo $ttlSks; ?>
                    </td>
                    <td colspan="2">&nbsp;</td>
                </tr>
            </tbody>
        </table>
        <?php } ?>
        <input type="hidden" name="task" value="" />
        <?php echo JHtml::_('form.token'); ?>
    </form>
</div>