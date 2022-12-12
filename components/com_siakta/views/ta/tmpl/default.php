<?php
defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;

HTMLHelper::_('jquery.framework');

?>

<legend>
	<?php echo Text::_('COM_SIAKTA_TA_LEGEND'); ?>
</legend>
<div class="clearfix"></div>
<div class="row-fluid">
	<div class="col-md-8">
		<div class="judulta"><strong><i>
					<?php echo $this->item->title; ?>
				</i></strong>
		</div>
	</div>
</div>
<div class="form-horizontal">
	<?php
echo HTMLHelper::_('bootstrap.startTabSet', 'myTab', array('active'=>'detail'));
echo HTMLHelper::_('bootstrap.addTab', 'myTab', 'detail', Text::_('COM_SIAKTA_HEADING_MAHASISWA_LABEL'));

echo $this->form->renderFieldset('akademik');
echo HTMLHelper::_('bootstrap.endTab');
echo HTMLHelper::_('bootstrap.addTab', 'myTab', 'info', Text::_('COM_SIAKTA_HEADING_SIDANG_LABEL'));
echo $this->form->renderFieldset('sidang');
echo HTMLHelper::_('bootstrap.endTab');
echo JHtml::_('bootstrap.endTabSet');
?>
</div>