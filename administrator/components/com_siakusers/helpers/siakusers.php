<?php


defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Helper\ContentHelper;
use Joomla\CMS\Language\Text;

class SiakusersHelper extends ContentHelper
{
    public static function addSubmenu($vName): void
    {
        JHtmlSidebar::addEntry(
            Text::_('COM_SIAKUSERS_SUBMENU_MAHASISWA'),
            'index.php?option=com_siakusers&view=users&mode=0',
            $vName == 'mahasiswa'
        );

        JHtmlSidebar::addEntry(
            Text::_('COM_SIAKUSERS_SUBMENU_PEGAWAI'),
            'index.php?option=com_siakusers&view=users&mode=1',
            $vName == 'pegawai'
        );
    }

    /**
     * Status Mahasiswa
     * Translate Status Mahasiswa Record DB to string
     *
     * @param Int Status
     * @return String Text Status
     */
    public static function statusMahasiswa($status): string
    {
        switch($status) {
            case 1:
                return Text::_('COM_SIAKUSERS_STATUS_MAHASISWA_AKTIF');
                break;
            case -1:
                return Text::_('COM_SIAKUSERS_STATUS_MAHASISWA_CUTI');
                break;
            case 2:
                return Text::_('COM_SIAKUSERS_STATUS_MAHASISWA_LULUS');
                break;
            case -2:
                return Text::_('COM_SIAKUSERS_STATUS_MAHASISWA_DROPOUT');
                break;
            default:
                return Text::_('COM_SIAKUSERS_STATUS_MAHASISWA_TIDAK_AKTIF');
                break;
        }
    }

    public static function statusPegawai($status): string
    {
        switch($status) {
            case 1:
                return Text::_('COM_SIAKUSERS_STATUS_PEGAWAI_AKTIF');
                break;
            case -1:
                return Text::_('COM_SIAKUSERS_STATUS_PEGAWAI_CUTI');
                break;
            case 2:
                return Text::_('COM_SIAKUSERS_STATUS_PEGAWAI_RESIGN');
                break;
            case -2:
                return Text::_('COM_SIAKUSERS_STATUS_PEGAWAI_DIPECAT');
                break;
            default:
                return Text::_('COM_SIAKUSERS_STATUS_PEGAWAI_MUTASI');
                break;
        }
    }
}
