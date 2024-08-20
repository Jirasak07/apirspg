<?php
require_once __DIR__ . '/vendor/autoload.php';
$mpdf = new \Mpdf\Mpdf([
    'default_font_size' => 15,
    'default_font' => 'sarabun',
    'default_font_color' => 'red',
]);
$mpdf->AddPage();
$mpdf->SetFont('sarabun', 'B', 15);
$mpdf->WriteCell(20, 0, 'สวัสดีครับ', 0, 0, 'L');
$mpdf->Output();