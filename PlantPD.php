<?php 
require_once __DIR__.'/vendor/autoload.php';
$mpdf = new \Mpdf\Mpdf([
    'default_font_size'=>16,
    'default_font'=>'sarabun'
]);
$html = '<div style="text-align:center" >Hello World สวัสดีครับ</div>';
$mpdf->SetTmportUse();
$mpdf->SetDocTemplate('as-2.pdf',true);
$mpdf->WriteHTML($html);
$mpdf->Output();
?>