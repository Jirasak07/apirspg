<?php
require_once __DIR__ . '/vendor/autoload.php';

$api_url = 'https://rspg-kpppao.com/backend/Plant/Print/?id=1';
$response = file_get_contents($api_url);
$data = json_decode($response, true); // แปลง JSON เป็น array

$mpdf = new \Mpdf\Mpdf([
    'default_font_size' => 16,
    'default_font' => 'sarabun',
    'default_font_color'=>'red',
]);

$pagecount2 = $mpdf->setSourceFile('as-1.pdf');
$tplIds = $mpdf->importPage($pagecount2);
$mpdf->useImportedPage($tplIds);
$mpdf->AddPage();
$pagecount = $mpdf->setSourceFile('as-2.pdf');
$tplId = $mpdf->importPage($pagecount);
$mpdf->useTemplate($tplId);
$mpdf->Ln(50);
$mpdf->SetX(50);
$mpdf->SetTextColor('#03045e'); // สีน้ำเงิน (RGB: 0, 0, 255)
$mpdf->WriteCell(20, 0, $data[0]['plant_name'], 0, 0, 'L');
$mpdf->SetX(115);
$mpdf->WriteCell(20, 0, $data[0]['plant_code'], 0, 1, 'L');
$mpdf->Ln(6);
$mpdf->SetX(57);
$mpdf->WriteCell(20, 0, $data[0]['plant_character'], 0, 0, 'L');
$distinctive = $data[0]['distinctive'];
if (mb_strlen($distinctive, 'utf-8') > 85) {
    $sub = mb_substr($distinctive, 0, 70, 'utf-8');
    $mpdf->Ln(6);
    $mpdf->SetX(70);
    $mpdf->WriteCell(20, 0, $sub, 0, 0, 'L');

    if (mb_strlen($distinctive, 'utf-8') > 100) {
        $sub = mb_substr($distinctive, 70, 95, 'utf-8');
        $mpdf->Ln(6);
        $mpdf->SetX(37);
        $mpdf->WriteCell(20, 0, $sub, 0, 0, 'L');
        if (mb_strlen($distinctive, 'utf-8') > 110) {
            $sub = mb_substr($distinctive, 95, 95, 'utf-8');
            $mpdf->Ln(6);
            $mpdf->SetX(37);
            $mpdf->WriteCell(20, 0, $sub, 0, 0, 'L');
        }
        if (mb_strlen($distinctive, 'utf-8') > 150) {
            $sub = mb_substr($distinctive, 180, 95, 'utf-8');
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
$mpdf->AddPage();
$pagecount3 = $mpdf->setSourceFile('as-3.pdf');
$tplId3 = $mpdf->importPage($pagecount3);
$mpdf->useImportedPage($tplId3);
$mpdf->AddPage();
$pagecount3 = $mpdf->setSourceFile('as-4.pdf');
$tplId3 = $mpdf->importPage($pagecount3);
$mpdf->useImportedPage($tplId3);

$mpdf->Output();
