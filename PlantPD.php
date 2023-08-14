<?php 
require_once __DIR__.'/vendor/autoload.php';
$mpdf = new \Mpdf\Mpdf([
    'default_font_size'=>16,
    'default_font'=>'sarabun'
]);
<<<<<<< Updated upstream
$mpdf->SetImportUse(); // only with mPDF <8.0
$mpdf->SetDocTemplate('/as-2.pdf',true);
=======
$html = '<div style="text-align:center;" >Hello World สวัสดีครับ</div>';
$mpdf->SetImportUse();
$mpdf->SetDocTemplate('as-2.pdf',true);
$mpdf->WriteHTML($html);
>>>>>>> Stashed changes
$mpdf->Output();
?>