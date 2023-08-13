<?php 
header("Access-Control-Allow-Origin: *");

require_once('tcpdf.php');

$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8');
$pdf->AddPage();
$pdf->Ln(10);
$pdf->SetFont('thsarabun', 'B', 16);
$pdf->writeHTML($htmlcontent, false, 0, true, 0);
$pdf->Ln(3);
$pdf->SetFont('thsarabun', '', 15);
$pdf->Cell(170, 0, 'ส่วนราชการ    งานทะเบียนและประมวลผล', 0, 1, 'L', 0, '', 0);
$pdf->Cell(170, 0, 'ที่.............................วันที่  ', 0, 1, 'L', 0, '', 0);
$pdf->lastPage();
$pdf->Output('postpone.pdf', 'I');
?>