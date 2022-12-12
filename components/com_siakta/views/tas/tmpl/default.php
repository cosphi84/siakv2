<?php

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\LayoutHelper;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Uri\Uri;

defined('_JEXEC') or die;

$listOrder  = $this->escape($this->state->get('list.ordering'));
$listDirn   = $this->escape($this->state->get('list.direction'));

?>

<legend>
	<h1>
		<?php echo Text::_('COM_SIAKTA_VIEW_TAS_PAGETITLE'); ?>
	</h1>
</legend>

<div class="clearfix"></div>

<form method="POST"
	action="<?php echo Route::_('index.php?option=com_siakta&view=tas'); ?>"
	id="adminForm" name="adminForm">
	<div id="j-main-container" class="span12 well">
		<?php echo LayoutHelper::render('joomla.searchtools.default', array('view'=>$this)); ?>
		<?php if (empty($this->items)) : ?>
		<div class="alert alert-no-items">
			<?php echo Text::_('JGLOBAL_NO_MATCHING_RESULTS'); ?>
		</div>
		<?php else : ?>
		<table class="table table-striped">
			<thead>
				<tr>
					<th class="center" width="1%">
						#
					</th>
					<th width="10%" class="center">
						<?php echo HTMLHelper::_('searchtools.sort', 'COM_SIAKTA_HEADING_TAHUN_LABEL', 'a.tahun', $listDirn, $listOrder); ?>
					</th>
					<th width="40%" class="center">
						<?php echo HTMLHelper::_('searchtools.sort', 'COM_SIAKTA_HEADING_JUDUL_TA_LABEL', 'a.judul', $listDirn, $listOrder); ?>
					</th>
					<th width="20%" class="center">
						<?php echo HTMLHelper::_('searchtools.sort', 'COM_SIAKTA_HEADING_PENULIS_LABEL', 'u.name', $listDirn, $listOrder); ?>
					</th>
					<th width="14%" class="center">
						<?php echo Text::_('COM_SIAKTA_HEADING_PRODI_JURUSAN_LABEL'); ?>
					</th>
					
				</tr>
			</thead>
			<tfoot>
				<tr>
					<td class="center" colspan="6">
						<?php echo $this->pagination->getListFooter(); ?>
					</td>
				</tr>
			</tfoot>
			<tbody>
				<?php foreach ($this->items as $i=>$item): ?>
				<tr class="row<?php echo $i % 2; ?>">
					<td class="center">
						<?php echo $this->pagination->getRowOffset($i); ?>
					</td>
					<td class="center">
						<?php echo $this->escape($item->tahun); ?>
					</td>
					<td>
						<a href="<?php echo JRoute::_('index.php?option=com_siakta&view=ta&id='.$item->id);?>">
							<?php echo $this->escape($item->title); ?>
						</a>
					</td>
					<td>
						<?php echo $item->mahasiswa; ?>
						<div class="small">
							<?php echo "NPM: ". $item->NPM; ?>
						</div>
					</td>
					<td class="center">
						<?php echo $item->prodi . ' / '. $item->jurusan; ?>
					</td>
					
				</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
		<?php endif; ?>
	</div>
	<input type="hidden" name="task" value="" />
	<?php echo JHtml::_('form.token'); ?>
</form>