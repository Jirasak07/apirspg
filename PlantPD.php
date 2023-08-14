<?php 
require_once __DIR__.'/vendor/autoload.php';
$mpdf = new \Mpdf\Mpdf([
    'default_font_size'=>16,
    'default_font'=>'Sarabun'
]);
$html = '<div style="color:red;text-align:center" >
<div>Hello World สวัสดีครับ</div></div>';
$mpdf->WriteHTML($html);
// $mpdf->WriteCell(0,0,'sdsdsdsd',0,0,'C');

$mpdf->Output();
?>