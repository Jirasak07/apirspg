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
$api_url = 'https://www.rspg-kpppao.com/apirspg/Plant/Print/?id=' . $id;
$api_url2 = 'https://www.rspg-kpppao.com/apirspg/Plant/ShowImagePdf/?id=' . $id;
// $api_url = 'https://www.rspg-kpppao.com/apirspg/Plant/Print/?id=11';
$response = file_get_contents($api_url);
$data = json_decode($response, true); // แปลง JSON เป็น array
// $api_url2 = 'https://www.rspg-kpppao.com/apirspg/Plant/ShowImagePdf/?id=11';
$response2 = file_get_contents($api_url2);
$data2 = json_decode($response2, true); // แปลง JSON เป็น array


$mpdf = new \Mpdf\Mpdf([
    'default_font_size' => 16,
    'default_font' => 'sarabun',
    'format' => [210, 297],
]);
$mpdf->AddPage();
$mpdf->SetY(28);
$mpdf->SetFont('sarabun', 'B', '16');
$mpdf->WriteCell(0, 0, 'ใบงานที่ 5', 0, 0, 'C', 0, '', 0);
$mpdf->Ln(8);
$mpdf->WriteCell(0, 0, 'เรื่อง การเก็บข้อมูลการใช้ประโยชน์ของพืชในท้องถิ่น', 0, 0, 'C', 0, '', 0);
$mpdf->Ln(12);
$mpdf->WriteCell(0, 0, 'ข้อมูลพืช', 0, 0, 'L', 0, '', 0);
$mpdf->Ln(8);
$mpdf->SetFont('sarabun', '', '16');
$mpdf->SetX(25);
$mpdf->WriteCell(0, 0, 'พืชที่มีความสคัญ หรือมีลักษณะพิเศษ เช่น พืชที่เป็นไม้ผล ผักพื้นเมือง พืชสมุนไพร พืชใช้เนื้อไม้ พืชเศรษฐกิจ ฯลฯ', 0, 0, 'L', 0, '', 0);
///////////////////////////////
$mpdf->Ln(8);
$mpdf->SetFont('sarabun', 'B', '16');
$mpdf->SetX(25);
$mpdf->WriteCell(15, 0, 'ชื่อพืช', 0, 0, 'L', 0, '', 0);
$mpdf->SetFont('sarabun', '', '16');
$mpdf->WriteCell(50, 0, $data[0]["plant_name"], 0, 0, 'L', 0, '', 0);
$mpdf->SetFont('sarabun', 'B', '16');
$mpdf->WriteCell(25, 0, 'รหัสพรรณไม้', 0, 0, 'L', 0, '', 0);
$mpdf->SetFont('sarabun', '', '16');
$mpdf->WriteCell(20, 0, $data[0]["plant_code"], 0, 0, 'L', 0, '', 0);
/////////////////////////////
$mpdf->Ln(8);
$mpdf->SetFont('sarabun', 'B', '16');
$mpdf->SetX(25);
$mpdf->WriteCell(20, 0, 'ลักษณะวิสัย', 0, 0, 'L', 0, '', 0);
$mpdf->SetFont('sarabun', '', '16');

$text = $data[0]['plant_character'];
$mpdf->Ln(6);
// การเขียนข้อความแบบ Multicell
$mpdf->SetX(25);

$mpdf->MultiCell(0, 7, $text, 0, 'L');
/////////////////////////////
$mpdf->Ln(8);
$mpdf->SetFont('sarabun', 'B', '16');
$mpdf->SetX(25);
$mpdf->WriteCell(35, 0, 'ลักษณะเด่นของพืช', 0, 0, 'L', 0, '', 0);
$mpdf->SetFont('sarabun', '', '16');

$text = $data[0]['distinctive'];

$mpdf->Ln(6);
// การเขียนข้อความแบบ Multicell
$mpdf->SetX(25);
$mpdf->MultiCell(0, 7, $text, 0, 'L');
/////////////////////////////
$mpdf->Ln(8);
$mpdf->SetFont('sarabun', 'B', '16');
$mpdf->SetX(25);
$mpdf->WriteCell(25, 0, 'บริเวณที่พบ', 0, 0, 'L', 0, '', 0);
$mpdf->SetFont('sarabun', '', '16');
$mpdf->WriteCell(50, 0, $data[0]['area'], 0, 0, 'L', 0, '', 0);
/////////////////////////////
$mpdf->Ln(8);
$mpdf->SetFont('sarabun', 'B', '16');
$mpdf->SetX(25);
$mpdf->WriteCell(60, 0, 'แสดงพิกัดตำแหน่งพรรณไม้(GIS)', 0, 0, 'L', 0, '', 0);
$mpdf->SetFont('sarabun', 'B', '16');
$mpdf->WriteCell(10, 0, 'X : ', 0, 0, 'L', 0, '', 0);
$mpdf->SetFont('sarabun', '', '16');
$mpdf->WriteCell(40, 0, $data[0]['locate_x'], 0, 0, 'L', 0, '', 0);
$mpdf->SetFont('sarabun', 'B', '16');
$mpdf->WriteCell(10, 0, 'Y : ', 0, 0, 'L', 0, '', 0);
$mpdf->SetFont('sarabun', '', '16');
$mpdf->WriteCell(10, 0, $data[0]['locate_y'], 0, 0, 'L', 0, '', 0);
/////////////////////////////
$mpdf->Ln(8);
$mpdf->SetFont('sarabun', 'B', '16');
$mpdf->SetX(25);
$mpdf->WriteCell(25, 0, 'ตำบล/แขวง', 0, 0, 'L', 0, '', 0);
$mpdf->SetFont('sarabun', '', '16');
$mpdf->WriteCell(25, 0, $data[0]['tumbol'], 0, 0, 'L', 0, '', 0);
$mpdf->SetFont('sarabun', 'B', '16');
$mpdf->WriteCell(15, 0, 'อำเภอ ', 0, 0, 'L', 0, '', 0);
$mpdf->SetFont('sarabun', '', '16');
$mpdf->WriteCell(38, 0, $data[0]['amphure'], 0, 0, 'L', 0, '', 0);
$mpdf->SetFont('sarabun', 'B', '16');
$mpdf->WriteCell(15, 0, 'จังหวัด', 0, 0, 'L', 0, '', 0);
$mpdf->SetFont('sarabun', '', '16');
$mpdf->WriteCell(10, 0, $data[0]['province'], 0, 0, 'L', 0, '', 0);
/////////////////////////////
$mpdf->Ln(8);
$mpdf->SetFont('sarabun', 'B', '16');
$mpdf->SetX(25);
$mpdf->WriteCell(25, 0, 'อายุประมาณ', 0, 0, 'L', 0, '', 0);
$mpdf->SetFont('sarabun', '', '16');
$age = $data[0]['age'];
$ageAsString = strval($age);
$mpdf->WriteCell(10, 0, $ageAsString, 0, 0, 'L', 0, '', 0);
$mpdf->SetFont('sarabun', 'B', '16');
$mpdf->WriteCell(33, 0, 'ปี   เส้นรอบวงลำต้น', 0, 0, 'L', 0, '', 0);
$mpdf->SetFont('sarabun', '', '16');
$age = $data[0]['girth'];
$ageAsString = strval($age);
$mpdf->WriteCell(10, 0, $ageAsString, 0, 0, 'L', 0, '', 0);
$mpdf->SetFont('sarabun', 'B', '16');
$mpdf->WriteCell(28, 0, 'เมตร   ความสูง', 0, 0, 'L', 0, '', 0);
$mpdf->SetFont('sarabun', '', '16');
$age = $data[0]['height'];
$ageAsString = strval($age);
$mpdf->WriteCell(10, 0, $ageAsString, 0, 0, 'L', 0, '', 0);
$mpdf->SetFont('sarabun', 'B', '16');
$mpdf->WriteCell(28, 0, 'เมตร', 0, 0, 'L', 0, '', 0);
/////////////////////////////
$mpdf->Ln(8);
$mpdf->SetFont('sarabun', 'B', '16');
$mpdf->SetX(25);
$mpdf->WriteCell(25, 0, 'สถานภาพ', 0, 0, 'L', 0, '', 0);
$mpdf->SetFont('sarabun', '', '16');
$mpdf->WriteCell(18, 0, $data[0]['status'], 0, 0, 'L', 0, '', 0);
$mpdf->SetFont('sarabun', 'B', '16');
$mpdf->WriteCell(33, 0, 'จำนวนที่พบ', 0, 0, 'L', 0, '', 0);
$mpdf->SetFont('sarabun', '', '16');
$age = $data[0]['qty'];
$ageAsString = strval($age);
$mpdf->WriteCell(18, 0, $ageAsString, 0, 0, 'L', 0, '', 0);
$mpdf->SetFont('sarabun', 'B', '16');
$mpdf->WriteCell(28, 0, 'ต้น', 0, 0, 'L', 0, '', 0);
/////////////////////////////
$mpdf->Ln(12);
$mpdf->SetFont('sarabun', 'B', '16');
$mpdf->WriteCell(18, 0, 'การใช้ประโยชน์ในท้องถิ่น (ระบุส่วนที่ใช้และวิธีการใช้)', 0, 0, 'L', 0, '', 0);
/////////////////////////////
$mpdf->Ln(6);
$mpdf->SetFont('sarabun', 'B', '16');
$mpdf->SetX(25);
$mpdf->WriteCell(25, 0, 'อาหาร', 0, 0, 'L', 0, '', 0);
$mpdf->Ln(6);
$mpdf->SetX(25);
$mpdf->SetFont('sarabun', '', '16');
$text = $data[0]['benefit_food'];
$mpdf->Ln(6);
$mpdf->SetX(25);
$mpdf->MultiCell(0, 7, $text, 0, 'L');
/////////////////////////////
$mpdf->Ln(8);
$mpdf->SetFont('sarabun', 'B', '16');
$mpdf->SetX(25);
$mpdf->WriteCell(25, 0, 'ยารักษาโรค ใช้กับคน', 0, 0, 'L', 0, '', 0);
$mpdf->Ln(6);
$mpdf->SetX(25);
$mpdf->SetFont('sarabun', '', '16');
$text = $data[0]['benefit_medicine_human'];
$mpdf->Ln(6);
$mpdf->SetX(25);
$mpdf->MultiCell(0, 7, $text, 0, 'L');
/////////////////////////////
$mpdf->Ln(8);
$mpdf->SetFont('sarabun', 'B', '16');
$mpdf->SetX(25);
$mpdf->WriteCell(25, 0, 'ยารักษาโรค ใช้กับสัตว์', 0, 0, 'L', 0, '', 0);
$mpdf->Ln(6);
$mpdf->SetX(25);
$mpdf->SetFont('sarabun', '', '16');
$text = $data[0]['benefit_medicine_animal'];
$mpdf->Ln(6);
$mpdf->SetX(25);
$mpdf->MultiCell(0, 7, $text, 0, 'L');
/////////////////////////////
$mpdf->Ln(8);
$mpdf->SetFont('sarabun', 'B', '16');
$mpdf->SetX(25);
$mpdf->WriteCell(25, 0, 'เครื่องเรือน เครื่องใช้อื่นๆ', 0, 0, 'L', 0, '', 0);
$mpdf->Ln(6);
$mpdf->SetX(25);
$mpdf->SetFont('sarabun', '', '16');
$text = $data[0]['benefit_appliances'];
$mpdf->Ln(6);
$mpdf->SetX(25);
$mpdf->MultiCell(0, 7, $text, 0, 'L');
/////////////////////////////
$mpdf->Ln(8);
$mpdf->SetFont('sarabun', 'B', '16');
$mpdf->SetX(25);
$mpdf->WriteCell(25, 0, 'ยาฆ่าแมลง ยาปราบศัตรูพืช', 0, 0, 'L', 0, '', 0);
$mpdf->Ln(6);
$mpdf->SetX(25);
$mpdf->SetFont('sarabun', '', '16');
$text = $data[0]['benefit_pesticide'];
$mpdf->Ln(6);
$mpdf->SetX(25);
$mpdf->MultiCell(0, 7, $text, 0, 'L');
/////////////////////////////
$mpdf->Ln(8);
$mpdf->SetFont('sarabun', 'B', '16');
$mpdf->SetX(25);
$mpdf->WriteCell(25, 0, 'ความเกี่ยวข้องกับประเพณี วัฒนธรรม', 0, 0, 'L', 0, '', 0);
$mpdf->Ln(6);
$mpdf->SetX(25);
$mpdf->SetFont('sarabun', '', '16');
$text = $data[0]['about_tradition'];
$mpdf->Ln(6);
$mpdf->SetX(25);
$mpdf->MultiCell(0, 7, $text, 0, 'L');
/////////////////////////////
$mpdf->Ln(8);
$mpdf->SetFont('sarabun', 'B', '16');
$mpdf->SetX(25);
$mpdf->WriteCell(25, 0, 'ความเกี่ยวข้องกับความเชื่อทางศาสนา', 0, 0, 'L', 0, '', 0);
$mpdf->Ln(6);
$mpdf->SetX(25);
$mpdf->SetFont('sarabun', '', '16');
$text = $data[0]['about_religion'];
$mpdf->Ln(6);
$mpdf->SetX(25);
$mpdf->MultiCell(0, 7, $text, 0, 'L');
/////////////////////////////
$mpdf->Ln(8);
$mpdf->SetFont('sarabun', 'B', '16');
$mpdf->SetX(25);
$mpdf->WriteCell(25, 0, 'อื่นๆ (เช่นการเป็นพิษ อันตราย)', 0, 0, 'L', 0, '', 0);
$mpdf->Ln(6);
$mpdf->SetX(25);
$mpdf->SetFont('sarabun', '', '16');
$text = $data[0]['other'];
$mpdf->Ln(6);
$mpdf->SetX(25);
$mpdf->MultiCell(0, 7, $text, 0, 'L');
$mpdf->AddPage();
// $mpdf->WriteHTML('<h1 style="text-align: center;">ข้อมูลพืช</h1>');

$mpdf->WriteHTML('<h4>ความสำคัญของพืช โดยเลือกได้มากกว่า 1 กลุ่ม แบ่งออกเป็น</h4>');
$plantGroups = [
    'พืชไม้ผล',
    'พืชใช้เนื้อไม้',
    'ไม้ดอก ไม้ประดับ',
    'พืชเส้นใย',
    'พืชผักพื้นเมือง ผักสวนครัว',
    'พืชให้แป้งและน้ำตาล',
    'พืชจักสาน',
    'พืชให้น้ำมันหอมระเหย',
    'พืชเครื่องดื่ม',
    'พืชอาหารสัตว์',
    'พืชสมุนไพร',
    'พืชมีพิษ',
    'พืชเครื่องเทศ',
    'พืชกําจัดแมลง',
    'พืชน้ํามัน',
    'วัชพืช',
    'พืชให้สี',
    'พืชให้น้ำยาง',
    'พืชเพื่อสิ่งแวดล้อมและระบบนิเวศ'
];
$mpdf->WriteHTML('<table>');
$i = 0;
foreach ($plantGroups as $group) {
    if ($i % 2 === 0) {
        $mpdf->WriteHTML('<tr>');
    }

    $mpdf->WriteHTML('<td style="padding-right: 20px;"><label><input type="checkbox"> ' . $group . '</label></td>');

    if ($i % 2 !== 0) {
        $mpdf->WriteHTML('</tr>');
    }

    $i++;
}

// ถ้ามีเหลือที่ยังไม่ได้ปิดแถว
if ($i % 2 !== 0) {
    $mpdf->WriteHTML('<td></td></tr>'); // เพิ่มเซลล์ว่างเพื่อปิดแถว
}

$mpdf->WriteHTML('</table>');


// ข้อมูลเจ้าของพืช
$mpdf->Ln(6);
$mpdf->SetX(25);
$mpdf->WriteHTML('<label><input type="checkbox" name="plant_owner" value="มีเจ้าของ"> มีเจ้าของ</label> &nbsp;&nbsp;&nbsp;&nbsp; <label><input type="checkbox" name="plant_owner" value="ไม่มีเจ้าของ"> ไม่มีเจ้าของ</label>');

// ฟอร์มข้อมูลเจ้าของ
$mpdf->Ln(8);
$mpdf->SetFont('sarabun', 'B', '16');
$mpdf->SetX(25);
$mpdf->WriteCell(25, 0, 'เจ้าของพืช', 0, 0, 'L', 0, '', 0);
$mpdf->Ln(8);
$mpdf->SetFont('sarabun', 'B', '16');
$pageWidth = 210;
// กำหนดข้อความที่ยาวเต็มความกว้างของหน้ากระดาษ
$repeatDots = str_repeat('.', ($contentWidth / 1.8));

// บรรทัดที่ 1: ชื่อ-สกุล อายุ อาชีพ
$mpdf->SetX(25);
$mpdf->WriteCell(80, 10, 'ชื่อ-สกุล: .................................................................', 0, 0, 'L', 0, '', 0);
$mpdf->WriteCell(30, 10, 'อายุ: .......................', 0, 0, 'L', 0, '', 0);
$mpdf->WriteCell(90, 10, 'อาชีพ: ...................................................', 0, 1, 'L', 0, '', 0);

// บรรทัดที่ 2: ชื่อหมู่บ้าน/ชุมชน/แขวง ที่อยู่บ้านเลขที่ หมู่ที่/ชุมชนที่/แขวงที่
$mpdf->SetX(25);
$mpdf->WriteCell(80, 10, 'ชื่อหมู่บ้าน/ชุมชน/แขวง: .......................................', 0, 0, 'L', 0, '', 0);
$mpdf->WriteCell(40, 10, 'ที่อยู่บ้านเลขที่: ...............', 0, 0, 'L', 0, '', 0);
$mpdf->WriteCell(50, 10, 'หมู่ที่/ชุมชนที่/แขวงที่: ...............', 0, 1, 'L', 0, '', 0);

// บรรทัดที่ 3: ซอย ถนน ตำบล/แขวง
$mpdf->SetX(25);
$mpdf->WriteCell(45, 10, 'ซอย: ..................................', 0, 0, 'L', 0, '', 0);
$mpdf->WriteCell(45, 10, 'ถนน: ..................................', 0, 0, 'L', 0, '', 0);
$mpdf->WriteCell(50, 10, 'ตำบล/แขวง: ..........................................................', 0, 1, 'L', 0, '', 0);

// บรรทัดที่ 4: อำเภอ/เขต จังหวัด หมายเลขโทรศัพท์
$mpdf->SetX(25);
$mpdf->WriteCell(80, 10, 'อำเภอ/เขต: .........................................................', 0, 0, 'L', 0, '', 0);
$mpdf->WriteCell(80, 10, 'จังหวัด: .........................................................', 0, 1, 'L', 0, '', 0);

// บรรทัดที่ 5: อีเมล
$mpdf->SetX(25);
$mpdf->WriteCell(90, 10, 'หมายเลขโทรศัพท์: .........................................................', 0, 0, 'L', 0, '', 0);
$mpdf->WriteCell(60, 10, 'อีเมล: .........................................................', 0, 1, 'L', 0, '', 0);

// ฟอร์มข้อมูลผู้ให้ข้อมูล
$mpdf->Ln(8);
$mpdf->SetFont('sarabun', 'B', '16');
$mpdf->SetX(25);
$mpdf->WriteCell(25, 0, 'ผู้ให้ข้อมูล', 0, 0, 'L', 0, '', 0);
$mpdf->Ln(8);
$mpdf->SetFont('sarabun', 'B', '16');
$pageWidth = 210;
// กำหนดข้อความที่ยาวเต็มความกว้างของหน้ากระดาษ
$repeatDots = str_repeat('.', ($contentWidth / 1.8));

// บรรทัดที่ 1: ชื่อ-สกุล อายุ อาชีพ
$mpdf->SetX(25);
$mpdf->WriteCell(80, 10, 'ชื่อ-สกุล: .................................................................', 0, 0, 'L', 0, '', 0);
$mpdf->WriteCell(30, 10, 'อายุ: .......................', 0, 0, 'L', 0, '', 0);
$mpdf->WriteCell(90, 10, 'อาชีพ: ...................................................', 0, 1, 'L', 0, '', 0);

// บรรทัดที่ 2: ชื่อหมู่บ้าน/ชุมชน/แขวง ที่อยู่บ้านเลขที่ หมู่ที่/ชุมชนที่/แขวงที่
$mpdf->SetX(25);
$mpdf->WriteCell(80, 10, 'ชื่อหมู่บ้าน/ชุมชน/แขวง: .......................................', 0, 0, 'L', 0, '', 0);
$mpdf->WriteCell(40, 10, 'ที่อยู่บ้านเลขที่: ...............', 0, 0, 'L', 0, '', 0);
$mpdf->WriteCell(50, 10, 'หมู่ที่/ชุมชนที่/แขวงที่: ...............', 0, 1, 'L', 0, '', 0);

// บรรทัดที่ 3: ซอย ถนน ตำบล/แขวง
$mpdf->SetX(25);
$mpdf->WriteCell(45, 10, 'ซอย: ..................................', 0, 0, 'L', 0, '', 0);
$mpdf->WriteCell(45, 10, 'ถนน: ..................................', 0, 0, 'L', 0, '', 0);
$mpdf->WriteCell(50, 10, 'ตำบล/แขวง: ..........................................................', 0, 1, 'L', 0, '', 0);

// บรรทัดที่ 4: อำเภอ/เขต จังหวัด หมายเลขโทรศัพท์
$mpdf->SetX(25);
$mpdf->WriteCell(80, 10, 'อำเภอ/เขต: .........................................................', 0, 0, 'L', 0, '', 0);
$mpdf->WriteCell(80, 10, 'จังหวัด: .........................................................', 0, 1, 'L', 0, '', 0);

// บรรทัดที่ 5: อีเมล
$mpdf->SetX(25);
$mpdf->WriteCell(90, 10, 'หมายเลขโทรศัพท์: .........................................................', 0, 0, 'L', 0, '', 0);
$mpdf->WriteCell(60, 10, 'อีเมล: .........................................................', 0, 1, 'L', 0, '', 0);
/////////////////////////////
$mpdf->AddPage();
/////////////////////////////
$mpdf->Ln(12);
$mpdf->SetFont('sarabun', 'B', '16');
$mpdf->WriteCell(18, 0, 'ภาพประกอบ', 0, 0, 'L', 0, '', 0);
/////////////////////////////
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
$mpdf->setY(15);
foreach ($data2 as $index => $d) {
    $col = $index % $columns; // คำนวณตำแหน่งคอลัมน์
    $row = floor($index / $columns); // คำนวณตำแหน่งแถว

    $posX = $startX + ($col * ($imageWidth + $marginX)); // คำนวณตำแหน่งแนวนอนของภาพ
    $posY = $startY + ($row * ($imageHeight + $marginY + 10)); // คำนวณตำแหน่งแนวตั้งของภาพ

    $mpdf->Image($d["image_name"], $posX, $posY, $imageWidth, $imageHeight, 'PNG');
    $mpdf->SetXY($posX, $posY + $imageHeight + 2); // ตั้งตำแหน่งของ label
    $mpdf->Cell($imageWidth, 10, $d["type_img"], 0, 0, 'C'); // เพิ่ม label ใต้ภาพ
}
/////////////////////////////
$mpdf->Ln(25);
$mpdf->SetFont('sarabun', 'B', '16');
$mpdf->SetX(25);
$mpdf->WriteCell(33, 0, 'ผู้ให้ข้อมูล ชื่อ-สกุล ', 0, 0, 'L', 0, '', 0);
$mpdf->SetFont('sarabun', '', '16');
$mpdf->WriteCell(30, 0, $data[0]["name_adder"], 0, 0, 'L', 0, '', 0);
$mpdf->SetFont('sarabun', 'B', '16');
$mpdf->WriteCell(18, 0, 'สังกัด  ', 0, 0, 'L', 0, '', 0);
$mpdf->SetFont('sarabun', '', '16');
$mpdf->WriteCell(18, 0, $data[0]["og"], 0, 0, 'L', 0, '', 0);

$mpdf->Output();
