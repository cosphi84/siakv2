<?php

defined('_JEXEC') or exit;
$status = [
    '0' => 'Tidak Aktif',
    '1' => 'Aktif',
    '2' => 'Lulus',
    '-1' => 'Cuti',
    '-2' => 'Pindah',
    '-3' => 'Drop Out',
];

$konfirm = ['0' => 'Belum Dikonfirmasi', '1' => 'Sudah Dikonfirmasi'];

?>

<div class="container-fluid">
    <legend><?php echo JText::_('COM_SIAK_KONFIRMASUKU_LEGEND'); ?>
    </legend>

    <div class="clearfix"></div>
    <div class="row well">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th class="center">
                        <?php echo JText::_('COM_SIAK_NO_URUT'); ?>
                    </th>
                    <th class="nowrap hidden-phone">
                        <?php echo JText::_('COM_SIAK_KONFIRMASIKU_TIME'); ?>
                    </th>
                    <th>
                        <?php echo JText::_('COM_SIAK_PRODI_TITLE_LABEL'); ?>
                    </th>
                    <th>
                        <?php echo JText::_('COM_SIAK_FIELD_SEMESTER_LABEL'); ?>
                    </th>
                    <th>
                        <?php echo JText::_('COM_SIAK_TA_TITLE_LABEL'); ?>
                    </th>
                    <th>
                        <?php echo JText::_('COM_SIAK_KONF_FIELD_STATUSKU_LABEL'); ?>
                    </th>
                    <th>
                        <?php echo JText::_('COM_SIAK_KONF_FIELD_STATUS_LABEL'); ?>
                    </th>
                </tr>
            </thead>
            <tbody>
                <?php
                    foreach ($this->items as $i => $item) {
                        ?>
                <tr>
                    <td class="center">
                        <?php echo $this->pagination->getRowOffset($i); ?>
                    </td>
                    <td class="hidden-phone">
                        <?php echo $item->create_date; ?>
                    </td>
                    <td>
                        <?php echo $item->prodi; ?><br />
                        <span class="small break-word">
                            <?php echo $item->jurusan; ?>
                        </span>
                    </td>
                    <td>
                        <?php if (!(bool) $item->confirm) { ?>
                        <a
                            href="<?php echo JRoute::_('index.php?option=com_siak&view=konfirmasi&layout=edit&id='.$item->id, false); ?>">
                            <span class="icon-pencil-2"> </span>
                        </a>
                        <?php echo $item->semester; ?>
                        <?php } else { ?>
                        <?php echo $item->semester; ?>
                        <?php } ?>
                    </td>
                    <td>
                        <?php echo $item->ta; ?>
                    </td>
                    <td>
                        <?php echo $status[$item->status]; ?>

                    </td>

                    <?php (bool) $item->confirm ? $class = 'hijau' : $class = 'merah'; ?>

                    <td class="<?php echo $class; ?>">
                        <?php if ((bool) $item->confirm) { ?>
                        <a
                            href="<?php echo JRoute::_('index.php?option=com_siak&view=konfirmasi&format=pdf&id='.$item->id, false); ?>">
                            <span class="icon-download"> </span>
                        </a>
                        <?php echo $konfirm[$item->confirm]; ?>
                        <?php } else {
                            echo $konfirm[$item->confirm];
                        } ?>
                    </td>
                </tr>
                <?php
                    }
                ?>
            </tbody>
        </table>
    </div>
</div>