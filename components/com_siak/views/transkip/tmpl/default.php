<?php

defined('_JEXEC') or exit;
JLoader::register('TrasnkripHelper', JPATH_COMPONENT.'/helpers/trasnkrip.php');

JHtml::_('bootstrap.framework');
JHtml::_('jquery.framework');
JHtml::_('behavior.keepalive');
JHtml::_('behavior.core');
JHtml::_('formbehavior.chosen', 'select');

$totalSKS = 0;
$totalNilai = 0;
$user = JFactory::getUser();
$params = $this->state->params;
$nilaiMinimalRemidial = $params->get('minScoreToRemidial');
$nilai = [];
foreach ($this->items as $k => $v) {
    if (!isset($nilai[$v->smt]['sumSKS'])) {
        $nilai[$v->smt]['sumSKS'] = 0;
    }
    if (!isset($nilai[$v->smt]['sumNilai'])) {
        $nilai[$v->smt]['sumNilai'] = 0;
    }
    TrasnkripHelper::getPaymentStatus($user->id, $v->sid) ? $nilai[$v->smt]['lunas'] = true : $nilai[$v->smt]['lunas'] = false;

    /*
    if ('MURNI' != $v->status && $v->nilai_mutu < $v->nilai_remid_mutu) {
        $v->nilai_akhir = $v->nilai_akhir_remid;
        $v->nilai_angka = $v->nilai_remid_angka;
        $v->nilai_mutu = $v->nilai_remid_mutu;
    }
    */
    $v->remid = false;
    if ($v->nilai_mutu <= $nilaiMinimalRemidial) {
        $v->remid = true;
    }
    $v->BxN = $v->sks * $v->nilai_mutu;
    (int) $nilai[$v->smt]['sumSKS'] += (int) $v->sks;
    (int) $nilai[$v->smt]['sumNilai'] += $v->BxN;
    $nilai[$v->smt]['nilai'][] = $v;
}
ksort($nilai, SORT_NUMERIC);

/*

foreach ($nilai as $k => $v) {
    TrasnkripHelper::getPaymentStatus($user->id, $v[0]->sid) ? $nilai[$k]['lunas'] = true : $nilai[$k]['lunas'] = false;
    $v->NxB = (int) $v->nilai_mutu * (int) $v->sks;
    $nilai[$v->smt]['sumNilai'] .= $v->NxB;
    $nilai[$v->smt]['sumSKS'] .= $v->sks;
    $nilai[$v->smt]['ip'] = $nilai[$v->smt]['sumNilai'] / $nilai[$v->smt]['sumSKS'];
}
var_dump($nilai);
*/
?>

<div class="container-fluid">
    <legend>
        <?php echo JText::_('COM_SIAK_TRANSKIP_NILAI_LEGEND'); ?>
    </legend>

    <div class="clearfix"></div>
    <div class="row fluid well">

        <form
            action="<?php echo JRoute::_('index.php?option=com_siak&view=transkip'); ?>"
            method="post" name="adminForm" id="adminForm">
            <?php echo JLayoutHelper::render('joomla.searchtools.default', ['view' => $this]); ?>
            <input type="hidden" name="task" value="" />
            <input type="hidden" name="boxchecked" value="0" />
            <?php echo JHtml::_('form.token'); ?>
        </form>

        <?php if (empty($this->items)) { ?>
        <div class="alert alert-no-items">
            <?php echo JText::_('JGLOBAL_NO_MATCHING_RESULTS'); ?>
        </div>
        <?php } else { ?>

        <table id="tableTranskrip" class="table table-striped">
            <thead>
                <tr>
                    <th class="nowrap">
                        <?php echo JText::_('COM_SIAK_FIELD_MATAKULIAH_TITLE'); ?>
                    </th>
                    <th class="center">
                        <?php echo JText::_('COM_SIAK_SKS_TITLE'); ?>
                    </th>
                    <th class="nowrap center">
                        <?php echo JText::_('COM_SIAK_NILAI_ANGKA_MUTU_LABEL'); ?>
                    </th>
                    <th class="nowrap center">
                        <?php echo JText::_('COM_SIAK_NILAI_BXN_LABEL'); ?>
                    </th>
                    <th class="nowrap center">
                        <?php echo JText::_('COM_SIAK_NILAI_HURUF_MUTU_LABEL'); ?>
                    </th>

                </tr>
            </thead>

            <tbody>
                <?php foreach ($nilai as $key => $item) { ?>
                <tr class="row<?php echo $key % 2; ?>">
                    <td colspan="6">
                        <h2>
                            <?php echo JText::_('COM_SIAK_FIELD_SEMESTER_LABEL').': '.$key; ?>
                        </h2>
                        IP :
                        <?php echo sprintf('%.2f', ($item['sumNilai'] / $item['sumSKS'])); ?>
                    </td>
                <tr>
                    <?php foreach ($item['nilai'] as $k => $v) {
                        ?>
                <tr>
                    <td>
                        <?php echo $v->kodemk; ?><br />
                        <div class="small">
                            <?php echo $v->mk; ?>
                        </div>
                    </td>
                    <td class="center">
                        <?php echo $v->sks; ?>
                    </td>
                    <td class="center">
                        <?php //if ($item['lunas']) {
                                                echo $v->nilai_mutu;
                                //} else {
                            //echo '<span class="icon-stop-circle"></span>';
                            //}?>
                    </td>
                    <td class="center">
                        <?php // if ($item['lunas']) {
                            echo $v->BxN;
    //} else {
        //echo '<span class="icon-stop-circle"></span>';
    //}?>
                    </td>
                    <td class="center">
                        <?php //if ($item['lunas']) {
                            echo $v->nilai_angka;
                        // } else {
                        // echo '<span class="icon-stop-circle"></span>';
                        //}?>
                    </td>

                </tr>
                <?php
                    }
                    ?>
                <tr>
                    <td>


                        <?php }?>

            </tbody>
        </table>

        <?php } ?>
    </div>
</div>