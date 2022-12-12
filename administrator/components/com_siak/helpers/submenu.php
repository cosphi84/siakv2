<?php

defined('_JEXEC') or exit;

class SiakSubmenu extends JHelperContent
{
    public static function submenuFakultas($vName)
    {
        $canDo = JHelperContent::getActions('com_siak');

        if ($canDo->get('core.options')) {
            JHtmlSidebar::addEntry(
                JText::_('COM_SIAK_SIDE_MENU_PRODI'),
                'index.php?option=com_siak&view=prodis',
                'Prodis' == $vName
            );

            JHtmlSidebar::addEntry(
                JText::_('COM_SIAK_SIDE_MENU_JURUSAN'),
                'index.php?option=com_siak&view=jurusans',
                'Jurusans' == $vName
            );

            JHtmlSidebar::addEntry(
                JText::_('COM_SIAK_SIDE_MENU_KELASES'),
                'index.php?option=com_siak&view=kelases',
                'Kelases' == $vName
            );

            JHtmlSidebar::addEntry(
                JText::_('COM_SIAK_SIDE_MENU_BOBOTS'),
                'index.php?option=com_siak&view=bobots',
                'Bobots' == $vName
            );
        }

        JHtmlSidebar::addEntry(
            JText::_('COM_SIAK_SIDE_MENU_RUANGANS'),
            'index.php?option=com_siak&view=ruangans',
            'Ruangans' == $vName
        );

        JHtmlSidebar::addEntry(
            JText::_('COM_SIAK_SIDE_MENU_SKS'),
            'index.php?option=com_siak&view=sks',
            'Sks' == $vName
        );

        JHtmlSidebar::addEntry(
            JText::_('COM_SIAK_SIDE_MENU_SEMESTERS'),
            'index.php?option=com_siak&view=semesters',
            'Semesters' == $vName
        );

        JHtmlSidebar::addEntry(
            JText::_('COM_SIAK_SIDE_MENU_MATKULS'),
            'index.php?option=com_siak&view=matkuls',
            'Matkuls' == $vName
        );

        JHtmlSidebar::addEntry(
            JText::_('COM_SIAK_SIDE_MENU_PAKETMKS'),
            'index.php?option=com_siak&view=paketmks',
            'Paketmks' == $vName
        );
        JHtmlSidebar::addEntry(
            JText::_('COM_SIAK_SIDE_MENU_MAHASISWAS'),
            'index.php?option=com_siak&view=mahasiswas',
            'Mahasiswas' == $vName
        );

        JHtmlSidebar::addEntry(
            JText::_('COM_SIAK_SIDE_MENU_DOSENS'),
            'index.php?option=com_siak&view=dosens',
            'Dosens' == $vName
        );

        JHtmlSidebar::addEntry(
            JText::_('COM_SIAK_SIDE_MENU_WALIS'),
            'index.php?option=com_siak&view=walis',
            'Walis' == $vName
        );

        JHtmlSidebar::addEntry(
            JText::_('COM_SIAK_SIDE_MENU_DOSENMKS'),
            'index.php?option=com_siak&view=dosenmks',
            'Dosenmks' == $vName
        );

        JHtmlSidebar::addEntry(
            JText::_('COM_SIAK_SIDE_MENU_INDUSTRIES'),
            'index.php?option=com_siak&view=industries',
            'Industries' == $vName
        );
    }

    public static function submenuKeuangan($vName)
    {
        JHtmlSidebar::addEntry(
            JText::_('COM_SIAK_SIDE_MENU_BAYARANS'),
            'index.php?option=com_siak&view=bayarans',
            'Bayarans' == $vName
        );

        JHtmlSidebar::addEntry(
            JText::_('COM_SIAK_SIDE_MENU_TUNDABAYAR'),
            'index.php?option=com_siak&view=tundabayar',
            'Tundabayar' == $vName
        );
    }

    public static function submenuAkademik($vName)
    {
        JHtmlSidebar::addEntry(
            JText::_('COM_SIAK_SIDE_MENU_JADWALS'),
            'index.php?option=com_siak&view=jadwals',
            'Jadwals' == $vName
        );

        JHtmlSidebar::addEntry(
            JText::_('COM_SIAK_SIDE_MENU_DAFTAR_ULANGS'),
            'index.php?option=com_siak&view=dus',
            'Dus' == $vName
        );

        JHtmlSidebar::addEntry(
            JText::_('COM_SIAK_SIDE_MENU_KRSS'),
            'index.php?option=com_siak&view=krss',
            'Krss' == $vName
        );

        JHtmlSidebar::addEntry(
            JText::_('COM_SIAK_SIDE_MENU_ROMBELS'),
            'index.php?option=com_siak&view=rombels',
            'Rombels' == $vName
        );

        JHtmlSidebar::addEntry(
            JText::_('COM_SIAK_SIDE_MENU_PRAKTIKUMS'),
            'index.php?option=com_siak&view=praktikums',
            'Praktikums' == $vName
        );

        JHtmlSidebar::addEntry(
            JText::_('COM_SIAK_SIDE_MENU_KPS'),
            'index.php?option=com_siak&view=kps',
            'Kps' == $vName
        );

        JHtmlSidebar::addEntry(
            JText::_('COM_SIAK_SIDE_MENU_UJIANS'),
            'index.php?option=com_siak&view=ujians',
            'Ujians' == $vName
        );

        /*
        JHtmlSidebar::addEntry(
            JText::_('COM_SIAK_SIDE_MENU_PROPTAS'),
            'index.php?option=com_siak&view=proptas',
            'Proptas' == $vName
        );

        JHtmlSidebar::addEntry(
            JText::_('COM_SIAK_SIDE_MENU_BERKTAS'),
            'index.php?option=com_siak&view=brektas',
            'Berktas' == $vName
        );

        JHtmlSidebar::addEntry(
            JText::_('COM_SIAK_SIDE_MENU_TAS'),
            'index.php?option=com_siak&view=Tas',
            'Tas' == $vName
        );

        JHtmlSidebar::addEntry(
            JText::_('COM_SIAK_SIDE_MENU_SIDANGTA'),
            'index.php?option=com_siak&view=sidangs',
            'Sidangs' == $vName
        );


        JHtmlSidebar::addEntry(
            JText::_('COM_SIAK_SIDE_MENU_NILAIS'),
            'index.php?option=com_siak&view=nilais',
            'Nilais' == $vName
        );

        JHtmlSidebar::addEntry(
            JText::_('COM_SIAK_SIDE_MENU_SPS'),
            'index.php?option=com_siak&view=sps',
            'Sps' == $vName
        );

        */
    }
}
