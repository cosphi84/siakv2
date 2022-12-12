<?php

defined('_JEXEC') or exit;

JHtml::_('bootstrap.framework');
JHtml::_('behavior.tooltip');
?>
<div class="container-fluid well">
    <legend>         
        <?php echo JText::_('COM_SIAK_SK_PAGE_HEADING'); ?>
    </legend>
    <div class="clearfix"></div>
    
    <form
        action="<?php echo JRoute::_('index.php?option=com_siak&view=sks'); ?>"
        method="post" name="adminForm" id="adminForm" >        
        
        <div class="span12">
            <?php echo JLayoutHelper::render('joomla.searchtools.default', ['view' => $this]); ?>
        </div>
        <div class="span12">
            <?php if (empty($this->items)) { ?>
            <div class="alert alert-no-items">
                <?php echo JText::_('JGLOBAL_NO_MATCHING_RESULTS'); ?>
            </div>
            <?php } else { ?>
            <table class="table table-striped" id="classesList">
                <thead>
                    <tr>
                        <th class="nowrap center" width="1%">
                            #
                        </th>
                        <th class="nowrap" width="14%">
                            <?php echo JText::_('COM_SIAK_SK_FIELD_TITLE_LABEL'); ?>
                        </th>
                        <th class="nowrap" width=25%">
                            <?php echo JText::_('COM_SIAK_SK_FIELD_ALIAS_LABEL'); ?>
                        </th>
                        <th class="nowrap hidden-phone" width="35%">
                            <?php echo JText::_('COM_SIAK_SK_FIELD_NOTE_LABEL'); ?>
                        </th>

                        <th class="nowrap center" width="10%">
                            <?php echo JText::_('JGLOBAL_ARCHIVE_OPTIONS'); ?>
                        </th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <td colspan="5">
                            <?php echo $this->pagination->getListFooter(); ?>
                        </td>
                    </tr>
                </tfoot>
                <tbody>
                    <?php foreach ($this->items as $i => $item) { ?>
                    <tr class="row<?php echo $i % 2; ?>">
                        <td class="center">
                            <?php echo $this->pagination->getRowOffset($i); ?>
                        </td>
                        <td>
                            <?php echo $item->title; ?>
                        </td>
                        <td>
                            <?php echo $item->alias; ?>
                        </td>
                        <td class="hidden-phone">
                            <?php echo $item->note; ?>
                        </td>

                        <td class="center">
                            <?php echo JHtml::tooltip('Download SK', 'Download', 'notice-download.png', '', JURI::root().'media/com_siak/files/sk/'.$item->file, true); ?>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
            <?php } ?>
            <input type="hidden" name="task" value="" />
            <?php echo JHtml::_('form.token'); ?>
        </div>
    </form>
</div>