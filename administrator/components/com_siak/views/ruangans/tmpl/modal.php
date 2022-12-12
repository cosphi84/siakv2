<?php

defined('_JEXEC') or exit;

JHtml::_('behavior.multiselect');
JHtml::_('formbehavior.chosen', 'select');
JHtml::_('script', 'com_siak/modal-handler.js', ['version' => 'auto', 'relative' => true]);

$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn = $this->escape($this->state->get('list.direction'));
$app = JFactory::getApplication();
$function = $app->input->getCmd('function', 'jSelectRuangan');
$onclick = $this->escape($function);
?>

<form
    action="<?php echo JRoute::_('index.php?option=com_siak&view=ruangans&layout=modal&tmpl=component&function='.$function.'&'.JSession::getFormToken().'=1'); ?>"
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
                            <?php echo JText::_('COM_SIAK_NO_URUT'); ?>
                        </th>
                        <th class="nowrap">
                            <?php echo JText::_('COM_SIAK_RUANGAN_FIELD_TITLE_LABEL'); ?>
                        </th>
                        <th class="nowrap">
                            <?php echo JText::_('COM_SIAK_FIELD_ALIAS_LABEL'); ?>
                        </th>
                        <th class="nowrap">
                            <?php echo JText::_('JFIELD_NOTE_DESC'); ?>
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
                            <?php echo $this->pagination->getRowOffset($i); ?>
                        </td>
                        <td>
                            <?php $link = 'index.php?option=com_siak&view=ruangan&id='.$item->id;
                        $attribs = 'data-function="'.$this->escape($onclick).'"'
                                    .' data-id="'.$item->id.'"'
                                    .' data-title="'.$this->escape(addslashes($item->title)).'"';
                        ?>
                            <a class="select-link" href="javascript:void(0)" <?php echo $attribs; ?>>
                                <?php echo $this->escape($item->title); ?>
                            </a>

                        </td>

                        <td class="nowrap">
                            <?php echo $item->alias; ?>
                        </td>
                        <td class="nowrap">
                            <?php echo $item->note; ?>
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