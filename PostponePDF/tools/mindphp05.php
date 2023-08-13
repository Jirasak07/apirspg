<?php
require_once('tcpdf.php');

$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8');

$pdf->SetCreator('Mindphp');
$pdf->SetAuthor('Mindphp Developer');
$pdf->SetTitle('Mindphp Example 05');
$pdf->SetSubject('Mindphp Example');
$pdf->SetKeywords('Mindphp, TCPDF, PDF, example, guide');

$pdf->setHeaderFont(array('thsarabun', 'B', 12));
$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, 'Mindphp Example 05', 'การเพิ่มตัวอักษร Font (ฟอนต์) และการนำไปใช้', array (0, 64, 255), array (0, 64, 128));
$pdf->setFooterData(array (0, 64, 0), array (0, 64, 128));

$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
$pdf->AddPage();

$pdf->SetFont('thsarabun', '', 20);
$pdf->Cell('', '', 'ยินดีต้อนรับสู่ MINDPHP.COM', 0, 0, 'C', false, 'http://www.mindphp.com');

$pdf->Output('mindphp05.pdf', 'I');