<?php

defined('_JEXEC') or die;

JHtml::_('bootstrap.framework');
JHtml::_('behavior.tooltip');
JHtml::_('formbehavior.chosen', 'select');
$opsi = ['filterButton' => true];

?>
<div class="container-fluid">
    <legend><?php echo JText::_('COM_SIAK_PRAKTIKUM_DEFAULT_LEGEND'); ?>
    </legend>
    <div class="clearfix"></div>

    <form method="POST"
        action="<?php echo JRoute::_('index.php?option=com_siak&view=praktikums', false); ?>"
        name="adminForm" id="adminForm" class="form-horizontal well">

        <?php echo $this->form->renderFieldset('filter_default', ['class' => 'cols']); ?>

        <?php if (empty($this->items)) { ?>
        <div class="alert alert-no-items">
            <?php echo JText::_('JGLOBAL_NO_MATCHING_RESULTS'); ?>
        </div>
        <?php
            } else {
                echo $this->loadTemplate('result');
            }
        ?>

    </form>
</div>