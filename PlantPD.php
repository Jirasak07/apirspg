<?php 
require_once __DIR__.'/vendor/autoload.php';
$mpdf = new \Mpdf\Mpdf();
$mpdf->WriteHTML('Hello World สวัสดีครับ');

$mpdf->Output();
?>