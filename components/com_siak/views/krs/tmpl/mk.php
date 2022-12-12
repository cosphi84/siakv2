<?php

defined('_JEXEC') or die;

$id = JFactory::getApplication()->input->get('id', 0, 'int');

JHtml::_('bootstrap.framework');
JHtml::_('jquery.framework');
JHtml::_('behavior.formvalidation');
JHtml::_('behavior.keepalive');
JHtml::_('behavior.core');
JHtml::_('script', 'com_siak/krs.js', ['version' => 'auto', 'relative' => true]);
//JHtml::_('script', 'com_siak/submitbutton.js', ['version' => 'auto', 'relative' => true]);

$link = 'index.php?option=com_siak&amp;view=matkuls&amp;layout=modal&amp;tmpl=component&amp;'.JSession::getFormToken().'=1';
$urlSelect = $link.'&amp;function=jSelectMatakuliah';
JHtml::stylesheet(JUri::base().'media/com_siak/css/siak.css');

$user = JFactory::getUser($this->item->user_id);

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

<div class="j-main-container">
    <legend>
        <?php echo JText::_('COM_SIAK_KRS_FRM_MK_LEGEND'); ?>
    </legend>
    <div class="clearfix"></div>
    <form method="post"
        action="<?php echo JRoute::_('index.php?option=com_siak&view=krs&id='.$id, false); ?>"
        id="adminForm" name="adminForm" class="form-horizontal well form-validate">
        <table class="table table-striped table-condensed">
            <tr>
                <td>KRS ID : </td>
                <td><?php echo $this->item->id; ?>
            </tr>
            <tr>
                <td>
                    Nama Mahasiswa :
                </td>
                <td>
                    <?php echo $user->name; ?>
                </td>
            </tr>
            <tr>
                <td>
                    NPM :
                </td>
                <td>
                    <?php echo $user->username; ?>
                </td>
            </tr>
            <tr>
                <td>
                    Semester :
                </td>
                <td>
                    <?php echo $this->item->title; ?>
                </td>
            </tr>
        </table>
        <fieldset class="adminform">
            <div class="controls">
                <a class="btn btn-link" data-toggle="modal" role="button" href="#ModalSelectMKS" title="">
                    <span class="icon-save-new" aria-hidden="true"></span> <?php echo JText::_('COM_SIAK_ADD_MK'); ?>
                </a>
            </div>
            <table class="table table-bordered table-striped" id="tblMatakuliah" name="tblMatakuliah">
                <thead>
                    <tr>
                        <th class="center">
                            <?php echo JText::_('COM_SIAK_NO_URUT'); ?>
                        </th>
                        <th class="center">
                            <?php echo JText::_('COM_SIAK_KODE_MK_TITLE'); ?>
                        </th>
                        <th class="center">
                            <?php echo JText::_('COM_SIAK_MK_TITLE'); ?>
                        </th>
                        <th class="center">
                            <?php echo JText::_('COM_SIAK_SKS_TITLE'); ?>
                        </th>
                        <th class="center">
                            <?php echo JText::_('Opsi'); ?>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                            if (count($this->mks) > 0 && false != $this->mks) {
                                $a = 1;
                                $ttlSKS = 0;
                                foreach ($this->mks as $i => $v) {
                                    echo '<tr class="row'.($i % 2).'">'.PHP_EOL;
                                    echo '<td class="center">'.$a;
                                    echo '<input type="hidden" name="jform[mk]['.$i.']" class="mkid" value="'.$v->mkid.'"></td>'.PHP_EOL;
                                    echo '<td id="kodemk">'.$v->kode.'</td>'.PHP_EOL;
                                    echo '<td>'.$v->MK.'</td>'.PHP_EOL;
                                    echo '<td class="center" id="sks">'.$v->sks.'</td>'.PHP_EOL;
                                    echo '<td class="center"><a href="javascript:void(0);" onclick="deletemk('.$v->mid.');"><span class="icon-delete"></span></a></td>';
                                    echo '</td>';
                                    echo '</tr>'.PHP_EOL;
                                    ++$a;
                                    $ttlSKS += $v->sks;
                                } ?>

                </tbody>
                <?php
                            } else { ?>
                <tbody>

                </tbody>
                <?php } ?>
            </table>

            <?php
            echo $this->form->renderFieldset('ttlSKS');
            if (!$this->isMahasiswa) {
                echo $this->form->renderFieldset('frmKrsDosWal');
            } else {
                echo $this->form->renderFieldset('statusKRS');
            }
            ?>
        </fieldset>
        <div class="btn-toolbar center">
            <div class="btn-group">
                <button type="button" class="btn btn-primary" onclick="Joomla.submitbutton('krs.savemk')">
                    <span class="icon-ok"></span><?php echo JText::_('JSAVE'); ?>
                </button>
            </div>
            <div class="btn-group">
                <button type="button" class="btn" onclick="Joomla.submitbutton('krs.cancel')">
                    <span class="icon-cancel"></span><?php echo JText::_('JCANCEL'); ?>
                </button>
            </div>
        </div>
        <input type="hidden" name="task" />
        <input id="token" type="hidden"
            name="<?php echo JSession::getFormToken(); ?>"
            value="1" />
    </form>
</div>