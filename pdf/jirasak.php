<?php
header("Access-Control-Allow-Origin: *");
require_once('tcpdf.php');
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8');
$pdf->SetFont('thsarabunb', 'B', 16);

//$pdf->writeHTML($datalistfinish,  false, 0, true, 0 );


$pdf->Output('tess.pdf', 'I');
