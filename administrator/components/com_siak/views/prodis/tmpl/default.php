<?php

defined('_JEXEC') or exit;

JHtml::_('behavior.multiselect');
JHtml::_('formbehavior.chosen', 'select');
$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn = $this->escape($this->state->get('list.direction'));
?>

<form
    action="<?php echo JRoute::_('index.php?option=com_siak&view=prodis'); ?>"
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
                            <?php echo JHtml::_('searchtools.sort', 'COM_SIAK_PRODI_FIELD_TITLE_LABEL', 'p.title', $listDirn, $listOrder); ?>
                        </th>
                        <th class="nowrap">
                            <?php echo JText::_('COM_SIAK_FIELD_ALIAS_LABEL'); ?>
                        </th>
                        <th class="nowrap">
                            <?php echo JHtml::_('searchtools.sort', 'COM_SIAK_PRODI_FIELD_STRATA_LABEL', 'p.strata', $listDirn, $listOrder); ?>
                        </th>
                        <th class="nowrap">
                            <?php echo JHtml::_('searchtools.sort', 'COM_SIAK_PRODI_FIELD_GELAR_LABEL', 'p.gelar', $listDirn, $listOrder); ?>
                        </th>
                        <th class="nowrap">
                            <?php echo JHtml::_('searchtools.sort', 'COM_SIAK_PRODI_FIELD_KAPRODI_LABEL', 'u.name', $listDirn, $listOrder); ?>
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
                            <?php echo $item->alias; ?>
                        </td>
                        <td class="nowrap">
                            <?php echo $item->strata; ?>
                        </td>
                        <td class="nowrap">
                            <?php echo $item->gelar; ?>
                        </td>
                        <td class="nowrap">
                            <?php echo $item->kaprodi; ?>
                        </td>
                        <td class="nowrap center hidden-phone">
                            <?php echo JHtml::_('jgrid.published', $item->published, $i, 'prodis.', true, 'cb'); ?>
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