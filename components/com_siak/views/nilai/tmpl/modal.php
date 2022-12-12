<?php
defined('_JEXEC') or exit;

JHtml::_('behavior.multiselect');
JHtml::_('formbehavior.chosen', 'select');
JHtml::_('script', 'com_siak/modal-handler.js', ['version' => 'auto', 'relative' => true]);
$app = JFactory::getApplication();
$function = $app->input->getCmd('function', 'jSelectNilai');
$onclick = $this->escape($function);
?>

<div class="container-popup">
    <form
        action="<?php echo JRoute::_('index.php?option=com_siak&view=nilai&layout=modal&tmpl=component&function='.$function.'&'.JSession::getFormToken().'=1'); ?>"
        method="post" name="adminForm" id="adminForm" class="form-inline">

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
                        <?php echo JText::_('COM_SIAK_NO_URUT'); ?>
                    </th>
                    <th>
                        <?php echo JText::_('COM_SIAK_MAHASISWA_LABEL'); ?>
                    </th>
                    <th>
                        <?php echo JText::_('COM_SIAK_MATKUL_FIELD_NAMA_LABEL'); ?>
                    </th>
                    <th class="center">
                        <?php echo JText::_('COM_SIAK_NILAI_AKHIR_LABEL'); ?>
                    </th>

                    <th class="center">
                        <?php echo JText::_('COM_SIAK_NILAI_ANGKA_MUTU_LABEL'); ?>
                    </th>
                    <th class="center">
                        <?php echo JText::_('COM_SIAK_NILAI_HURUF_MUTU_LABEL'); ?>
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
                <?php foreach ($this->items as $i => $item) { ?>
                <tr class="row<?php echo $i % 2; ?>">
                    <td class="center">
                        <?php echo $this->pagination->getRowOffset($i); ?>
                    </td>
                    <td>
                        <?php echo $item->npm; ?>
                        <div class="small break-word">
                            <?php echo $item->mahasiswa; ?>
                        </div>
                    </td>
                    <td>
                        <?php $link = 'index.php?option=com_siak&view=nilai&id='.$item->id;
                        $attribs = 'data-function="'.$this->escape($onclick).'"'
                                    .' data-id="'.$item->id.'"'
                                    .' data-title="'.$this->escape(addslashes($item->kodemk)).'"';
                        ?>
                        <a class="select-link" href="javascript:void(0)" <?php echo $attribs; ?>>
                            <?php echo $this->escape($item->kodemk); ?>
                        </a>
                        <div class="small break-word">
                            <?php echo $item->mk; ?>
                        </div>
                    </td>
                    <td>
                        <?php echo $item->dosen; ?>

                    </td>
                    <td class="center">
                        <?php echo $item->nilai_akhir; ?>
                    </td>
                    <td class="center">
                        <?php echo $item->nilai_mutu; ?>
                    </td>

                    <td class="center">
                        <?php echo $item->nilai_angka; ?>
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