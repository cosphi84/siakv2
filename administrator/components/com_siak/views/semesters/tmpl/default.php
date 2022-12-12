<?php

defined('_JEXEC') or exit;

JHtml::_('behavior.multiselect');
JHtml::_('formbehavior.chosen', 'select');
JHTML::_('behavior.tooltip');
JHtml::_('behavior.core');
JHtml::_('script', 'com_siak/submitbutton.js', ['version' => 'auto', 'relative' => true]);
JHtml::_('script', 'com_siak/details-listener.js', ['version' => 'auto', 'relative' => true]);
JFactory::getDocument()->addStyleSheet(JURI::root().'media/com_siak/css/siak.css');
JHtml::_('script', 'com_siak/siak.js', ['version' => 'auto', 'relative' => true]);

$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn = $this->escape($this->state->get('list.direction'));
?>

<form
    action="<?php echo JRoute::_('index.php?option=com_siak&view=semesters'); ?>"
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
                            <?php echo JHtml::_('searchtools.sort', 'COM_SIAK_SEMESTER_FIELD_TITLE_LABEL', 's.title', $listDirn, $listOrder); ?>
                        </th>
                        <th class="nowrap">
                            <?php echo JText::_('COM_SIAK_SEMESTER_FIELD_TOTAL_SKS_LABEL'); ?>
                        </th>
                        <th class="nowrap">
                            <?php echo JText::_('COM_SIAK_SEMESTER_FIELD_UANG_SKS_LABEL'); ?>
                        </th>
                        <th class="nowrap">
                            <?php echo JText::_('COM_SIAK_SEMESTER_FIELD_UANG_SPP_LABEL'); ?>
                        </th>
                        <th class="nowrap">
                            <?php echo JText::_('COM_SIAK_PRODI_FIELD_TITLE_LABEL'); ?>
                        </th>
                        <th class="nowrap">
                            <?php echo JText::_('COM_SIAK_KELAS_FIELD_TITLE_LABEL'); ?>
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
                        <td width="1%" class="center">
                            <?php echo JHtml::_('grid.id', $i, $item->id); ?>
                        </td>
                        <td class="nowrap">
                            <?php echo $item->title; ?>
                        </td>
                        <td class="nowrap">
                            <?php $url = 'index.php?option=com_siak&view=semesters&layout=paketmk&tmpl=component&id='.$item->id; ?>
                            <a href="javascript:void(0);"
                                onclick="Joomla.popupWindow('<?php echo $url; ?>', 'paketMK', '750', '400', 'false' );">
                                <?php echo $this->escape($item->totalSKS); ?>
                            </a>

                        </td>
                        <td class="rupiah nowrap">
                            <?php echo $item->uangSKS; ?>
                        </td>
                        <td class="rupiah nowrap">
                            <?php echo $item->uangSPP; ?>
                        </td>
                        <td class="nowrap">
                            <div class="small">
                                <?php echo $item->prodi; ?><br>
                                <div class="break-word">
                                    <?php echo $item->jurusan; ?>
                                </div>
                            </div>
                        </td>
                        <td class="nowrap">
                            <?php empty($item->kelas) ? $item->kelas = 'Semua Kelas' : $item->kelas;
                                echo $item->kelas;
                            ?>
                        </td>
                        <td class="nowrap center hidden-phone">
                            <?php echo JHtml::_('jgrid.published', $item->published, $i, 'semesters.', true, 'cb'); ?>
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