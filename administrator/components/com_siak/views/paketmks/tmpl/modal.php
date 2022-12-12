<?php

defined('_JEXEC') or exit('Fuck Off here!');

JHtml::_('behavior.core');
JHtml::_('script', 'com_siak/modal-handler.js', ['version' => 'auto', 'relative' => true]);

$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn = $this->escape($this->state->get('list.direction'));

$app = JFactory::getApplication();
$function = $app->input->getCmd('function', 'jSelectMatakuliah');
$onclick = $this->escape($function);

?>
<div class="container-popup">

    <form 
        method  ="POST"
        action  = <?php echo JRoute::_('index.php?option=com_siak&view=paketmks&layout=modal&tmpl=component&function='.$function.'&'.JSession::getFormToken().'=1'); ?>
        class   = "form-inline"
        name    = "adminForm"
        id      = "adminForm"
    >

        <?php echo JLayoutHelper::render('joomla.searchtools.default', ['view' => $this]); ?>
        <div class="clearfix"></div>
        
        <?php if (empty($this->items)) { ?>


        <?php } else { ?>
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th class="nowrap center">
                            <?php echo JText::_('COM_SIAK_NO_URUT'); ?>
                        </th>
                        <th class="nowrap">
                            <?php echo JText::_('COM_SIAK_MATKUL_FIELD_NAMA_LABEL'); ?>
                        </th>
                        <th class="nowrap center">
                            <?php echo JText::_('COM_SIAK_MATKUL_FIELD_SKS_LABEL'); ?>
                        </th>
                        <th class="nowrap">
                            <?php echo JText::_('COM_SIAK_MATKUL_FIELD_JENIS_MK_LABEL'); ?>
                        </th>
                        <th class="nowrap">
                            <?php echo JText::_('COM_SIAK_SEMESTER_FIELD_TITLE_LABEL'); ?>
                        </th>
                        <th class="nowrap">
                            <?php echo JText::_('COM_SIAK_MATKUL_FIELD_PEMILIK_LABEL'); ?>
                        </th>
                        <th class="nowrap center">
                            <?php echo JText::_('JGLOBAL_FIELD_ID_LABEL'); ?>
                        </th>
                </thead>
                <tfoot>
                    <tr>
                        <td colspan="7">
                            <?php echo $this->pagination->getListFooter(); ?>
                        </td>
                    </tr>
                </tfoot>
                <tbody>
                    <?php foreach ($this->items as $v => $item) { ?>
                        <tr>
                            <td width="1%" class="center">
                                <?php echo $this->pagination->getRowOffset($i); ?>
                            </td>
                            <td class="nowrap">
                                <?php $link = 'index.php?option=com_siak&view=paketmks&id='.$item->id;
                                $attribs = 'data-function="'.$this->escape($onclick).'"'
                                        .' data-id="'.$item->id.'"'
                                        .' data-title="'.$this->escape(addslashes($item->kodeMK)).'"';
                                ?>
                                <a class="select-link" href="javascript:void(0)" <?php echo $attribs; ?>>
                                    <?php echo $this->escape($item->kodeMK); ?>
                                </a><br />
                                <span class="small break-word">
                                    <?php echo $this->escape($item->namaMK); ?>
                                <div class="small">
                        </td>
                        <td class="nowrap center">
                            <?php echo $item->sks; ?>
                        </td>
                        <td class="nowrap">
                            <?php echo $item->jenisMK; ?>
                        </td>
                        <td class="nowrap">
                            <?php echo $item->semester; ?>

                        </td>
                        <td class="nowrap">
                            <?php echo $item->prodi.' : '.$item->jurusan; ?>
                        </td>
                        <td class="center">
                            <?php echo JText::_($item->id); ?>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        <?php } ?>
        <input type="hidden" name="task" value="" />
        <input type="hidden" name="boxchecked" value="0" />
        <?php echo JHtml::_('form.token'); ?>
    </form>
</div>
