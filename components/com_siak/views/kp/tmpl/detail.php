<?php

defined('_JEXEC') or die;

JHtml::_('behavior.formvalidator');
JHtml::_('script', 'com_siak/submitbutton', ['version' => 'auto', 'relative' => true]);
$skip = ['id', 'user_id', 'prodi', 'jurusan', 'kelas', 'state', 'checked_out', 'checked_out_time'];
$path = 'media/com_siak/files/kp/';
?>

<div class="container-fluid">
    <legend><?php echo JText::sprintf('COM_SIAK_KP_DETAIL_LEGEND', ucfirst($this->item->nama)); ?>
    </legend>
    <div class="clearfix"></div>
    <div class="row-fluid well">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Item</th>
                    <th><i>Value</i></th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($this->item)) { ?>
                <tr>
                    <td colspan="2">
                        <div class="alert alert-no-items">
                            <?php echo JText::_('JGLOBAL_NO_MATCHING_RESULTS'); ?>
                        </div>
                    </td>
                </tr>
                <?php
                } else {
                    $a = 0;
                    foreach ($this->item as $title => $val) {
                        if (in_array($title, $skip)) {
                            continue;
                        }
                        if ('file_laporan' == $title) {
                            $link = JURI::root().$path.$val;
                            $val = '<a href="'.$link.'" target="new">'.$val.'</a>';
                        }
                        echo '<tr class="row'.($a % 2).'">';
                        echo '<td>';
                        $text = explode('_', $title);
                        foreach ($text as $t) {
                            echo ucfirst(($t));
                            echo ' ';
                        }
                        echo '</td>';
                        echo '<td>'.$val.'</td>';
                        echo '</tr>';
                        ++$a;
                    }
                }
                ?>
            </tbody>
        </table>
    </div>
</div>