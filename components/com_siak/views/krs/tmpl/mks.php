<?php

defined('_JEXEC') or die;

?>

<div class="container">
    <legend><?php echo JText::_('COM_SIAK_MKS_DETAIL_LEGEND'); ?>
    </legend>
    <div class="clearfix"></div>
    <table class="table table-striped well" width="700px">
        <thead>
            <tr>
                <th class="center">
                    <?php echo JText::_('COM_SIAK_NO_URUT'); ?>
                </th>
                <th>
                    <?php echo JText::_('COM_SIAK_KODE_MK_TITLE'); ?>
                </th>
                <th>
                    <?php echo JText::_('COM_SIAK_MK_TITLE'); ?>
                </th>
                <th class="center">
                    <?php echo JText::_('COM_SIAK_SKS_TITLE'); ?>
                </th>
            </tr>
        </thead>
        <tbody>
            <?php
            $a = 1;
            foreach ($this->mks as $i => $v) {
                echo '<tr class="row'.($i % 2).'">'.PHP_EOL;
                echo '<td class="center">'.$a;
                echo '<td>'.$v->kode.'</td>'.PHP_EOL;
                echo '<td>'.$v->MK.'</td>'.PHP_EOL;
                echo '<td class="center" id="sks">'.$v->sks.'</td>'.PHP_EOL;
                echo '</tr>'.PHP_EOL;
                ++$a;
            } ?>
        </tbody>
    </table>
    <div class="clearfix"></div>
    <div class="btn-toolbar center">

        <div class="btn-group">
            <button type="button" class="btn btn-primary" onclick="window.close()">
                <span class="icon-cancel"></span><?php echo JText::_('JCLOSE'); ?>
            </button>
        </div>
    </div>
</div>