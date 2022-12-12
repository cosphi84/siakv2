<?php

defined('_JEXEC') or exit;
jimport('tcpdf.tcpdf');

class SiakViewNilais extends JViewLegacy
{
    public function display($tpl = null)
    {
        $app = JFactory::getApplication();
        if (!class_exists('TCPDF')) {
            $app->enqueueMessage(JText::sprintf('COM_SIAK_ERROR_LIBRARY_NOT_FOUND', 'TCPDF'), 'error');
            $app->redirect(JRoute::_('index.php?option=com_siak&view=nilais'));

            return false;
        }
        $id = $app->input->get('id', 0, 'int');
        $data = $this->getNilaiMahasiswa($id);
        $params = JComponentHelper::getParams('com_siak');

        $errors = $this->get('Errors');
        if (count($errors) > 0) {
            throw new Exception(implode('\n', $errors), 500);

            return false;
        }

        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $pdf->SetCreator('SIAK '.$params->get('universitas'));
        $pdf->SetAuthor($params->get('fakultas'));
        $pdf->SetTitle('Transkrip Nilai');
        $pdf->SetSubject('Transkrip Nilai');
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
        $pdf->SetMargins(0, 0, 0);
        $pdf->SetAutoPageBreak(true, PDF_MARGIN_BOTTOM);
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

        if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
            require_once dirname(__FILE__).'/lang/eng.php';
            $pdf->setLanguageArray($l);
        }

        $pdf->SetFont('helvetica', '', 10);
        $pdf->AddPage();

        $logo = JPATH_ROOT.'/media/com_siak/images/untag-transparent.jpg';
        $pdf->Image($logo, 10, 10, 20, 20);
        $pdf->SetXY(40, 13);
        $pdf->Cell(100, 0, strtoupper($params['universitas']), 0, 1, 'L', 0, '', 0);
        $pdf->SetXY(40, 18);
        $pdf->Cell(100, 0, strtoupper($params['fakultas']), 0, 1, 'L', 0, '', 0);
        $pdf->Line(40, 25, 115, 25, ['width' => 0.5, 'cap' => 'square', 'join' => 'mitter', 'dash' => 0]);

        $pdf->SetXY(71, 35);
        $pdf->SetFont('helvetica', 'B', '14');
        $pdf->Cell(76, 0, 'Transkrip Nilai', 0, 1, 'C', 0, '', 0);
    }
}
