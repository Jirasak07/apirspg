<?php 
require_once __DIR__.'/vendor/autoload.php';



$api_url = 'https://rspg-kpppao.com/backend/Plant/Print/?id=1';
$response = file_get_contents($api_url);
$data = json_decode($response, true); // แปลง JSON เป็น array

$mpdf = new \Mpdf\Mpdf([
    'default_font_size'=>16,
    'default_font'=>'sarabun'
]);
$pagecount = $mpdf->setSourceFile('as-2.pdf');
$tplId = $mpdf->importPage($pagecount);
$mpdf->useTemplate($tplId);
foreach ($data as $item) {
$cell = "".$item['plant_name']."";
$mpdf->WriteCell(0,0,$cell);
}
$mpdf->WriteHTML($table);
$mpdf->Output();
?>