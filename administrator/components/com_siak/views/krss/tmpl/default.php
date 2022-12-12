<?php

defined('_JEXEC') or exit;
use Joomla\CMS\HTML\HTMLHelper;

JHtml::_('behavior.multiselect');
JHtml::_('formbehavior.chosen', 'select');
$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn = $this->escape($this->state->get('list.direction'));

?>
<form method="POST" action="index.php?option=com_siak&view=krss" name="adminForm" id="adminForm">
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
                            <?php echo JText::_('COM_SIAK_MAHASISWA_FIELD_TITLE_LABEL'); ?>
                        </th>
                        <th>
                            <?php echo JText::_('COM_SIAK_PRODI_FIELD_TITLE_LABEL'); ?>
                        </th>
                        <th>
                            <?php echo JText::_('COM_SIAK_KRS_CREATED_DATE'); ?>
                        </th>
                        <th>
                            <?php echo JText::_('COM_SIAK_SEMESTER_FIELD_TITLE_LABEL'); ?>
                        </th>
                        <th>
                            <?php echo JText::_('COM_SIAK_WALIS_FIELD_NAMA_LABEL'); ?>
                        </th>
                        <th>
                            <?php echo JText::_('COM_SIAK_FIELD_TAHUN_AJARAN_LABEL'); ?>
                        </th>
                        <th>
                            <?php echo JText::_('COM_SIAK_KRS_STATUS'); ?>
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
                    <?php foreach ($this->items as $a => $b) { ?>
                    <tr class="row<?php echo $a % 2; ?>">
                        <td class="center">
                            <?php echo JHtml::_('grid.id', $a, $b->id); ?>
                        </td>
                        <td>
                            <?php echo $b->npm; ?>
                            <span class="small break-word"><br>
                                <?php echo $b->mahasiswa; ?>
                            </span>
                        </td>
                        <td>
                            <?php echo $b->prodi; ?><br>
                            <span class="small break-word">
                                <?php echo $b->jurusan; ?>
                            </span>
                        </td>
                        <td>
                            <?php echo HTMLHelper::date($b->created_time, 'd/m/Y H:i:s'); ?>
                        </td>
                        <td>
                            <?php echo $b->semester; ?>
                        </td>
                        <td>
                            <?php echo $b->dosenwali; ?>
                        </td>
                        <td>
                            <?php echo $b->tahun_ajaran; ?>
                        </td>
                        <td>
                            <?php echo Siak::statusKRS($b->statusKRS); ?>
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