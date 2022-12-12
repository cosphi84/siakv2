<?php

defined('_JEXEC') or exit;

JHtml::_('behavior.multiselect');
JHtml::_('formbehavior.chosen', 'select');

$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn = $this->escape($this->state->get('list.direction'));

?>
<form method="POST" action="index.php?option=com_siak&view=bobots" name="adminForm" id="adminForm">

    <div id="j-sidebar-container" class="span2">
        <?php echo $this->sidebar; ?>
    </div>

    <div id="j-main-container" class="span10">


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
                        <?php echo JHtml::_('searchtools.sort', 'COM_SIAK_BOBOT_FIELD_TITLE_LABEL', 'b.name', $listDirn, $listOrder); ?>
                    </th>
                    <th>
                        <?php echo JHtml::_('searchtools.sort', 'COM_SIAK_BOBOT_FIELD_BOBOT_LABEL', 'b.bobot', $listDirn, $listOrder); ?>
                    </th>
                    <th class="center">
                        <?php echo JText::_('JPUBLISHED'); ?>
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <td colspan="4" class="center">
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
                        <?php echo $b->title; ?>
                        <span class="small break-word"><br />
                            <?php echo JText::_($this->escape($b->alias)); ?>
                        </span>
                    </td>
                    <td>
                        <?php echo $b->bobot; ?>
                    </td>

                    <td class="center">
                        <?php echo JHtml::_('jgrid.published', $b->state, $a, 'bobots.', true, 'cb'); ?>
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