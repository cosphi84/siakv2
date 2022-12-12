<?php
/**
 * @package     Joomla.Siak
 * @subpackage  com_siakta
 *
 * @copyright   (C) 2022 @ Risam, S.T
 * @license     Limited for FT-UNTAG Cirebon use Only
 */

use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\LayoutHelper;

 defined('_JEXEC') or die;

 HTMLHelper::_('behavior.multiselect');
 HTMLHelper::_('formbehavior.chosen', 'select');


 $listOrder = $this->escape($this->state->get('list.ordering'));
 $listDir   = $this->escape($this->state->get('list.diection'));

 ?>

<form method="POST" name="adminForm" id="adminForm" action="index.php?option=com_siakta&view=tas">
	<div id="j-sidebar-container" class="span2">
		<?php echo JHtmlSidebar::render(); ?>
	</div>
	<div id="j-main-container" class="span10">
		<div class="row-fluid">
			<div class="span12">
				<?php echo LayoutHelper::render('joomla.searchtools.default', array('view'=>$this)); ?>
			</div>
		</div>

		<?php if (empty($this->items)) : ?>
		<div class="allert alert-no-items">
			<?php echo Text::_('JGLOBAL_NO_MATCHING_RESULTS'); ?>
		</div>
		<?php else: ?>
		<table class="table table-striped table-hover">
			<thead>
				<tr>
					<th class="center" width="1%">
						<?php echo HTMLHelper::_('grid.checkall'); ?>
					</th>
					<th class="center">
						<?php echo HTMLHelper::_('searchtools.sort', 'COM_SIAKTA_HEADING_TAHUN_LABEL', 'a.tahun', $listDir, $listOrder); ?>
					</th>
					<th class="center">
						<?php echo Text::_('COM_SIAKTA_HEADING_JUDUL_TA_LABEL'); ?>
					</th>
					<th class="center nowrap">
						<?php echo HTMLHelper::_('searchtools.sort', 'COM_SIAKTA_HEADING_MAHASISWA_LABEL', 'd.name', $listDir, $listOrder); ?>
					</th>
					<th class="center">
						<?php echo Text::_('COM_SIAKTA_HEADING_TANGGAL_SIDANG_LABEL'); ?>
					</th>
					<th class="center">
						<?php echo Text::_('COM_SIAKTA_HEADING_PEMBIMBING_LABEL'); ?>
					</th>
					<th class="center">
						<?php echo Text::_('COM_SIAKTA_HEADING_YUDISIUM_LABEL'); ?>
					</th>
					<th class="center">
						<?php echo Text::_('JSTATUS'); ?>
					</th>
					<th class="center">
						<?php echo HTMLHelper::_('searchtools.sort', 'JGRID_HEADING_ID', 'a.id', $listDir, $listOrder); ?>
					</th>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<td>
						<?php echo $this->pagination->getListFooter(); ?>
					</td>

				</tr>
			</tfoot>
			<tbody>
				<?php foreach ($this->items as $i=>$item) : ?>
				<tr class="row<?php echo $i % 2; ?>">
					<td class="center">
						<?php echo HTMLHelper::_('grid.id', $i, $item->id); ?>
					</td>
					<td class="center">
						<?php echo $this->escape($item->tahun); ?>
					</td>
					<td>
						<?php echo $this->escape($item->title); ?>
					</td>
					<td class="nowrap">
						<?php echo $this->escape($item->mahasiswa);?>
						<div class="small">
							<?php echo $item->npm. ' / '. $item->prodi . ' / '. $item->jurusan; ?>
						</div>
					</td>

					<td class="nowrap">
						<?php
                                    if ($item->sidang_proposal == '0000-00-00 00:00:00') {
                                        echo 'Proposal: N/a';
                                    } else {
                                        echo 'Proposal: '.HTMLHelper::date($item->sidang_proposal, 'd-m-Y', false);
                                    }
                                    echo '<br />';
                                    if ($item->sidang_akhir == '0000-00-00 00:00:00') {
                                        echo 'Akhir: N/a';
                                    } else {
                                        echo 'Akhir: '. HTMLHelper::date($item->sidang_proposal, 'd-m-Y', false);
                                    }
                                 ?>
					</td>
					<td class="nowrap">
						<?php
                                    if ((int) $item->dosbing1 > 0) {
                                        echo '1: '. Factory::getUser($item->dosbing1)->name;
                                    } else {
                                        echo '1: N/a';
                                    }
                                    echo '<br />';
                                    if ((int) $item->dosbing2 > 0) {
                                        echo '2: '. Factory::getUser($item->dosbing2)->name;
                                    } else {
                                        echo '2: N/a';
                                    }
                                ?>
					</td>
					<td class="nowrap">
						<?php echo $item->yudisium; ?>
					</td>
					<td class="center">
						<?php echo HTMLHelper::_('jgrid.published', $item->state, $i, 'tas.', true, 'cb'); ?>
					</td>
					<td>
						<?php echo (int) $item->id; ?>
					</td>
				</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
		<?php endif; ?>
	</div>
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="boxchecked" value="0" />
	<?php echo HTMLHelper::_('form.token'); ?>
</form>