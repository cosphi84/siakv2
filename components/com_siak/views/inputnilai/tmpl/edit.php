<?php

defined('_JEXEC') or exit;

JHtml::_('formbehavior.chosen', 'select');
JHtml::_('behavior.formvalidator');
JHtml::_('behavior.tooltip');
JHtml::_('script', 'com_siak/submitbutton.js', ['version' => 'auto', 'relative' => true]);
JHtml::_('script', 'com_siak/nilai.js', ['version' => 'auto', 'relative' => true]);

$document = JFactory::getDocument();
$document->addScriptOptions('bobotNilai', $this->bobotNilai);

?>

<div class="container-fluid">
    <legend>
        <?php echo JText::_('COM_SIAK_FORM_INPUT_NILAI_LEGEND'); ?>
    </legend>
    <div class="clearfix"></div>

    <form
        action="<?php echo JRoute::_('index.php?option=com_siak&view=inputnilai', false); ?>"
        method="post" name="adminForm" id="adminForm" class="form-validate form-horizontal well">

        <div class=" form-horizontal">
            <fieldset class="adminform">

                <div class=" row-fluid">
                    <?php echo $this->form->renderFieldSet('frmInputnilai'); ?>
                    <?php
                    if ($this->totalRec == 0) {
                        echo '<div class="alert alert-no-items">';
                        echo JText::_('JGLOBAL_NO_MATCHING_RESULTS');
                        echo '</div>';
                    } else {
                        ?>
                    <table class="table table-striped table-bordered" id="table_nilai">
                        <thead>
                            <tr>
                                <th width="15%" rowspan="2" style="vertical-align : middle;text-align:center;">
                                    <?php echo JText::_('COM_SIAK_MAHASISWA_LABEL'); ?>
                                </th>
                                <th class="nowrap" width="15%" rowspan="2"
                                    style="vertical-align : middle;text-align:center;">
                                    <?php echo JText::_('COM_SIAK_FIELD_SEMESTER_LABEL'); ?>
                                </th>

                                <?php
                                foreach ($this->bobotNilai as $key => $val) {
                                    echo '<th style="vertical-align : middle;text-align:center;" width="10%" rowspan="2" >';
                                    echo JHtml::tooltip($val->alias, '', '', $val->title, '', false);
                                    echo '<br />Bobot: '.$val->bobot;
                                    echo '</th>';
                                } ?>
                                <th style="vertical-align:middle; text-align:center;" width="10%" rowspan="2">
                                    Nilai Akhir
                                </th>
                                <th style="vertical-align:middle; text-align:center;" width="10%" rowspan="2">
                                    Angka Mutu
                                </th>
                                <th style="vertical-align:middle; text-align:center;" width="10%" rowspan="2">
                                    Huruf Mutu
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($this->items as $i => $item) { ?>
                            <tr class="row<?php echo $i % 2; ?>">
                                <td>
                                    <?php echo $item->npm; ?> <br />
                                    <span class="small break-word">
                                        <?php echo $item->mahasiswa; ?>
                                    </span>
                                </td>
                                <td>
                                    <?php echo $item->semester; ?>
                                </td>

                                <?php

                                foreach ($this->bobotNilai as $key => $val) {
                                    $ro = '';
                                    $zzz = strtolower($val->title);

                                    if ($item->{$zzz} > 0) {
                                        $ro = 'readonly';
                                    }

                                    echo '<td>';
                                    echo '<input type="number" '
                                              .' name="jform['.$item->id.']['.strtolower($val->title).']" '
                                              .' min="0" max="100" step="1" '
                                              .' onChange="Joomla.hitungNilai(this);" '
                                              .' class="input input-small"'
                                              .' id="jform_'.strtolower($val->title).'_'.$item->id.'"'
                                              .' default="0" value="'.$item->{$zzz}.'" '.$ro.'>';
                                    echo '</td>';
                                } ?>

                                <input type="hidden"
                                    id="jform_nilai_angka_<?php echo $item->id; ?>"
                                    name="jform[<?php echo $item->id; ?>][nilai_angka]"
                                    value="<?php echo $item->nilai_angka; ?>" />
                                <input type="hidden"
                                    id="jform_nilai_akhir_<?php echo $item->id; ?>"
                                    name="jform[<?php echo $item->id; ?>][nilai_akhir]"
                                    value="<?php echo $item->nilai_akhir; ?>" />
                                <input type="hidden"
                                    id="jform_nilai_mutu_<?php echo $item->id; ?>"
                                    name="jform[<?php echo $item->id; ?>][nilai_mutu]"
                                    value="<?php echo $item->nilai_mutu; ?>" />

                                <td id="td_nilai_akhir_<?php echo $item->id; ?>"
                                    style="vertical-align: middle; text-align:center;">
                                    <?php echo $item->nilai_akhir; ?>
                                </td>
                                <td id="td_angka_mutu_<?php echo $item->id; ?>"
                                    style="vertical-align: middle; text-align:center;">
                                    <?php echo $item->nilai_mutu; ?>
                                </td>
                                <td id="td_huruf_mutu_<?php echo $item->id; ?>"
                                    style="vertical-align: middle; text-align:center;">
                                    <?php echo $item->nilai_angka; ?>
                                </td>
                            </tr>

                            <?php } ?>
                        </tbody>
                    </table>

                    <?php
                    } ?>
                </div>
            </fieldset>
            <div class="btn-toolbar center">

                <div class="btn-group">
                    <button type="button" class="btn btn-primary" onclick="Joomla.submitbutton('inputnilai.save')">
                        <span
                            class="icon-ok"></span><?php echo JText::_('JSAVE'); ?>
                    </button>
                </div>
                <div class="btn-group">
                    <button type="button" class="btn" onclick="Joomla.submitbutton('inputnilai.cancel')">
                        <span
                            class="icon-cancel"></span><?php echo JText::_('JCANCEL'); ?>
                    </button>
                </div>
            </div>

            <input type="hidden" name="task" />
            <?php echo JHtml::_('form.token'); ?>
        </div>
    </form>
</div>