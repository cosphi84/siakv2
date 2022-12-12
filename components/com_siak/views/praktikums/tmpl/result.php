<?php

defined('_JEXEC') or die;
$layout = \JFactory::getApplication()->input->get('layout', 'default', 'cmd');
?>

<table class="table table-striped">
    <thead>
        <tr>
            <th>
                <?php echo JText::_('COM_SIAK_NO_URUT'); ?>
            </th>
            <?php
                if ('matakuliah' == $layout) {
                    echo '<th>';
                    echo JText::_('COM_SIAK_FIELD_NAMA_TITLE');
                    echo '</th>';
                    echo '<th>';
                    echo JText::_('COM_SIAK_FIELD_NPM_TITLE');
                    echo '</th>';
                    echo '<th>';
                    echo JText::_('COM_SIAK_KELAS_TITLE_LABEL');
                    echo '</th>';
                } else {
                    echo '<th>';
                    echo JText::_('COM_SIAK_KODE_MK_TITLE');
                    echo '</th><th>';
                    echo JText::_('COM_SIAK_FIELD_NAMA_TITLE');
                    echo '</th>';
                }
            ?>
        </tr>
    </thead>
    <tfoot>
        <tr>
            <?php
                'matakuliah' == $layout ? $colspan = '4' : $colspan = '3';
            ?>
            <td colspan="<?php echo $colspan; ?>">
                <?php echo $this->pagination->getListFooter(); ?>
            </td>
        </tr>
    </tfoot>
    <tbody>
        <?php
            foreach ($this->items as $i->{$j}) {
                echo '<tr class="row'.($i % 2).'">';
                echo '<td>'.$this->pagination->getRowOffset($i).'</td>';
                if ('matakuliah' == $layout) {
                    echo '<td>'.$j->mahasiswa.'</td>';
                    echo '<td>'.$j->npm.'</td>';
                    echo '<td>'.$j->kelas.'</td>';
                } else {
                    echo '<td>'.$j->kodeMK.'</td>';
                    echo '<td>'.$j->mk.'</td>';
                }
            }
            ?>

    </tbody>
</table>