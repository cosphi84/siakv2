<?php

defined('_JEXEC') or exit;

JHtml::_('behavior.keepalive');

?>

<div class="container-fluid">
    <legend>
        <?php echo JText::_('COM_SIAK_DOSEN_WALI_PAGE_LEGEND'); ?>
    </legend>
    <div class="clearfix"></div>

    <?php if (empty($this->items)) { ?>
        <div class="alert alert-no-items">
            <?php echo JText::_('JGLOBAL_NO_MATCHING_RESULTS'); ?>
        </div>
    <?php } else { ?>
            <table class="table table-striped" id="classeslist">
                <thead>
                    <tr>
                        <th class="center">
                            <?php echo JText::_('COM_SIAK_NO_URUT'); ?>
                        </th>
                        <th>
                            <?php echo JText::_('COM_SIAK_PRODI_TITLE_LABEL'); ?>
                        </th>
                        <th>
                            <?php echo JText::_('COM_SIAK_KELAS_TITLE_LABEL'); ?>
                        </th>
                        <th>
                            <?php echo JText::_('COM_SIAK_WALIS_FIELD_ANGKATAN_LABEL'); ?>
                        </th>
                        <th>
                            <?php echo JText::_('COM_SIAK_DOSEN_WALI_TITLE'); ?>
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
                <tbody>
                    <?php foreach ($this->items as $i => $j) { ?>
                        <tr>
                            <td class="center">
                                <?php echo $this->pagination->getRowOffset($i); ?>
                            </td>
                            <td>
                                <?php echo $j->prodi; ?>
                            </td>
                            <td>
                                <?php echo $j->kelas; ?>
                            </td>
                            <td>
                                <?php echo $j->angkatan; ?>
                            </td>
                            <td>
                                <?php echo $j->dosen; ?>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
    <?php } ?>
</div>