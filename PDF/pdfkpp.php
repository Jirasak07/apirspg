<?php
require_once('path_to_tcpdf/tcpdf.php');

$pdf = new ();
$pdf->SetMargins(10, 10, 10);
$pdf->AddPage();
$pdf->SetFont('dejavusans', '', 12);
$pdf->Cell(0, 10, 'Hello, this is a PDF created using TCPDF!', 0, 1, 'C');
$pdf->Output('example.pdf', 'I');
?>