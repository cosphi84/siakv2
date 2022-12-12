	<?php
/**
 * @package     Joomla.Siak
 * @subpackage  com_siakta
 *
 * @copyright   (C) 2022 @ Risam, S.T
 * @license     Limited for FT-UNTAG Cirebon use Only
 */

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\LayoutHelper;

defined('_JEXEC') or die;

HTMLHelper::_('behavior.formvalidator');
HTMLHelper::_('formbehavior.chosen', 'select');
HTMLHelper::_('jquery.framework');
HTMLHelper::_('script', 'com_siakta/siakta.js', array('version'=>'auto', 'relative'=>true));
?>

	<div class="clearfix"></div>
	<form method="POST" id="adminForm" name="adminForm" class="form-validate"
		action="index.php?option=com_siakta&view=ta&layout=edit&id=<?php echo $this->item->id; ?>">
		<?php echo LayoutHelper::render('joomla.edit.title_alias', $this); ?>

		<div class="form-horizontal">
			<?php echo HTMLHelper::_('bootstrap.startTabSet', 'myTab', array('active'=>'akademik')); ?>

			<?php echo HTMLHelper::_('bootstrap.addTab', 'myTab', 'akademik', Text::_('COM_SIAKTA_HEADING_AKADEMIK_LABEL')); ?>
			<?php echo $this->form->renderFieldset('akademik'); ?>
			<?php echo HTMLHelper::_('bootstrap.endTab'); ?>

			<?php echo HTMLHelper::_('bootstrap.addTab', 'myTab', 'sidang', Text::_('COM_SIAKTA_HEADING_SIDANG_LABEL')); ?>
			<?php echo $this->form->renderFieldset('sidang'); ?>
			<?php echo HTMLHelper::_('bootstrap.endTab'); ?>

			<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'opsi', JText::_('JFIELD_NOTE_LABEL')); ?>
			<div class="row-fluid form-horizontal-desktop">
				<?php echo $this->form->renderFieldset('opsi'); ?>
			</div>
			<?php echo JHtml::_('bootstrap.endTab'); ?>

			<?php echo JHtml::_('bootstrap.endTabSet'); ?>
		</div>
		<input type="hidden" name="task" value="" />
		<?php echo JHtml::_('form.token'); ?>
	</form>