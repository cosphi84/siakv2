<?php

defined('_JEXEC') or exit;
JHTML::_('behavior.tooltip');
JHtml::_('formbehavior.chosen', 'select');
JHtml::_('behavior.core');
JHtml::stylesheet(JUri::base().'media/com_siak/css/siak.css');
?>
<form
    action="<?php echo JRoute::_('index.php?option=com_siak&view=krss&layout=wali'); ?>"
    method="post" name="adminForm" id="adminForm">
    <div class="j-main-container">
        <legend><?php echo JText::_('COM_SIAK_KRSS_WALI_LEGEND'); ?>
        </legend>
        <div class="clearfix"></div>
        <div class="col-sm well">
            <div class="span12">
                <?php echo JLayoutHelper::render('joomla.searchtools.default', ['view' => $this, 'searchButton' => false]); ?>
            </div>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th class="center">
                            <?php echo JText::_('COM_SIAK_NO_URUT'); ?>
                        </th>
                        <th>
                            <?php echo JText::_('COM_SIAK_MAHASISWA_LABEL'); ?>
                        </th>
                        <th>
                            <?php echo JText::_('COM_SIAK_FIELD_SEMESTER_LABEL'); ?>
                        </th>
                        <th>
                            <?php echo JText::_('COM_SIAK_JUMLAH_SKS'); ?>
                        </th>
                        <th>
                            <?php echo JText::_('COM_SIAK_DOSEN_WALI_TITLE'); ?>
                        </th>
                        <th>
                            <?php echo JText::_('COM_SIAK_KONF_FIELD_STATUS_LABEL'); ?>
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
                    <?php
                    if (0 == count($this->items)) {
                        echo '<tr>';
                        echo '<td colspan="7" class="center alert alert-no-items">';
                        echo JText::_('JGLOBAL_NO_MATCHING_RESULTS');
                        echo '</td>';
                        echo '</tr>';
                    } else {
                        foreach ($this->items as $k => $val) {
                            echo '<tr class="row'.($k % 2).'">';
                            echo '<td class="center">'.$this->pagination->getRowOffset($k).'</td>';
                            echo '<td>'.$val->npm.'<br>';
                            echo '<span class="small break-word">';
                            echo $val->mahasiswa.'</span></td>';
                            echo '<td>'.$val->semester.'</td>';
                            echo '<td>';
                            echo '<a id="link-items-krs" href="javascript:void(0)" '.
                                    'onclick="Joomla.popupWindow(\''.JRoute::_('index.php?option=com_siak&view=krs&layout=mks&tmpl=component&id='.$val->id).'\', \'detailMK\', \'800\', \'500\', \'true\')">';
                            echo $val->ttl_sks.'</a>';
                            echo '</td>';
                            echo '<td>'.$val->dosenwali.'</td>';

                            switch ($val->statusKRS) {
                                    case '-1':
                                        echo '<td class="draft">';
                                        echo JHtml::tooltip(
                                            JText::_('COM_SIAK_KRS_DRAFT_NOTE'),
                                            JText::_('COM_SIAK_TOOLTIP_INFORMASI'),
                                            '',
                                            'Draft',
                                            ''
                                        );
                                        //echo 'Draft';

                                        break;

                                    case '0':
                                        echo '<td class="final">';
                                        echo JHtml::tooltip(
                                            JText::_('COM_SIAK_KRS_FINAL_NOTE'),
                                            JText::_('COM_SIAK_TOOLTIP_INFORMASI'),
                                            '',
                                            'Final',
                                            ''
                                        );

                                        break;

                                    case '1':
                                        echo '<td class="setuju">';
                                        echo JHtml::tooltip(
                                            JText::_('COM_SIAK_KRS_SETUJU_NOTE'),
                                            JText::_('COM_SIAK_TOOLTIP_INFORMASI'),
                                            '',
                                            'Disetujui',
                                            ''
                                        );

                                        break;

                                    case '-2':
                                        echo '<td class="merah">';
                                        echo JHtml::tooltip(
                                            JText::sprintf('COM_SIAK_KRS_REJECT_NOTE', $this->escape($val->confirm_note)),
                                            JText::_('COM_SIAK_TOOLTIP_INFORMASI'),
                                            '',
                                            'Revisi',
                                            ''
                                        );

                                        break;

                                    default:
                                    echo '<td class="setuju">Done</td>';
                                }
                            echo '</td>';
                            echo '<td>';
                            if ($val->statusKRS <= 0) {
                                echo JHTML::tooltip(
                                    JText::_('COM_SIAK_TOOLTIP_UBAH_ITEM'),
                                    'Edit',
                                    'edit.png',
                                    '',
                                    JRoute::_('index.php?option=com_siak&view=krs&layout=mk&id='.$val->id)
                                );
                            }
                            echo ' ';
                            if ($val->statusKRS > 0) {
                                echo JHTML::tooltip(
                                    JText::_('COM_SIAK_TOOLTIP_DOWNLOAD_ITEM'),
                                    'Download PDF',
                                    'pdf_button.png',
                                    '',
                                    JRoute::_('index.php?option=com_siak&view=krs&format=pdf&id='.$val->id)
                                );
                            }
                            echo '</td>';
                        }
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
    <input type="hidden" name="task" value="" />
    <input type="hidden" name="boxchecked" value="0" />
    <?php echo JHtml::_('form.token'); ?>
    <form>