<?php

header("Access-Control-Allow-Origin: *");

require_once('tcpdf.php');
$pdf_file_top = './9/as-1.pdf';
$pdf_file_bottom1 = './9/as-3.pdf';
$pdf_file_bottom2 = './9/as-4.pdf';

$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8');

$pdf->SetMargins(10, 10, 10);
$pdf->AddPage();
$pdf_data_top = file_get_contents($pdf_file_top);
$pdf->writeHTML($pdf_data_top, true, false, true, false, '');

// เริ่มหน้าใหม่สำหรับไฟล์ PDF บน
$pdf->AddPage();
$pdf_data_top = file_get_contents($pdf_file_top);
$pdf->writeHTML($pdf_data_top, true, false, true, false, '');

// เพิ่มหน้าสำหรับไฟล์ PDF ล่าง 1
$pdf->AddPage();
$pdf_data_bottom1 = file_get_contents($pdf_file_bottom1);
$pdf->writeHTML($pdf_data_bottom1, true, false, true, false, '');

// เพิ่มหน้าสำหรับไฟล์ PDF ล่าง 2
$pdf->AddPage();
$pdf_data_bottom2 = file_get_contents($pdf_file_bottom2);
$pdf->writeHTML($pdf_data_bottom2, true, false, true, false, '');

$pdf->Output('example.pdf', 'I');
?>