<?php

defined('_JEXEC') or exit;

JHtml::_('behavior.multiselect');
JHtml::_('formbehavior.chosen', 'select');
JHtml::_('behavior.tooltip');
$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn = $this->escape($this->state->get('list.direction'));
?>

<form
    action="<?php echo JRoute::_('index.php?option=com_siak&view=sks'); ?>"
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
                        <th class="nowrap center" width="1%">
                            <?php echo JHtml::_('grid.checkall'); ?>
                        </th>
                        <th class="nowrap" width="14%">
                            <?php echo JHtml::_('searchtools.sort', 'COM_SIAK_SK_FIELD_TITLE_LABEL', 'a.title', $listDirn, $listOrder); ?>
                        </th>
                        <th class="nowrap" width=25%">
                            <?php echo JText::_('COM_SIAK_SK_FIELD_ALIAS_LABEL'); ?>
                        </th>
                        <th class="nowrap" width="35%">
                            <?php echo JText::_('COM_SIAK_SK_FIELD_NOTE_LABEL'); ?>
                        </th>
                        <th width="10%">
                            <?php echo JText::_('JGRID_HEADING_ACCESS'); ?>
                        </th>
                        <th class="nowrap center" width="10%">
                            <?php echo JText::_('JGLOBAL_ARCHIVE_OPTIONS'); ?>
                        </th>
                        <th class="nowrap center hidden-phone" width="5%">
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
                        <td class="nowrap">
                            <?php echo $item->title; ?>
                        </td>
                        <td>
                            <?php echo $item->alias; ?>
                        </td>
                        <td>
                            <?php echo $item->note; ?>
                        </td>
                        <td>
                            <?php echo $this->escape($item->access_level); ?>
                        </td>
                        <td class="center">
                            <?php echo JHtml::tooltip('Download SK', 'Download', 'notice-download.png', '', JURI::root().'/media/com_siak/files/sk/'.$item->file, true); ?>

                        </td>
                        <td class=" nowrap center hidden-phone">
                            <?php echo JHtml::_('jgrid.published', $item->published, $i, 'sks.', true, 'cb'); ?>
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