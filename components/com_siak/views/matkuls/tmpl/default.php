<?php

defined('_JEXEC') or exit;

?>

<div class="container-fluid">
    <legend><?php echo JText::_('COM_SIAK_PAKET_MK_LEGEND'); ?>
    </legend>
    <div class="clearfix"></div>
    <form
        action="<?php echo JRoute::_('index.php?option=com_siak&view=paketmk'); ?>"
        method="post" name="adminForm" id="adminForm">
        <div class="row well">
            <div class="col">
                <?php echo JLayoutHelper::render('joomla.searchtools.default', ['view' => $this]); ?>
            </div>
            <div class="col">
                <?php if (empty($this->items)) { ?>
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
                                <?php echo JText::_('Program Studi'); ?>
                            </th>
                            <th>
                                <?php echo JText::_('COM_SIAK_FIELD_SEMESTER_LABEL'); ?>
                            </th>

                            <th class="hidden-phone">
                                <?php echo JText::_('COM_SIAK_KODE_MK_TITLE'); ?>
                            </th>

                            <th class="hidden-phone">
                                <?php echo JText::_('COM_SIAK_SKS_TITLE'); ?>
                            </th>
                            <th>
                                <?php echo JText::_('COOM_SIAK_JENIS_MK_TITLE'); ?>
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
                        <?php
                            foreach ($this->items as $i => $item) {
                                ?>
                        <tr class="row<?php echo $i % 2; ?>">
                            <td class="center">
                                <?php echo $this->pagination->getRowOffset($i); ?>
                            </td>
                            <td class="nowrap">
                                <?php echo $item->prodi; ?></br>
                                <div class="small">
                                    <?php echo $item->jurusan; ?>
                                </div>
                            </td>
                            <td>
                                <?php echo $item->semester; ?>
                            </td>

                            <td class="nowrap">
                                <?php echo $item->kode; ?>
                                <br />
                                <div class="small">
                                    <?php echo $item->mk; ?>
                                </div>
                            </td>

                            <td class="hidden-phone">
                                <?php echo $item->sks; ?>
                            </td>
                            <td>
                                <?php echo $item->jenis; ?>
                            </td>
                        </tr>
                        <?php
                            }
                        ?>

                    </tbody>
                </table>
                <?php } ?>
            </div>
            <input type="hidden" name="task" value="" />
            <?php echo JHtml::_('form.token'); ?>
        </div>
    </form>
</div>