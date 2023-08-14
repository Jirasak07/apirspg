<?php 
require_once __DIR__.'/vendor/autoload.php';
$mpdf = new \Mpdf\Mpdf([
    'default_font_size'=>16,
    'default_font'=>'sarabun'
]);

$pagecount = $mpdf->setSourceFile('/as-2.pdf');
$tplId = $mpdf->importPage($pagecount);
$mpdf->useTemplate($tplId);
$mpdf->Output();
?>