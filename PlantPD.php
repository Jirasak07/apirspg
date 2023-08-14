<?php 
require_once __DIR__.'/vendor/autoload.php';
$mpdf = new \Mpdf\Mpdf([
    'default_font_size'=>16,
    'default_font'=>'sarabun'
]);
$mpdf->SetImportUse(true); // only with mPDF <8.0
$mpdf->SetDocTemplate('/as-2.pdf',true);
$mpdf->Output();
?>