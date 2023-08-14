<?php 
require_once __DIR__.'/vendor/autoload.php';
$mpdf = new \Mpdf\Mpdf([
    'default_font_size'=>16,
    'default_font'=>'sarabun'
]);
$mpdf->WriteHTML('<div>สวัสดี</div>');
$mpdf->Output();
?>