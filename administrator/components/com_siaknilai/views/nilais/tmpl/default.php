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

<form method="POST" name="adminForm" id="adminForm" action="index.php?option=com_siaknilai&view=nilais">
	<div id="j-sidebar-container" class="span2">
		<?php echo JHtmlSidebar::render(); ?>
	</div>
	<div id="j-main-container" class="span10">
		<div class="row-fluid">
			<div class="span12">
				<?php echo LayoutHelper::render('joomla.searchtools.default', array('view'=>$this)); ?>
			</div>
		</div>
		<div class="row-fluid">
			<div class="box-subject-nilai">
				<div class="row-fluid"> 
					<div class="col-md-12 span12">
						Transkip Nilai Mahasiswa : <?php echo $this->mahasiswa->name; ?>
					</div>
				</div>
				<div class="row-fluid small"> 
					<div class="col-md-4 span4">
						Total SKS : <?php echo $this->totalSKS; ?>
					</div>
					<div class="col-md-4 span4">
						Total Bobot x SKS : <?php echo $this->sumBxS; ?>
					</div>
					<div class="col-md-4 span4">
						IP / IPK : <?php echo sprintf('%0.2f', $this->ipk); ?>
					</div>
				</div>
				<div class="row-fluid">
					<?php if(empty($this->TA)) : ?>
							<div class="col-md-12 span12">Tugas Akhir : N/a</div>
					<?php else: ?>
					<div class="col-md-12 span12">
						<span class="small">Judul Tugas Akhir : <i>
							<?php echo $this->escape($this->TA->title); ?></i>
						</span>
					</div>
				</div>
				<div class="row-fluid">
					<div class="col-md-6 span6 small">
						<?php 
							if(($this->TA->tanggal_lulus == '') || ($this->TA->tanggal_lulus == '0000-00-00')){
								$ty = '';
					 		}else{
    							$ty = HTMLHelper::date($this->TA->tanggal_lulus);
							}
							echo 'Tanggal Yudisium : '. $ty; ?>
					</div>
					<div class="col-md-6 span6 small">
						<?php echo 'Yudisium : '. $this->TA->yudisium; ?>
					</div>
				</div>
				<?php endif; ?>
			</div>
		</div>

		<?php if (empty($this->items)) : ?>
		<div class="alert alert-no-items">
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
						<?php echo HTMLHelper::_('searchtools.sort', 'COM_SIAKNILAI_KODE_MATAKULIAH_LABEL', 'c.title', $listDir, $listOrder); ?>
					</th>
					<th class="center">
						<?php echo HTMLHelper::_('searchtools.sort', 'COM_SIAKNILAI_MATAKULIAH_LABEL', 'c.alias', $listDir, $listOrder); ?>
					</th>
					<th class="center">
						<?php echo HTMLHelper::_('searchtools.sort', 'COM_SIAKNILAI_SEMESTER_LABEL', 'd.alias', $listDir, $listOrder); ?>
					</th>

					<th class="center">
						<?php echo Text::_('COM_SIAKNILAI_NILAI_AKHIR_LABEL'); ?>
					</th>
					<th class="center">
						<?php echo Text::_('COM_SIAKNILAI_NILAI_HURUF_LABEL'); ?>
					</th>
					<th class="center">
						<?php echo Text::_('COM_SIAKNILAI_NILAI_ANGKA_LABEL'); ?>
					</th>
					<th class="center">
						<?php echo Text::_('COM_SIAKNILAI_PUBLIS_NILAI_LABEL'); ?>
					</th>
					<th class="center">
						<?php echo HTMLHelper::_('searchtools.sort', 'JGRID_HEADING_ID', 'a.id', $listDir, $listOrder); ?>
					</th>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<td colspan="9" class="center">
						<?php echo $this->pagination->getListFooter(); ?>
					</td>

				</tr>
			</tfoot>
			<tbody>
				<?php foreach($this->items as $i=>$item): ?>
				<tr class="row<?php echo $i % 2; ?>">
					<td class="center">
						<?php echo HTMLHelper::_('grid.id', $i, $item->id); ?>
					</td>
					<td class="center">
						<?php echo $item->kodemk; ?>
					</td>
					<td>
						<?php echo $item->mk; ?>
					</td>
					<td class="center">
						<?php echo $item->smstr; ?>
					</td>
					<td class="center">
						<?php echo $item->nilai_akhir; ?>
					</td>
					<td class="center">
						<?php echo $item->nilai_angka; ?>
					</td>
					<td class="center">
						<?php echo $item->nilai_mutu; ?>
					</td>
					<td class="center">
						<?php echo HTMLHelper::_('jgrid.published', $item->display, $i, 'nilais.', true, 'cb'); ?>
					</td>
					<td class="center">
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
