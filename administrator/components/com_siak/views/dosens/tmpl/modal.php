<?php

defined('_JEXEC') or exit('Restricted Access');

JHtml::_('behavior.core');
JHtml::_('script', 'com_siak/modal-handler.js', ['version' => 'auto', 'relative' => true]);

$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn = $this->escape($this->state->get('list.direction'));

$app = JFactory::getApplication();
$function = $app->input->getCmd('function', 'jSelectSemesters');
$onclick = $this->escape($function);
?>
<div class="container-popup">

    <form
        action="<?php echo JRoute::_('index.php?option=com_siak&view=dosens&layout=modal&tmpl=component&function='.$function.'&'.JSession::getFormToken().'=1'); ?>"
        method="post" name="adminForm" id="adminForm" class="form-inline">

        <?php echo JLayoutHelper::render('joomla.searchtools.default', ['view' => $this]); ?>

        <div class="clearfix"></div>

        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th class="nowrap center">
                        #
                    </th>
                    <th class="nowrap">
                        <?php echo JHtml::_('searchtools.sort', 'COM_SIAK_DOSENS_FIELD_NAME_LABEL', 's.title', $listDirn, $listOrder); ?>
                    </th>
                    <th class="nowrap">
                        <?php echo JText::_('COM_SIAK_USER_FIELD_NO_NIDN_LABEL'); ?>
                    </th>
                    <th class="nowrap">
                        <?php echo JText::_('COM_SIAK_USER_FIELD_NIK_LABEL'); ?>
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
                <?php if (!empty($this->items)) { ?>
                <?php foreach ($this->items as $i => $item) { ?>
                <tr>
                    <td><?php echo $this->pagination->getRowOffset($i); ?>
                    </td>
                    <td>

                        <?php $attribs = 'data-function="'.$this->escape($onclick).'"'
                                    .' data-id="'.$item->uid.'"'
                                    .' data-title="'.$this->escape(addslashes($item->dosen)).'"';
                        ?>
                        <a class="select-link" href="javascript:void(0)" <?php echo $attribs; ?>>
                            <?php echo $this->escape($item->dosen); ?>
                        </a>

                    </td>
                    <td class="nowrap">
                        <?php if (empty($item->nidn)) {
                            $item->nidn = 'N/a';
                        }
                            echo $item->nidn; ?>
                    </td>
                    <td class="nowrap">
                        <?php echo $item->nik; ?>
                    </td>

                </tr>

                <?php }  ?>
                <?php }  ?>
            </tbody>
        </table>
        <input type="hidden" name="task" value="" />
        <input type="hidden" name="boxchecked" value="0" />
        <?php echo JHtml::_('form.token'); ?>
    </form>
</div>