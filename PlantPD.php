<?php
require_once __DIR__ . '/vendor/autoload.php';
if(isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = $_GET['id'];

    // ทำตามการประมวลผลที่ต้องการกับ $id ที่ถูกกรองแล้ว
} else {
    // กรณีไม่ได้รับ id ที่ถูกต้องหรือไม่มี id
    // ให้รับมือกับสถานการณ์นี้อย่างเหมาะสม
    $id = 0;
}
$api_url = 'https://rspg-kpppao.com/backend/Plant/Print/?id='.$id;
$response = file_get_contents($api_url);
$data = json_decode($response, true); // แปลง JSON เป็น array

$mpdf = new \Mpdf\Mpdf([
    'default_font_size' => 15,
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
$mpdf->SetFont('sarabun','B',15);
$mpdf->SetTextColor('#3f37c9'); // สีน้ำเงิน (RGB: 0, 0, 255)
$mpdf->WriteCell(20, 0, $data[0]['plant_name'], 0, 0, 'L');
$mpdf->SetX(115);
$mpdf->WriteCell(20, 0, $data[0]['plant_code'], 0, 1, 'L');
$mpdf->Ln(6);
$mpdf->SetX(57);
$mpdf->WriteCell(20, 0, $data[0]['plant_character'], 0, 0, 'L');
$distinctive = $data[0]['distinctive'];
if (mb_strlen($distinctive, 'utf-8') > 85) {
    $sub = mb_substr($distinctive, 0, 70, 'utf-8');
    $mpdf->Ln(6.5);
    $mpdf->SetX(70);
    $mpdf->WriteCell(20, 0, $sub, 0, 0, 'L');
    if (mb_strlen($distinctive, 'utf-8') > 60) {
        $sub = mb_substr($distinctive, 70,85, 'utf-8');
        $mpdf->Ln(6);
        $mpdf->SetX(37);
        $mpdf->WriteCell(20, 0, $sub, 0, 0, 'L');
        if (mb_strlen($distinctive, 'utf-8') > 110) {
            $sub = mb_substr($distinctive, 155, 95, 'utf-8');
            $mpdf->Ln(6);
            $mpdf->SetX(37);
            $mpdf->WriteCell(20, 0, $sub, 0, 0, 'L');
        }
        if (mb_strlen($distinctive, 'utf-8') > 150) {
            $sub = mb_substr($distinctive, 250, 95, 'utf-8');
            $mpdf->Ln(6);
            $mpdf->SetX(37);
            $mpdf->WriteCell(20, 0, $sub, 0, 0, 'L');
        }
    }
    $mpdf->Ln(7);
    $mpdf->SetX(60);
    $sub = mb_substr($data[0]['area'], 0, 75, 'utf-8');
    $mpdf->WriteCell(20, 0, $sub, 0, 0, 'L');
    $mpdf->Ln(7);
    $mpdf->SetX(100);
    $mpdf->WriteCell(20, 0, $data[0]['lacate_x'], 0, 0, 'L');
    $mpdf->SetX(140);
    $mpdf->WriteCell(20, 0, $data[0]['locate_y'], 0, 0, 'L');
    $mpdf->Ln(7);
    $mpdf->SetX(60);
    $mpdf->WriteCell(20, 0, $data[0]['tumbol'], 0, 0, 'L');
    $mpdf->SetX(105);
    $mpdf->WriteCell(20, 0, $data[0]['amphur'], 0, 0, 'L');
    $mpdf->SetX(145);
    $mpdf->WriteCell(20, 0, $data[0]['province'], 0, 0, 'L');
    $mpdf->Ln(6);
    $mpdf->SetX(65);
    $age = $data[0]['age'];
    $ageAsString = strval($age);
    $mpdf->WriteCell(20, 0, $ageAsString , 0, 0, 'L');
    $mpdf->SetX(120);
    $age = $data[0]['girth'];
    $ageAsString = strval($age);
    $mpdf->WriteCell(20, 0, $ageAsString , 0, 0, 'L');
    $mpdf->SetX(160);
    $age = $data[0]['height'];
    $ageAsString = strval($age);
    $mpdf->WriteCell(20, 0, $ageAsString , 0, 0, 'L');
    $mpdf->Ln(6);
    $mpdf->SetX(60);
    $mpdf->WriteCell(20, 0, $data[0]['statuss'], 0, 0, 'L');
    $mpdf->SetX(170);
    $age = $data[0]['qty'];
    $ageAsString = strval($age);
    $mpdf->WriteCell(20, 0, $ageAsString , 0, 0, 'L');
    $mpdf->Ln(24);
    // $mpdf->WriteCell(20, 0, $data[0]['benefit_foot'], 0, 0, 'L');
    $distinctive = $data[0]['benefit_foot'];
    if (mb_strlen($distinctive, 'utf-8') > 60) {
        $sub = mb_substr($distinctive, 0,90, 'utf-8');
        $mpdf->SetX(37);
        $mpdf->WriteCell(20, 0, $sub, 0, 0, 'L');
        if(mb_strlen($distinctive, 'utf-8') > 90){
            $sub = mb_substr($distinctive, 90,90, 'utf-8');
            $mpdf->Ln(6);
            $mpdf->SetX(37);
            $mpdf->WriteCell(20, 0, $sub, 0, 0, 'L');
            if(mb_strlen($distinctive, 'utf-8') > 180){
                $sub = mb_substr($distinctive, 180,90, 'utf-8');
                $mpdf->Ln(6);
                $mpdf->SetX(37);
                $mpdf->WriteCell(20, 0, $sub, 0, 0, 'L');
            }
        }
    }
    $mpdf->Ln(13);
    $distinctive = $data[0]['benefit_medicine_human'];
    if (mb_strlen($distinctive, 'utf-8') > 60) {
        $sub = mb_substr($distinctive, 0,90, 'utf-8');
        $mpdf->SetX(37);
        $mpdf->WriteCell(20, 0, $sub, 0, 0, 'L');
        if(mb_strlen($distinctive, 'utf-8') > 90){
            $sub = mb_substr($distinctive, 90,90, 'utf-8');
            $mpdf->Ln(6);
            $mpdf->SetX(37);
            $mpdf->WriteCell(20, 0, $sub, 0, 0, 'L');
            if(mb_strlen($distinctive, 'utf-8') > 180){
                $sub = mb_substr($distinctive, 180,90, 'utf-8');
                $mpdf->Ln(6);
                $mpdf->SetX(37);
                $mpdf->WriteCell(20, 0, $sub, 0, 0, 'L');
            }
        }
    }
    $mpdf->Ln(13);
    $distinctive = $data[0]['benefit_medicine_animal'];
    if (mb_strlen($distinctive, 'utf-8') > 60) {
        $sub = mb_substr($distinctive, 0,90, 'utf-8');
        $mpdf->SetX(37);
        $mpdf->WriteCell(20, 0, $sub, 0, 0, 'L');
        if(mb_strlen($distinctive, 'utf-8') > 90){
            $sub = mb_substr($distinctive, 90,90, 'utf-8');
            $mpdf->Ln(6);
            $mpdf->SetX(37);
            $mpdf->WriteCell(20, 0, $sub, 0, 0, 'L');
            if(mb_strlen($distinctive, 'utf-8') > 180){
                $sub = mb_substr($distinctive, 180,90, 'utf-8');
                $mpdf->Ln(6);
                $mpdf->SetX(37);
                $mpdf->WriteCell(20, 0, $sub, 0, 0, 'L');
            }
        }
    }
    $mpdf->Ln(12.5);
    $distinctive = $data[0]['benefit_appliances'];
    if (mb_strlen($distinctive, 'utf-8') > 60) {
        $sub = mb_substr($distinctive, 0,90, 'utf-8');
        $mpdf->SetX(37);
        $mpdf->WriteCell(20, 0, $sub, 0, 0, 'L');
        if(mb_strlen($distinctive, 'utf-8') > 90){
            $sub = mb_substr($distinctive, 90,90, 'utf-8');
            $mpdf->Ln(6);
            $mpdf->SetX(37);
            $mpdf->WriteCell(20, 0, $sub, 0, 0, 'L');
            if(mb_strlen($distinctive, 'utf-8') > 180){
                $sub = mb_substr($distinctive, 180,90, 'utf-8');
                $mpdf->Ln(6);
                $mpdf->SetX(37);
                $mpdf->WriteCell(20, 0, $sub, 0, 0, 'L');
            }
        }
    }
    $mpdf->Ln(12.5);
    $distinctive = $data[0]['benefit_pesticide'];
    if (mb_strlen($distinctive, 'utf-8') > 60) {
        $sub = mb_substr($distinctive, 0,90, 'utf-8');
        $mpdf->SetX(37);
        $mpdf->WriteCell(20, 0, $sub, 0, 0, 'L');
        if(mb_strlen($distinctive, 'utf-8') > 90){
            $sub = mb_substr($distinctive, 90,90, 'utf-8');
            $mpdf->Ln(6);
            $mpdf->SetX(37);
            $mpdf->WriteCell(20, 0, $sub, 0, 0, 'L');
            if(mb_strlen($distinctive, 'utf-8') > 180){
                $sub = mb_substr($distinctive, 180,90, 'utf-8');
                $mpdf->Ln(6);
                $mpdf->SetX(37);
                $mpdf->WriteCell(20, 0, $sub, 0, 0, 'L');
            }
        }
    }
}

// $mpdf->WriteHTML($table);
$mpdf->AddPage();
$pagecount3 = $mpdf->setSourceFile('as-3.pdf');
$tplId3 = $mpdf->importPage($pagecount3);
$mpdf->useTemplate($tplId3);
$mpdf->Ln(17.5);
$distinctive = $data[0]['about_tradition'];
if (mb_strlen($distinctive, 'utf-8') > 60) {
    $sub = mb_substr($distinctive, 0,90, 'utf-8');
    $mpdf->SetX(37);
    $mpdf->WriteCell(20, 0, $sub, 0, 0, 'L');
    if(mb_strlen($distinctive, 'utf-8') > 90){
        $sub = mb_substr($distinctive, 90,90, 'utf-8');
        $mpdf->Ln(6);
        $mpdf->SetX(37);
        $mpdf->WriteCell(20, 0, $sub, 0, 0, 'L');
        if(mb_strlen($distinctive, 'utf-8') > 180){
            $sub = mb_substr($distinctive, 180,90, 'utf-8');
            $mpdf->Ln(6);
            $mpdf->SetX(37);
            $mpdf->WriteCell(20, 0, $sub, 0, 0, 'L');
        }
    }
}
$mpdf->Ln(13);
$distinctive = $data[0]['about_religion'];
if (mb_strlen($distinctive, 'utf-8') > 60) {
    $sub = mb_substr($distinctive, 0,90, 'utf-8');
    $mpdf->SetX(37);
    $mpdf->WriteCell(20, 0, $sub, 0, 0, 'L');
    if(mb_strlen($distinctive, 'utf-8') > 90){
        $sub = mb_substr($distinctive, 90,90, 'utf-8');
        $mpdf->Ln(6);
        $mpdf->SetX(37);
        $mpdf->WriteCell(20, 0, $sub, 0, 0, 'L');
        if(mb_strlen($distinctive, 'utf-8') > 180){
            $sub = mb_substr($distinctive, 180,90, 'utf-8');
            $mpdf->Ln(6);
            $mpdf->SetX(37);
            $mpdf->WriteCell(20, 0, $sub, 0, 0, 'L');
        }
    }
}
$mpdf->Ln(13);
$distinctive = $data[0]['other'];
if (mb_strlen($distinctive, 'utf-8') > 60) {
    $sub = mb_substr($distinctive, 0,90, 'utf-8');
    $mpdf->SetX(37);
    $mpdf->WriteCell(20, 0, $sub, 0, 0, 'L');
    if(mb_strlen($distinctive, 'utf-8') > 90){
        $sub = mb_substr($distinctive, 90,90, 'utf-8');
        $mpdf->Ln(6);
        $mpdf->SetX(37);
        $mpdf->WriteCell(20, 0, $sub, 0, 0, 'L');
        if(mb_strlen($distinctive, 'utf-8') > 180){
            $sub = mb_substr($distinctive, 180,90, 'utf-8');
            $mpdf->Ln(6);
            $mpdf->SetX(37);
            $mpdf->WriteCell(20, 0, $sub, 0, 0, 'L');
        }
    }
}
$mpdf->Ln(13);
$mpdf->SetX(60);
$mpdf->WriteCell(20, 0, $data[0]['name_adder'], 0, 0, 'L');
$mpdf->SetX(170);
$age = $data[0]['age_adder'];
$ageAsString = strval($age);
$mpdf->WriteCell(20, 0, $ageAsString , 0, 0, 'L');
$mpdf->Ln(6);
$distinctive = $data[0]['address_adder'];
if (mb_strlen($distinctive, 'utf-8') > 60) {
    $sub = mb_substr($distinctive, 0,90, 'utf-8');
    $mpdf->SetX(37);
    $mpdf->WriteCell(20, 0, $sub, 0, 0, 'L');
    if(mb_strlen($distinctive, 'utf-8') > 90){
        $sub = mb_substr($distinctive, 90,90, 'utf-8');
        $mpdf->Ln(6);
        $mpdf->SetX(30);
        $mpdf->WriteCell(20, 0, $sub, 0, 0, 'L');
    }
}else{
    $mpdf->SetX(37);
    $mpdf->WriteCell(20, 0,$data[0]['address_adder'], 0, 0, 'L');
}
$mpdf->AddPage();
$pagecount3 = $mpdf->setSourceFile('as-4.pdf');
$tplId3 = $mpdf->importPage($pagecount3);
$mpdf->useImportedPage($tplId3);

$mpdf->Output();
