<?php
header("Access-Control-Allow-Origin: *");
require_once('tcpdf.php');
$pdf = new TCPDF();
$pdf->SetMargins(10, 10, 10);
$pdf->AddPage();
$pdf->SetFont('dejavusans', '', 12);
$pdf->Cell(0, 10, 'Hello, this is a PDF created using TCPDF!', 0, 1, 'C');
$pdf->Output('example.pdf', 'I');
