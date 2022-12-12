<?php

defined('_JEXEC') or exit;
JHtml::_('formbehavior.chosen', 'select');
JHTML::_('behavior.tooltip');
$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn = $this->escape($this->state->get('list.direction'));
$user = JFactory::getUser();
$usrGrp = $user->get('groups');
$params = JComponentHelper::getParams('com_siak');
$grpMhs = $params->get('grpMahasiswa');
$grpKaprodi = $params->get('grpKaprodi');

?>
<form
    action="<?php echo JRoute::_('index.php?option=com_siak&view=kps'); ?>"
    method="post" name="adminForm" id="adminForm">


    <div class="container-fluid">
        <legend><?php echo JText::_('COM_SIAK_KPS_LEGEND'); ?>
        </legend>
        <div class=clearfix"></div>
        <div class="row well">
            <div class="span12">
                <?php echo JLayoutHelper::render('joomla.searchtools.default', ['view' => $this, 'searchButton' => false]); ?>
            </div>

            <div class="span12">
                <div class="span11">
                    <?php if (empty($this->items)) { ?>
                    <div class="alert alert-no-items">
                        <?php echo JText::_('JGLOBAL_NO_MATCHING_RESULTS'); ?>
                    </div>
                    <?php } else { ?>

                    <table class="table table-striped" id="classesList">
                        <thead>
                            <tr>
                                <th>
                                    <?php echo JText::_('COM_SIAK_NO_URUT'); ?>
                                </th>
                                <th>
                                    <?php echo JText::_('COM_SIAK_FIELD_NAMA_TITLE'); ?>
                                </th>
                                <th classs="hidden-phone">
                                    <?php echo JText::_('COM_SIAK_PRODI_TITLE_LABEL'); ?>
                                </th>
                                <th classs="hidden-phone">
                                    <?php echo JText::_('COM_SIAK_KELAS_TITLE_LABEL'); ?>
                                </th>
                                <th>
                                    <?php echo JText::_('COM_SIAK_KP_FIELD_INSTANSI_LABEL'); ?>
                                </th>
                                <th>
                                    <?php echo Jtext::_('COM_SIAK_DOSBING_FIELD_LABEL'); ?>
                                </th>
                                <th>
                                    <?php echo JText::_('JGLOBAL_ARCHIVE_OPTIONS'); ?>
                                </th>
                            </tr>

                        </thead>
                        <tfoot>
                            <tr>
                                <td colspan="7">
                                    <?php echo $this->pagination->getListFooter(); ?>
                                </td>
                            </tr>
                        </tfoot>
                        <tbody>
                            <?php foreach ($this->items as $i => $item) { ?>
                            <tr class="row<?php echo $i % 2; ?>">
                                <td>
                                    <?php echo $this->pagination->getRowOffset($i); ?>
                                </td>
                                <td>
                                    <?php echo $item->npm; ?>
                                    <br>
                                    <div class="small break-word">
                                        <?php echo $item->mahasiswa; ?>
                                    </div>
                                </td>
                                <td class="hidden-phone">
                                    <?php echo $item->prodi; ?>
                                    <br />
                                    <div class="small">
                                        <?php echo $item->jurusan; ?>
                                    </div>
                                </td>
                                <td class="hidden-phone">
                                    <?php echo $item->kelas; ?>
                                </td>
                                <td>
                                    <?php echo $item->instansi; ?><br>
                                    <div class="small">
                                        <?php echo $item->alamat.', '.$item->kota; ?>
                                    </div>
                                </td>
                                <td>
                                    <?php echo $item->dosbing; ?>
                                </td>
                                <td class="nowrap">
                                    <?php
                                        if ($item->user_id == JFactory::getUser()->id) {
                                            echo JHTML::tooltip(
                                                JText::_('COM_SIAK_TOOLTIP_UBAH_ITEM'),
                                                'Edit Item',
                                                'edit.png',
                                                '',
                                                JRoute::_('index.php?option=com_siak&view=kp&layout=laporan&id='.$item->id)
                                            );
                                        } else {
                                            if (in_array($grpKaprodi, $usrGrp)) {
                                                echo JHTML::tooltip(
                                                    JText::_('COM_SIAK_TOOLTIP_UBAH_ITEM'),
                                                    'Edit Item',
                                                    'edit.png',
                                                    '',
                                                    JRoute::_('index.php?option=com_siak&view=kp&layout=kaprodi&id='.$item->id)
                                                );
                                            }
                                            echo ' ';
                                            echo JHTML::tooltip(
                                                JText::_('COM_SIAK_TOOLTIP_LIHAT_ITEM'),
                                                'Lihat Item',
                                                'tooltip.png',
                                                '',
                                                JRoute::_('index.php?option=com_siak&view=kp&layout=detail&id='.$item->id)
                                            );
                                        }
                                        ?>
                                </td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    <input type="hidden" name="task" value="" />
    <?php echo JHtml::_('form.token'); ?>
</form>