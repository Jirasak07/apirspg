<?php
require_once __DIR__ . '/vendor/autoload.php';
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = $_GET['id'];

    // ทำตามการประมวลผลที่ต้องการกับ $id ที่ถูกกรองแล้ว
} else {
    // กรณีไม่ได้รับ id ที่ถูกต้องหรือไม่มี id
    // ให้รับมือกับสถานการณ์นี้อย่างเหมาะสม
    $id = 0;
}
// $api_url = 'https://rspg-kpppao.com/backend/Plant/Print/?id=' . $id;
// $api_url2 = 'https://rspg-kpppao.com/backend/Plant/ShowImagePdf/?id=' . $id;
$api_url = 'http://localhost:8080/apirspg/Plant/Print/?id=' . $id;
$api_url2 = 'http://localhost:8080/apirspg/Plant/ShowImagePdf/?id=' . $id;
$response = file_get_contents($api_url);
$data = json_decode($response, true); // แปลง JSON เป็น array
$response2 = file_get_contents($api_url2);
$data2 = json_decode($response2, true); // แปลง JSON เป็น array

$mpdf = new \Mpdf\Mpdf([
    'default_font_size' => 15,
    'default_font' => 'sarabun',
    'default_font_color' => 'red',
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
$mpdf->SetFont('sarabun', 'B', 15);
$mpdf->SetTextColor('#3f37c9'); // สีน้ำเงิน (RGB: 0, 0, 255)
$mpdf->WriteCell(20, 0, $data[0]['plant_name'], 0, 0, 'L');
$mpdf->SetX(115);
$mpdf->WriteCell(20, 0, $data[0]['plant_code'], 0, 1, 'L');
$mpdf->Ln(6);
$mpdf->SetX(57);
$mpdf->WriteCell(20, 0, mb_substr($data[0]['plant_character'], 0, 85, 'utf-8'), 0, 0, 'L');
$distinctive = $data[0]['distinctive'];
if (mb_strlen($distinctive, 'utf-8') > 85) {
    $sub = mb_substr($distinctive, 0, 75, 'utf-8');
    $mpdf->Ln(6.5);
    $mpdf->SetX(70);
    $mpdf->WriteCell(20, 0, $sub, 0, 0, 'L');
    if (mb_strlen($distinctive, 'utf-8') > 60) {
        $sub = mb_substr($distinctive, 75, 95, 'utf-8');
        $mpdf->Ln(6);
        $mpdf->SetX(37);
        $mpdf->WriteCell(20, 0, $sub, 0, 0, 'L');
        if (mb_strlen($distinctive, 'utf-8') > 110) {
            $sub = mb_substr($distinctive, 165, 95, 'utf-8');
            $mpdf->Ln(6);
            $mpdf->SetX(37);
            $mpdf->WriteCell(20, 0, $sub, 0, 0, 'L');
        }
        if (mb_strlen($distinctive, 'utf-8') > 150) {
            $sub = mb_substr($distinctive, 255, 90, 'utf-8');
            $mpdf->Ln(6);
            $mpdf->SetX(37);
            $mpdf->WriteCell(20, 0, $sub, 0, 0, 'L');
        }
    }
} else {
    $mpdf->WriteCell(20, 0, $distinctive, 0, 0, 'L');
}
$mpdf->Ln(7);
$mpdf->SetX(60);
$sub = mb_substr($data[0]['area'], 0, 75, 'utf-8');
$mpdf->WriteCell(20, 0, $sub, 0, 0, 'L');
$mpdf->Ln(7);
$mpdf->SetX(100);
$mpdf->WriteCell(20, 0, $data[0]['locate_x'], 0, 0, 'L');
$mpdf->SetX(140);
$mpdf->WriteCell(20, 0, $data[0]['locate_y'], 0, 0, 'L');
$mpdf->Ln(7);
$mpdf->SetX(60);
$mpdf->WriteCell(20, 0, $data[0]['tumbol'], 0, 0, 'L');
$mpdf->SetX(105);
$mpdf->WriteCell(20, 0, $data[0]['amphure'], 0, 0, 'L');
$mpdf->SetX(145);
$mpdf->WriteCell(20, 0, $data[0]['province'], 0, 0, 'L');
$mpdf->Ln(6);
$mpdf->SetX(65);
$age = $data[0]['age'];
$ageAsString = strval($age);
$mpdf->WriteCell(20, 0, $ageAsString, 0, 0, 'L');
$mpdf->SetX(120);
$age = $data[0]['girth'];
$ageAsString = strval($age);
$mpdf->WriteCell(20, 0, $ageAsString, 0, 0, 'L');
$mpdf->SetX(160);
$age = $data[0]['height'];
$ageAsString = strval($age);
$mpdf->WriteCell(20, 0, $ageAsString, 0, 0, 'L');
$mpdf->Ln(6);
$mpdf->SetX(60);
$mpdf->WriteCell(20, 0, $data[0]['status'], 0, 0, 'L');
$mpdf->SetX(170);
$age = $data[0]['qty'];
$ageAsString = strval($age);
$mpdf->WriteCell(20, 0, $ageAsString, 0, 0, 'L');
$mpdf->Ln(24);
// $mpdf->WriteCell(20, 0, $data[0]['benefit_foot'], 0, 0, 'L');
$distinctive = $data[0]['benefit_food'];
if (mb_strlen($distinctive, 'utf-8') > 60) {
    $sub = mb_substr($distinctive, 0, 90, 'utf-8');
    $mpdf->SetX(37);
    $mpdf->WriteCell(20, 0, $sub, 0, 0, 'L');
    if (mb_strlen($distinctive, 'utf-8') > 90) {
        $sub = mb_substr($distinctive, 90, 90, 'utf-8');
        $mpdf->Ln(6);
        $mpdf->SetX(37);
        $mpdf->WriteCell(20, 0, $sub, 0, 0, 'L');
        if (mb_strlen($distinctive, 'utf-8') > 180) {
            $sub = mb_substr($distinctive, 180, 90, 'utf-8');
            $mpdf->Ln(6);
            $mpdf->SetX(37);
            $mpdf->WriteCell(20, 0, $sub, 0, 0, 'L');
        }
    }
} else {
    $mpdf->SetX(37);
    $mpdf->WriteCell(20, 0, $data[0]['benefit_food'], 0, 0, 'L');
}
// $mpdf->Ln(13);
$mpdf->SetY(179);
// $html='<div style="position:relative;top:10cm;" ></div>';
// $mpdf->WriteHTML($html);
$distinctive = $data[0]['benefit_medicine_human'];
if (mb_strlen($distinctive, 'utf-8') > 60) {
    $sub = mb_substr($distinctive, 0, 90, 'utf-8');
    $mpdf->SetX(37);
    $mpdf->WriteCell(20, 0, $sub, 0, 0, 'L');
    if (mb_strlen($distinctive, 'utf-8') > 90) {
        $sub = mb_substr($distinctive, 90, 90, 'utf-8');
        $mpdf->Ln(6);
        $mpdf->SetX(37);
        $mpdf->WriteCell(20, 0, $sub, 0, 0, 'L');
        if (mb_strlen($distinctive, 'utf-8') > 180) {
            $sub = mb_substr($distinctive, 180, 90, 'utf-8');
            $mpdf->Ln(6);
            $mpdf->SetX(37);
            $mpdf->WriteCell(20, 0, $sub, 0, 0, 'L');
        }
    }
} else {
    // $mpdf->Ln(13);
    $mpdf->SetX(37);
    $mpdf->WriteCell(20, 0, $data[0]['benefit_medicine_human'], 0, 0, 'L');
}
$mpdf->SetY(204);
$distinctive = $data[0]['benefit_medicine_animal'];
if (mb_strlen($distinctive, 'utf-8') > 60) {
    $sub = mb_substr($distinctive, 0, 90, 'utf-8');
    $mpdf->SetX(37);
    $mpdf->WriteCell(20, 0, $sub, 0, 0, 'L');
    if (mb_strlen($distinctive, 'utf-8') > 90) {
        $sub = mb_substr($distinctive, 90, 90, 'utf-8');
        $mpdf->Ln(6);
        $mpdf->SetX(37);
        $mpdf->WriteCell(20, 0, $sub, 0, 0, 'L');
        if (mb_strlen($distinctive, 'utf-8') > 180) {
            $sub = mb_substr($distinctive, 180, 90, 'utf-8');
            $mpdf->Ln(6);
            $mpdf->SetX(37);
            $mpdf->WriteCell(20, 0, $sub, 0, 0, 'L');
        }
    }
} else {
    // $mpdf->Ln(14);
    $mpdf->SetX(37);
    $mpdf->WriteCell(20, 0, $data[0]['benefit_medicine_animal'], 0, 0, 'L');
}
$mpdf->SetY(228);
$distinctive = $data[0]['benefit_appliances'];
if (mb_strlen($distinctive, 'utf-8') > 60) {
    $sub = mb_substr($distinctive, 0, 90, 'utf-8');
    $mpdf->SetX(37);
    $mpdf->WriteCell(20, 0, $sub, 0, 0, 'L');
    if (mb_strlen($distinctive, 'utf-8') > 90) {
        $sub = mb_substr($distinctive, 90, 90, 'utf-8');
        $mpdf->Ln(6);
        $mpdf->SetX(37);
        $mpdf->WriteCell(20, 0, $sub, 0, 0, 'L');
        if (mb_strlen($distinctive, 'utf-8') > 180) {
            $sub = mb_substr($distinctive, 180, 90, 'utf-8');
            $mpdf->Ln(6);
            $mpdf->SetX(37);
            $mpdf->WriteCell(20, 0, $sub, 0, 0, 'L');
        }
    }
} else {
    $mpdf->SetX(37);
    $mpdf->WriteCell(20, 0, $data[0]['benefit_appliances'], 0, 0, 'L');
}
$mpdf->SetY(253);
$distinctive = $data[0]['benefit_pesticide'];
if (mb_strlen($distinctive, 'utf-8') > 60) {
    $sub = mb_substr($distinctive, 0, 90, 'utf-8');
    $mpdf->SetX(37);
    $mpdf->WriteCell(20, 0, $sub, 0, 0, 'L');
    if (mb_strlen($distinctive, 'utf-8') > 90) {
        $sub = mb_substr($distinctive, 90, 90, 'utf-8');
        $mpdf->Ln(6);
        $mpdf->SetX(37);
        $mpdf->WriteCell(20, 0, $sub, 0, 0, 'L');
        if (mb_strlen($distinctive, 'utf-8') > 180) {
            $sub = mb_substr($distinctive, 180, 90, 'utf-8');
            $mpdf->Ln(6);
            $mpdf->SetX(37);
            $mpdf->WriteCell(20, 0, $sub, 0, 0, 'L');
        }
    }
} else {
    $mpdf->SetX(37);
    $mpdf->WriteCell(20, 0, $data[0]['benefit_pesticide'], 0, 0, 'L');
}
// $mpdf->WriteHTML($table);
$mpdf->AddPage();
$pagecount3 = $mpdf->setSourceFile('as-3.pdf');
$tplId3 = $mpdf->importPage($pagecount3);
$mpdf->useTemplate($tplId3);
$mpdf->SetY(20);
$distinctive = $data[0]['about_tradition'];
if (mb_strlen($distinctive, 'utf-8') > 60) {
    $sub = mb_substr($distinctive, 0, 90, 'utf-8');
    $mpdf->SetX(37);
    $mpdf->WriteCell(20, 0, $sub, 0, 0, 'L');
    if (mb_strlen($distinctive, 'utf-8') > 90) {
        $sub = mb_substr($distinctive, 90, 90, 'utf-8');
        $mpdf->Ln(6);
        $mpdf->SetX(37);
        $mpdf->WriteCell(20, 0, $sub, 0, 0, 'L');
        if (mb_strlen($distinctive, 'utf-8') > 180) {
            $sub = mb_substr($distinctive, 180, 90, 'utf-8');
            $mpdf->Ln(6);
            $mpdf->SetX(37);
            $mpdf->WriteCell(20, 0, $sub, 0, 0, 'L');
        }
    }
} else {
    $mpdf->Ln(13);
    $mpdf->SetX(37);
    $mpdf->WriteCell(20, 0, $data[0]['about_tradition'], 0, 0, 'L');
}
$mpdf->SetY(45);
$distinctive = $data[0]['about_religion'];
if (mb_strlen($distinctive, 'utf-8') > 60) {
    $sub = mb_substr($distinctive, 0, 90, 'utf-8');
    $mpdf->SetX(37);
    $mpdf->WriteCell(20, 0, $sub, 0, 0, 'L');
    if (mb_strlen($distinctive, 'utf-8') > 90) {
        $sub = mb_substr($distinctive, 90, 90, 'utf-8');
        $mpdf->Ln(6);
        $mpdf->SetX(37);
        $mpdf->WriteCell(20, 0, $sub, 0, 0, 'L');
        if (mb_strlen($distinctive, 'utf-8') > 180) {
            $sub = mb_substr($distinctive, 180, 90, 'utf-8');
            $mpdf->Ln(6);
            $mpdf->SetX(37);
            $mpdf->WriteCell(20, 0, $sub, 0, 0, 'L');
        }
    }
} else {
    $mpdf->Ln(13);
    $mpdf->SetX(37);
    $mpdf->WriteCell(20, 0, $data[0]['about_religion'], 0, 0, 'L');
}
$mpdf->SetY(71.5);
$distinctive = $data[0]['other'];
if (mb_strlen($distinctive, 'utf-8') > 60) {
    $sub = mb_substr($distinctive, 0, 90, 'utf-8');
    $mpdf->SetX(37);
    $mpdf->WriteCell(20, 0, $sub, 0, 0, 'L');
    if (mb_strlen($distinctive, 'utf-8') > 90) {
        $sub = mb_substr($distinctive, 90, 90, 'utf-8');
        $mpdf->Ln(6);
        $mpdf->SetX(37);
        $mpdf->WriteCell(20, 0, $sub, 0, 0, 'L');
        if (mb_strlen($distinctive, 'utf-8') > 180) {
            $sub = mb_substr($distinctive, 180, 90, 'utf-8');
            $mpdf->Ln(6);
            $mpdf->SetX(37);
            $mpdf->WriteCell(20, 0, $sub, 0, 0, 'L');
        }
    }
} else {
    $mpdf->Ln(12);
    $mpdf->SetX(37);
    $mpdf->WriteCell(20, 0, $data[0]['other'], 0, 0, 'L');
}
// $mpdf->SetX(60);
$html = "<div style='position:fixed; top:8.9cm; left:4.5cm;color:#3f37c9;'>" . $data[0]['name_adder'] . "</div>";
$mpdf->WriteHTML($html);
// $mpdf->WriteCell(20, 0, $data[0]['name_adder'], 0, 0, 'L');
// $mpdf->SetX(170);
$mpdf->WriteHTML($html);
// $ageAsString = strval($age);
// $mpdf->WriteCell(20, 0, $ageAsString , 0, 0, 'L');
$mpdf->SetY(111.5);
if (mb_strlen($distinctive, 'utf-8') > 60) {
    $sub = mb_substr($distinctive, 0, 90, 'utf-8');
    $mpdf->SetX(37);
    $mpdf->WriteCell(20, 0, $sub, 0, 0, 'L');
    if (mb_strlen($distinctive, 'utf-8') > 90) {
        $sub = mb_substr($distinctive, 90, 90, 'utf-8');
        $mpdf->Ln(6);
        $mpdf->SetX(30);
        $mpdf->WriteCell(20, 0, $sub, 0, 0, 'L');
    }
} else {
    $mpdf->SetX(37);
}

$mpdf->AddPage();

$pagecount3 = $mpdf->setSourceFile('as-4.pdf');
$tplId3 = $mpdf->importPage($pagecount3);
$mpdf->useTemplate($tplId3);

$columns = 2; // จำนวนคอลัมน์
$rows = ceil(count($data2) / $columns); // คำนวณจำนวนแถว

$imageWidth = 50; // ความกว้างของภาพ
$imageHeight = 50; // ความสูงของภาพ
$marginX = 10; // ระยะห่างแนวนอนระหว่างภาพ
$marginY = 20; // ระยะห่างแนวตั้งระหว่างภาพและ label

$pageWidth = $mpdf->w - $mpdf->lMargin - $mpdf->rMargin; // ความกว้างของหน้า PDF
$pageHeight = $mpdf->h - $mpdf->tMargin - $mpdf->bMargin; // ความสูงของหน้า PDF

$totalGridWidth = ($columns * $imageWidth) + (($columns - 1) * $marginX);
$totalGridHeight = ($rows * $imageHeight) + (($rows - 1) * $marginY) + ($rows * 10); // เพิ่มความสูงของ label ด้วย

$startX = ($pageWidth - $totalGridWidth) / 2 + $mpdf->lMargin;
$startY = ($pageHeight - $totalGridHeight) / 2 + $mpdf->tMargin;

foreach ($data2 as $index => $d) {
    $col = $index % $columns; // คำนวณตำแหน่งคอลัมน์
    $row = floor($index / $columns); // คำนวณตำแหน่งแถว
    
    $posX = $startX + ($col * ($imageWidth + $marginX)); // คำนวณตำแหน่งแนวนอนของภาพ
    $posY = $startY + ($row * ($imageHeight + $marginY + 10)); // คำนวณตำแหน่งแนวตั้งของภาพ
    
    $mpdf->Image($d["image_name"], $posX, $posY, $imageWidth, $imageHeight, 'PNG');
    $mpdf->SetXY($posX, $posY + $imageHeight + 2); // ตั้งตำแหน่งของ label
    $mpdf->Cell($imageWidth, 10, $d["type_img"], 0, 0, 'C'); // เพิ่ม label ใต้ภาพ
}
$mpdf->Output();
