<?php
/**
 * @package     Joomla.Siak
 * @subpackage  regiser TA
 *
 * @copyright   (C) 2022 @ Risam, S.T
 * @license     Limited for FT-UNTAG Cirebon use Only
 */

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;

 defined('_JEXEC') or die;

 HTMLHelper::_('behavior.formvalidator');
 HTMLHelper::_('formbehavior.chosen', 'select');
 HTMLHelper::_('script', 'com_siakta/siakta.js', array('version'=>'auto', 'relative'=>true));
 ?>

<legend>
	<h1>
		<?php echo Text::_('COM_SIAKTA_VIEW_REGISTER_LEGEND_PAGE'); ?>
	</h1>
</legend>
<div class="clearfix"></div>

<div class="span10">
	<div class="row-fluid">
		<div class="span12">
			<form method="POST"
				action="<?php echo Route::_('index.php?option=com_siakta&view=register&layout=edit'); ?>"
				name="adminForm" id="adminForm" class="form-validate form-horizontal well">

				<div class="form-horizontal">
					<fieldset class="adminForm">
						<?php echo $this->form->renderFieldset('frmRegistrasi'); ?>
					</fieldset>

					<div class="btn-toolbar center">
						<div class="btn-group">
							<button type="button" class="btn btn-primary"
								onClick="Joomla.submitbutton('register.save')">
								<span class="icon-ok"></span><?php echo Text::_('JSAVE'); ?>
							</button>
						</div>
						<div class="btn-group">
							<button type="button" class="btn" onClick="Joomla.submitbutton('register.cancel')">
								<span class="icon-cancel"></span><?php echo Text::_('JCANCEL'); ?>
							</button>
						</div>
					</div>

				</div>
				<input type="hidden" name="task" value="" />
				<?php echo HTMLHelper::_('form.token'); ?>
			</form>
		</div>
	</div>
</div>