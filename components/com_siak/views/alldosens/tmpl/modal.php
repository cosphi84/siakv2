<?php

defined('_JEXEC') or exit('Restricted Access');

JHtml::_('behavior.core');
JHtml::_('script', 'com_siak/modal-handler.js', ['version' => 'auto', 'relative' => true]);

$app = JFactory::getApplication();
$function = $app->input->getCmd('function', 'jSelectDosens');
$onclick = $this->escape($function);
?>
<div class="container-popup">

    <div class="clearfix"></div>

    <table class="table table-striped table-hover">
        <thead>
            <tr>
                <th class="nowrap">
                    <?php echo JText::_('COM_SIAK_NO_URUT'); ?>
                </th>
                <th class="nowrap">
                    <?php echo JText::_('COM_SIAK_MK_TITLE'); ?>
                </th>
                <th class="nowrap">
                    <?php echo JText::_('JGLOBAL_EMAIL'); ?>
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
                                    .' data-id="'.$item->id.'"'
                                    .' data-title="'.$this->escape(addslashes($item->dosen)).'"';
                        ?>
                    <a class="select-link" href="javascript:void(0)" <?php echo $attribs; ?>>
                        <?php echo $this->escape($item->dosen); ?>
                    </a>

                </td>

                <td class="nowrap">
                    <?php echo $item->email; ?>
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