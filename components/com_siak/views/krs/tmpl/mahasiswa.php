<?php

defined('_JEXEC') or exit;

JHtml::_('bootstrap.framework');
JHtml::_('behavior.formvalidation');
JHtml::_('behavior.keepalive');
JHtml::_('behavior.core');
JHtml::_('script', 'com_siak/krs.js', ['version' => 'auto', 'relative' => true]);
JHtml::_('script', 'com_siak/submitbutton.js', ['version' => 'auto', 'relative' => true]);
JHtml::stylesheet(JUri::base().'media/com_siak/css/siak.css');

$link = 'index.php?option=com_siak&amp;view=matkuls&amp;layout=modal&amp;tmpl=component&amp;'.JSession::getFormToken().'=1';
$urlSelect = $link.'&amp;function=jSelectMatakuliah';

echo JHtml::_(
    'bootstrap.renderModal',
    'ModalSelectMKS',
    [
        'title' => 'Pilih Matakuliah',
        'url' => $urlSelect,
        'height' => '400px',
        'width' => '800px',
        'bodyHeight' => '70',
        'modalWidth' => '80',
        'footer' => '<a role="button" class="btn" data-dismiss="modal" aria-hidden="true">'.JText::_('JLIB_HTML_BEHAVIOR_CLOSE').'</a>',
    ]
);
?>

<div class="container-fluid">
    <legend>
        <?php echo JText::_('COM_SIAK_KRS_FRM_MAHASISWA_LEGEND'); ?>
    </legend>
    <div class="clearfix"></div>
    <form method="post"
        action="<?php echo JRoute::_('index.php?option=com_siak&view=krs&id='.$this->item->id, false); ?>"
        id="adminForm" name="adminForm" class="form-horizontal well form-validate">


        <fieldset class="adminform">
            <?php echo $this->form->renderFieldset('frmKrs'); ?>
            <div class="col-span-12 center">
                <button type="button" class="btn btn-primary" onclick="Joomla.submitbutton('krs.save')">
                    <span class="icon-next"></span><?php echo JText::_('JNEXT'); ?>
                </button>
            </div>

            <input type="hidden" name="task" />
            <input id="token" type="hidden"
                name="<?php echo JSession::getFormToken(); ?>"
                value="1" />
        </fieldset>
    </form>
</div>