<?php

defined('_JEXEC') or die;

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Layout\LayoutHelper;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Language\Text;

HTMLHelper::_('behavior.multiselect');
HTMLHelper::_('formbehavior.chosen', 'select');
HTMLHelper::_('behavior.tooltip');

$listOrder = $this->escape($this->state->get('list.ordering'));
$listDir = $this->escape($this->state->get('list.direction'));

$mode = $this->state->get('mode', 0);
?>

<form method="POST"
    action="<?php echo Route::_('index.php?option=com_siakusers&view=users'); ?>"
    id="adminForm" name="adminForm">
    <div id="j-sidebar-container" class="span2">
        <?php echo JHtmlSidebar::render(); ?>
    </div>
    <div id="j-main-container" class="span10">
        <div class="row-fluid">
            <?php echo LayoutHelper::render('joomla.searchtools.default', array('view'=>$this)); ?>
        </div>
        <div class="row-fluid">
            <?php if (empty($this->items)) : ?>
            <div class="alert alert-no-items">
                <?php echo Text::_('JGLOBAL_NO_MATCHING_RESULTS'); ?>
            </div>
            <?php else: ?>
            <table class="table table-hover table-striped" id="tableUsers">
                <thead>
                    <tr>
                        <th class="center" width="1%">
                            <?php echo HTMLHelper::_('grid.checkall'); ?>
                        </th>
                        <th>
                            <?php echo HTMLHelper::_('searchtools.sort', 'COM_SIAKUSERS_HEADING_NAME', 'u.name', $listDir, $listOrder); ?>
                        </th>

                        <?php if($mode == 0) : ?>
                        <th>
                            <?php echo HTMLHelper::_('searchtools.sort', 'COM_SIAKUSERS_HEADING_PRODI', 'a.prodi', $listDir, $listOrder); ?>
                        </th>
                        <th class="nowrap">
                            <?php echo HTMLHelper::_('searchtools.sort', 'COM_SIAKUSERS_KELAS_PRODI', 'a.kelas', $listDir, $listOrder); ?>
                        </th>
                        <?php else: ?>
                        <th>
                            <?php echo HTMLHelper::_('searchtools.sort', 'COM_SIAKUSERS_HEADING_NIK', 'a.nik', $listDir, $listOrder); ?>
                        </th>
                        <th class="center nowrap">
                            <?php echo HTMLHelper::_('searchtools.sort', 'COM_SIAKUSERS_HEADING_NIDN', 'a.nidn', $listDir, $listOrder); ?>
                        </th>
                        <?php endif; ?>
                        <th class="nowrap">
                            <?php echo Text::sprintf('COM_SIAKUSERS_HEDAING_STATUS', $mode == 0 ? "Mahasiswa" : "Pegawai"); ?>
                        </th>
                        <th class="nowrap">
                            <?php echo Text::_('COM_SIAKUSERS_HEDAING_BLOKIR_STATUS'); ?>
                        </th>
                        <th width="1%" class="nowrap hidden-phone">
                            <?php echo JHtml::_('searchtools.sort', 'JGRID_HEADING_ID', 'a.id', $listDir, $listOrder); ?>
                        </th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <td colspan=" 9" class="center">
                            <?php echo $this->pagination->getListFooter(); ?>
                        </td>

                    </tr>
                </tfoot>
                <tbody>
                    <?php foreach($this->items as $k=>$item): ?>
                    <tr class="row<?php echo $k % 2; ?>">
                        <td class="center">
                            <?php echo HTMLHelper::_('grid.id', $k, $item->id); ?>
                        </td>
                        <td class="nowrap">
                            <?php echo $this->escape($item->nama); ?>

                            <?php if($mode == 0) : ?>
                            <div class="small">
                                <?php echo 'NPM :' . $item->npm; ?>
                            </div>
                            <?php endif; ?>
                        </td>
                        <?php if($mode == 0) : ?>
                        <td>
                            <?php echo $this->escape($item->prodi). ' - ' . $this->escape($item->angkatan); ?>
                            <div class="small">
                                <?php echo $this->escape($item->konsentrasi); ?>
                            </div>
                        </td>
                        <td>
                            <?php echo $this->escape($item->kelas_mhs); ?>
                        </td>
                        <td>
                            <?php echo SiakusersHelper::statusMahasiswa($item->state); ?>
                        </td>
                        <?php else: ?>
                        <td>
                            <?php echo $this->escape($item->nik); ?>
                        </td>
                        <td>
                            <?php echo $this->escape($item->nidn); ?>
                        </td>
                        <td>
                            <?php echo SiakusersHelper::statusPegawai($item->state); ?>
                        </td>
                        <?php endif; ?>
                        <td>
                            <?php if($item->block) {
                                echo HTMLHelper::tooltip(
                                    'Status Tutup membuat pengguna tidak bisa login dan transaksi di SIAK',
                                    'Status Diblokir',
                                    '',
                                    Text::_('COM_SIAKUSERS_STATUS_USER_BLOCKED')
                                );
                            } else {
                                echo HTMLHelper::tooltip(
                                    'Status Buka memungkinkan pengguna untuk login dan melakukan transaksi di SIAK.',
                                    'Status Dibuka',
                                    '',
                                    Text::_('COM_SIAKUSERS_STATUS_USER_UNBLOCKED')
                                );
                            }
                        ?>
                        <td class="center">
                            <?php echo (int)$item->id; ?>
                        </td>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php endif; ?>
        </div>
    </div>
    <input type="hidden" name="task" value="" />
    <input type="hidden" name="boxchecked" value="0" />
    <?php echo HTMLHelper::_('form.token'); ?>
</form>