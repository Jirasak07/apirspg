<?php 
require_once __DIR__.'/vendor/autoload.php';



$api_url = 'https://rspg-kpppao.com/backend/Plant/Print/?id=1';
$response = file_get_contents($api_url);
$data = json_decode($response, true); // แปลง JSON เป็น array

$mpdf = new \Mpdf\Mpdf([
    'default_font_size'=>14,
    'default_font'=>'sarabun'
]);
$pagecount = $mpdf->setSourceFile('as-2.pdf');
$tplId = $mpdf->importPage($pagecount);
$mpdf->useTemplate($tplId);
$mpdf->Ln(50);
$mpdf->SetX(50);
$mpdf->WriteCell(20,0,$data[0]['plant_name'],0,0,'L');
$mpdf->SetX(115);
$mpdf->WriteCell(20,0,$data[0]['plant_code'],0,1,'L');
$mpdf->Ln(6);
$mpdf->SetX(57);
$mpdf->WriteCell(20,0,$data[0]['plant_character'],0,0,'L');
$distinctive = $data[0]['distinctive'];
if (mb_strlen($distinctive, 'utf-8') > 85) {
    $sub = mb_substr($distinctive,0,85,'utf-8');
    $mpdf->Ln(6);
    $mpdf->SetX(70);
    $mpdf->WriteCell(20, 0, $sub, 0, 0, 'L');

    if(mb_strlen($distinctive, 'utf-8') > 100){
        $sub = mb_substr($distinctive,86,110,'utf-8');
        $mpdf->Ln(6);
        $mpdf->SetX(37);
        $mpdf->WriteCell(20, 0, $sub, 0, 0, 'L');
        if(mb_strlen($distinctive, 'utf-8') > 110){
            $sub = mb_substr($distinctive,110,110,'utf-8');
            $mpdf->Ln(6);
            $mpdf->SetX(37);
         $mpdf->WriteCell(20, 0, $sub, 0, 0, 'L');
        }
        if(mb_strlen($distinctive, 'utf-8') > 150){
            $sub = mb_substr($distinctive,220,110,'utf-8');
            $mpdf->Ln(6);
            $mpdf->SetX(37);
         $mpdf->WriteCell(20, 0, $sub, 0, 0, 'L');
        }
    }
    $mpdf->Ln(7);
    $mpdf->SetX(60);
    $mpdf->WriteCell(20, 0, $data[0]['area'], 0, 0, 'L');
    $mpdf->Ln(7);
    $mpdf->SetX(100);
    $mpdf->WriteCell(20, 0, $data[0]['lacate_x'], 0, 0, 'L');
    $mpdf->SetX(140);
    $mpdf->WriteCell(20, 0, $data[0]['locate_y'], 0, 0, 'L');
}


// $mpdf->WriteHTML($table);
$mpdf->Output();
?>