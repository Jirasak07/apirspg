<?php

header("Access-Control-Allow-Origin: *");

$token = $_GET['token'];
$term = $_GET['term'];

$url = "https://mua.kpru.ac.th/apipostpone/Report/ReportSubfinancial?TERM=".$term;
$curl = curl_init($url);
curl_setopt($curl, CURLOPT_URL, $url);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
$headers = array(
  "Access-Control-Allow-Origin: *",
  "Content-Type: application/json",
  "Authorization: Basic ".$token,
);
curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
$respone = curl_exec($curl);
curl_close($curl);
$respone = json_decode($respone, true);

$url = "https://mua.kpru.ac.th/apipostpone/Report/ReportStudentAddress";
$curl = curl_init($url);
curl_setopt($curl, CURLOPT_URL, $url);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
$headers = array(
  "Access-Control-Allow-Origin: *",
  "Content-Type: application/json",
  "Authorization: Basic ".$token,
);
curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
$address = curl_exec($curl);
curl_close($curl);
$address = json_decode($address, true);

  //วันที่
$dayTH = ['อาทิตย์','จันทร์','อังคาร','พุธ','พฤหัสบดี','ศุกร์','เสาร์'];
$monthTH = [null,'มกราคม','กุมภาพันธ์','มีนาคม','เมษายน','พฤษภาคม','มิถุนายน','กรกฎาคม','สิงหาคม','กันยายน','ตุลาคม','พฤศจิกายน','ธันวาคม'];
$monthTH_brev = [null,'ม.ค.','ก.พ.','มี.ค.','เม.ย.','พ.ค.','มิ.ย.','ก.ค.','ส.ค.','ก.ย.','ต.ค.','พ.ย.','ธ.ค.'];
function thai_date_and_time($time){   // 19 ธันวาคม 2556 เวลา 10:10:43
    global $dayTH,$monthTH;   
    $thai_date_return = date("j",$time);   
    $thai_date_return.=" ".$monthTH[date("n",$time)];   
    $thai_date_return.= " ".(date("Y",$time)+543);   
   // $thai_date_return.= " เวลา ".date("H:i:s",$time);
    return $thai_date_return;   
} 
function shortthai_date_and_time($time){   // 19 ธันวาคม 2556 เวลา 10:10:43
    global $dayTH,$monthTH_brev;   
    $thai_date_return = date("j",$time);   
    $thai_date_return.=" ".$monthTH_brev[date("n",$time)];   
    $thai_date_return.= " ".(date("Y",$time)+543);   
   // $thai_date_return.= " เวลา ".date("H:i:s",$time);
    return $thai_date_return;   
} 

function toThaiNumber($number){
  $numthai = array("๑","๒","๓","๔","๕","๖","๗","๘","๙","๐");
  $numarabic = array("1","2","3","4","5","6","7","8","9","0");
  $str = str_replace($numarabic, $numthai, $number);
  return $str;
}

require_once('tcpdf.php');

$datetime_insert = $respone[0]['datetime_insert'];
$datetime_insert = strtotime($datetime_insert); 
$datetime_insert = thai_date_and_time($datetime_insert);
$datetime_insert = explode(" ", $datetime_insert);

$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8');

$pdf->SetCreator('ขาดแคลนทุนทรัพย์อย่างแท้จริง');
$pdf->SetAuthor('ขาดแคลนทุนทรัพย์อย่างแท้จริง');
$pdf->SetTitle('เอกสารประกอบ');
$pdf->SetSubject('ขาดแคลนทุนทรัพย์อย่างแท้จริง');
$pdf->SetKeywords('ขาดแคลนทุนทรัพย์อย่างแท้จริง, kpru, PDF, example, guide');

$pdf->setHeaderFont(array('thsarabun', 'B', 12));

$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, 'APPLICATION FORM FOR The Shortage of Financial Support', 'ขาดแคลนทุนทรัพย์อย่างแท้จริง', array (0, 0, 255), array (0, 64, 128));
$pdf->setFooterData(array (0, 64, 0), array (0, 64, 128));

$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

$pdf->setPrintHeader(false);//สำหรับปิด header
$pdf->setPrintFooter(true);//สำหรับปิด header

$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

$pdf->AddPage();
$pdf->Ln(10);
$pdf->SetFont('thsarabun', 'B', 18);
// $imagestudent = 'images/blankimage.png';
// // $imagestudent = 'https://mua.kpru.ac.th/ImagesStudent/pic'.substr($respone[0]['ID_NO'], 0, 2).'/'.substr($respone[0]['ID_NO'], 0, 7).'/'.$respone[0]['ID_NO'].'.jpg';
$htmlcontent='
  <table  >
    <tr>
      <th align="center" width="5%"></th>
      <th align="center" width="80%">แบบคำร้องเป็นผู้ขาดแคลนทุนทรัพย์อย่างแท้จริง (ภาคปกติ)<br />ของนักศึกษาระดับปริญญาตรีที่ชำระเงินช้ากว่ากำหนด
      <br />ประจำภาคเรียนที่  '.substr($respone[0]['TERM'], 0, 1).'  ปีการศึกษา  '.substr($respone[0]['TERM'], 2, 4).'  มหาวิทยาลัยราชภัฏกำแพงเพชร</th>
      <th width="15%" align="right"><img src="'.$respone[0]['imagestudent'].'" width="100" style="border: 1px solid black"></th>
    </tr>
  </table>
';
$pdf->writeHTML($htmlcontent, false, 0, true, 0);
$pdf->SetFont('thsarabun', 'B', 16);
$pdf->Cell(170, 0, 'แจ้งข้อมูลอันเป็นเท็จจะไม่ได้รับการพิจารณา', 0, 1, 'C', 0, '', 0);
$pdf->SetFont('thsarabun', '', 16);
$pdf->Cell(170, 0, 'คำเตือน', 0, 1, 'L', 0, '', 0);
$pdf->Cell(170, 0, '        1. การปลอมแปลงลายมือชื่อ หรือหลักฐาน เป็นความผิดตามประมวลกฎหมายอาญา ม.264', 0, 1, 'L', 0, '', 0);
$pdf->Cell(170, 0, '        2. การกรอกข้อความอันเป็นเท็จ เป็นความผิดตามประมวลกฎหมายอาญา ม.137', 0, 1, 'L', 0, '', 0);
$pdf->Cell(170, 0, '----------------------------------------------------------------------------------------------------------------------------------------------------', 0, 1, 'L', 0, '', 0);

$classstudent = substr($respone[0]['TERM'], 4, 2) - substr($respone[0]['ID_NO'], 0, 2) + 1;
$age = (substr($respone[0]['datetime_insert'], 0, 4)+543) - (substr((DATE('Y') + 543), 0, 2).substr($respone[0]['BDATE'], 6, 2));
$monthly_income = explode(".", $respone[0]['monthly_income']);

$htmlcontent='
  <table>
    <tr>
      <td align="left" width="3%"> 1.</td>
      <td align="left" width="97%">ชื่อ-สกุล '.$respone[0]['PNAME'].$respone[0]['NAME'].'  รหัสนักศึกษา '.$respone[0]['ID_NO'].'  นักศึกษาภาค ปกติ  ชั้นปีที่ '.$classstudent.' <br />
      วิชาเอก '.$respone[0]['t_mjname'].'  คณะ '.str_replace("คณะ" , "", $respone[0]['faculty_name']).'  คะแนนเฉลี่ยสะสม '.$respone[0]['GRADE'].' <br />
      ชื่ออาจารย์ที่ปรึกษา '.$respone[0]['prenm'].$respone[0]['poname'].'  เลขบัตรประจำตัวประชาชน '.substr($respone[0]['GDNAME'], 0, 1).'-'.substr($respone[0]['GDNAME'], 1, 4).'-'.substr($respone[0]['GDNAME'], 5, 5).'-'.substr($respone[0]['GDNAME'], 10, 2).'-'.substr($respone[0]['GDNAME'], 12, 1).' <br />
      วันเดือนปีเกิด '.substr($respone[0]['BDATE'], 0, 6).substr((DATE('Y') + 543), 0, 2).substr($respone[0]['BDATE'], 6, 2).'  อายุ '.$age.' ปี สัญชาติ '.$respone[0]['NATION_NAME_TH'].'  เชื้อชาติ '.$respone[0]['RACE_NAME'].'  ศาสนา '.str_replace("ศาสนา","",$respone[0]['RELIGION_NAME_TH']).' </td>
    </tr>
    <tr>
      <td align="left" width="3%"> 2.</td>
      <td align="left" width="97%">ภูมิลำเนาเดิม เลขที่ '.$address[0]['HOUSE_NUMBER'].'  หมู่ที่ '.$address[0]['MOO'].'  ชื่อหมู่บ้าน '.$address[0]['HOUSEADD_NAME'].'  ตรอก/ซอย '.$address[0]['SOI'].'  ถนน '.$address[0]['STREET'].'  ตำบล/แขวง '.$address[0]['SUB_DISTRICT_NAME_TH'].'  อำเภอ/เขต '.$address[0]['DISTRICT_NAME_TH'].'  จังหวัด'.$address[0]['PROVINCE_NAME_TH'].' รหัสไปรษณีย์ '.$address[0]['ZIPCODE'].' </td>
    </tr>
    <tr>
      <td align="left" width="3%"> 3.</td>
      <td align="left" width="97%">ที่อยู่ปัจจุบัน เลขที่ '.$address[1]['HOUSE_NUMBER'].'  หมู่ที่ '.$address[1]['MOO'].'  ชื่อหมู่บ้าน '.$address[1]['HOUSEADD_NAME'].'  ตรอก/ซอย '.$address[1]['SOI'].'  ถนน '.$address[1]['STREET'].'  ตำบล/แขวง '.$address[1]['SUB_DISTRICT_NAME_TH'].'  อำเภอ/เขต '.$address[1]['DISTRICT_NAME_TH'].'  จังหวัด'.$address[1]['PROVINCE_NAME_TH'].' รหัสไปรษณีย์ '.$address[1]['ZIPCODE'].' </td>
    </tr>
    <tr>
      <td align="left" width="3%"> 4.</td>
      <td align="left" width="97%">จบการศึกษาชั้นสูงสุดระดับ '.$respone[0]['STUDENT_STUDENT_VUT'].'  สถานศึกษา '.$respone[0]['STUDENT_SCHOOL_NAME'].'  จังหวัด'.$respone[0]['STUDENT_SCHOOL_PROVICE'].'  ปีการศึกษา '.$respone[0]['STUDENT_DATE_FINISH'].' </td>
    </tr>
    <tr>
      <td align="left" width="3%"> 5.</td>
      <td align="left" width="97%">เบอร์โทรศัพท์ที่ติดต่อนักศึกษาโดยตรง '.substr($respone[0]['tel'], 0, 3).'-'.substr($respone[0]['tel'], 3, 3).'-'.substr($respone[0]['tel'], 6, 4).' </td>
    </tr>
    <tr>
      <td align="left" width="3%"> 6.</td>
      <td align="left" width="97%">ข้าพเจ้าได้รับค่าใช้จ่ายเดือนละ '.number_format($monthly_income[0]).' บาท</td>
    </tr>
    <tr>
      <td align="left" width="3%"> 7.</td>
      <td align="center" width="5%"><img src="images/blank-checkbox.png" width="10"></td>
      <td align="left" width="92%">เคยขอทุนการศึกษา</td>
    </tr>
    <tr>
      <td align="left" width="3%"></td>
      <td align="center" width="5%"><img src="images/blank-checkbox.png" width="10"></td>
      <td align="left" width="92%">ไม่เคยขอทุนการศึกษา (ถ้าตอบไม่เคยขอทุนการศึกษาไม่ต้องทำ 7.1)</td>
    </tr>
    <tr>
      <td align="left" width="3%"></td>
      <td align="left" width="97%">  7.1 เคยรับทุนการศึกษา ดังนี้</td>
    </tr>
  </table>
';
$pdf->writeHTML($htmlcontent, false, 0, true, 0);

$pdf->writeHTML('
  <table  border="1">
    <tr>
      <th align="center" width="15%">ปีการศึกษา</th>
      <th align="center" width="30%">ประเภท/ผู้ให้ทุน</th>
      <th align="center" width="40%">ชื่อทุนการศึกษา</th>
      <th align="center" width="15%">จำนวนเงิน</th>
    </tr>
    <tr>
      <th align="center" width="15%"></th>
      <th align="center" width="30%"></th>
      <th align="center" width="40%"></th>
      <th align="center" width="15%"></th>
    </tr>
    <tr>
      <th align="center" width="15%"></th>
      <th align="center" width="30%"></th>
      <th align="center" width="40%"></th>
      <th align="center" width="15%"></th>
    </tr>
    <tr>
      <th align="center" width="15%"></th>
      <th align="center" width="30%"></th>
      <th align="center" width="40%"></th>
      <th align="center" width="15%"></th>
    </tr>
  </table>
',  false, 0, true, 0 );

$pdf->Ln(3);
$pdf->writeHTML('
  <table>
    <tr>
      <td align="left" width="3%"> 8.</td>
      <td align="center" width="5%"><img src="images/blank-checkbox.png" width="10"></td>
      <td align="left" width="22%">เคยกู้ยืมเงิน กยศ.</td>
      <td align="center" width="5%"><img src="images/blank-checkbox.png" width="10"></td>
      <td align="left" width="65%">ไม่เคยกู้ยืมเงิน กยศ.</td>
    </tr>
  </table>
', false, 0, true, 0);

$pdf->writeHTML('
  <table border="1">
    <tr>
      <th align="center" width="15%">ปีการศึกษา</th>
      <th align="center" width="30%">ประเภท/ผู้ให้ทุน</th>
      <th align="center" width="40%">ชื่อทุนการศึกษา</th>
      <th align="center" width="15%">จำนวนเงิน</th>
    </tr>
    <tr>
      <td align="center" width="15%"></td>
      <td align="center" width="30%"></td>
      <td align="center" width="40%"></td>
      <td align="center" width="15%"></td>
    </tr>
    <tr>
      <td align="center" width="15%"></td>
      <td align="center" width="30%"></td>
      <td align="center" width="40%"></td>
      <td align="center" width="15%"></td>
    </tr>
    <tr>
      <td align="center" width="15%"></td>
      <td align="center" width="30%"></td>
      <td align="center" width="40%"></td>
      <td align="center" width="15%"></td>
    </tr>
  </table>
',  false, 0, true, 0 );

$pdf->AddPage();
$pdf->Ln(5);
$pdf->writeHTML('
  <table>
    <tr>
      <td align="left" width="3%"> 9.</td>
      <td align="left" width="97%"> ผู้อุปการะเลี้ยงดู ชื่อ..................................................................................................................................................</td>
    </tr>
    <tr>
      <td align="left" width="25%">เลขบัตรประจำตัวประชาชน</td>
      <td align="center" width="3%"><img src="images/blank-checkbox.png" width="10"></td>
      <td align="center" width="1%">-</td>
      <td align="center" width="3%"><img src="images/blank-checkbox.png" width="10"></td>
      <td align="center" width="3%"><img src="images/blank-checkbox.png" width="10"></td>
      <td align="center" width="3%"><img src="images/blank-checkbox.png" width="10"></td>
      <td align="center" width="3%"><img src="images/blank-checkbox.png" width="10"></td>
      <td align="center" width="1%">-</td>
      <td align="center" width="3%"><img src="images/blank-checkbox.png" width="10"></td>
      <td align="center" width="3%"><img src="images/blank-checkbox.png" width="10"></td>
      <td align="center" width="3%"><img src="images/blank-checkbox.png" width="10"></td>
      <td align="center" width="3%"><img src="images/blank-checkbox.png" width="10"></td>
      <td align="center" width="3%"><img src="images/blank-checkbox.png" width="10"></td>
      <td align="center" width="1%">-</td>
      <td align="center" width="3%"><img src="images/blank-checkbox.png" width="10"></td>
      <td align="center" width="3%"><img src="images/blank-checkbox.png" width="10"></td>
      <td align="center" width="1%">-</td>
      <td align="center" width="3%"><img src="images/blank-checkbox.png" width="10"></td>
      <td align="left" width="32%">  วันเดือนปีเกิด..................................</td>
    </tr>
    <tr>
      <td align="left" width="15%">อายุ................ปี</td>
      <td align="left" width="85%">สัญชาติ....................เชื้อชาติ....................ศาสนา.................... จบการศึกษาชั้นสูงสุด.........................</td>
    </tr>
    <tr>
      <td align="left" width="50%">จากสถานศึกษา...................................................................</td>
      <td align="left" width="50%">จังหวัด.................................................................................</td>
    </tr>
    <tr>
      <td align="left" width="100%">ที่อยู่ เลขที่.....................หมู่ที่.................ชื่อหมู่บ้าน......................................ตรอก/ซอย................................................</td>
    </tr>
    <tr>
      <td align="left" width="100%">ถนน....................................ตำบล/แขวง...................................................อำเภอ/เขต.....................................................</td>
    </tr>
    <tr>
      <td align="left" width="100%">จังหวัด.............................................รหัสไปรษณีย์...........................................เบอร์โทรศัพท์...........................................</td>
    </tr>
    <tr>
      <td align="left" width="10%">อาชีพ</td>
      <td align="center" width="5%"><img src="images/blank-checkbox.png" width="10"></td>
      <td align="left" width="20%">รับราชการ</td>
      <td align="center" width="5%"><img src="images/blank-checkbox.png" width="10"></td>
      <td align="left" width="60%">รัฐวิสาหกิจ ตำแหน่ง..............................................................................</td>
    </tr>
    <tr>
      <td align="left" width="100%">หน้าที่................................................................................................................................................................................</td>
    </tr>
    <tr>
      <td align="left" width="50%">เงินเดือน...................................................................บาท</td>
      <td align="left" width="50%">เงินประจำตำแหน่ง......................................................บาท</td>
    </tr>
    <tr>
      <td align="left" width="3%"><img src="images/blank-checkbox.png" width="10"></td>
      <td align="left" width="20%">ค้าขาย โดยเป็น</td>
      <td align="center" width="5%"><img src="images/blank-checkbox.png" width="10"></td>
      <td align="left" width="10%">เจ้าของร้าน</td>
      <td align="center" width="5%"><img src="images/blank-checkbox.png" width="10"></td>
      <td align="left" width="7%">เช่าร้าน</td>
      <td align="center" width="5%"><img src="images/blank-checkbox.png" width="10"></td>
      <td align="left" width="45%">หาบแร่/รถเข็น</td>
    </tr>
    <tr>
      <td align="left" width="100%">ลักษณะสินค้า...................................................................................................................................................................</td>
    </tr>
    <tr>
      <td align="left" width="100%">รายได้โดยเฉลี่ยเดือนละ..................................................................................บาท (หากไม่แน่นอนให้ประมาณการ)</td>
    </tr>
    <tr>
      <td align="left" width="3%"><img src="images/blank-checkbox.png" width="10"></td>
      <td align="left" width="20%">เกษตรกร โดยเป็น</td>
      <td align="center" width="5%"><img src="images/blank-checkbox.png" width="10"></td>
      <td align="left" width="30%">เจ้าของที่ดินรวม...................ไร่</td>
      <td align="center" width="5%"><img src="images/blank-checkbox.png" width="10"></td>
      <td align="left" width="37%">เช่าที่ดินรวม...................ไร่</td>
    </tr>
    <tr>
      <td align="left" width="100%">ประเภทของพืชผล............................................................................................................................................................</td>
    </tr>
    <tr>
      <td align="left" width="100%">รายได้เดือนละ..................................................................................บาท (หากไม่แน่นอนให้ประมาณการ)</td>
    </tr>
    <tr>
      <td align="left" width="3%"><img src="images/blank-checkbox.png" width="10"></td>
      <td align="left" width="97%">รับจ้าง (ระบุงานให้ชัดเจน).........................................................................................................................................</td>
    </tr>
    <tr>
      <td align="left" width="100%">รายได้โดยเฉลี่ยเดือนละ..................................................................................บาท (หากไม่แน่นอนให้ประมาณการ)</td>
    </tr>
    <tr>
      <td align="left" width="3%"><img src="images/blank-checkbox.png" width="10"></td>
      <td align="left" width="97%">อื่นๆ (ระบุงานให้ชัดเจน)............................................................................................................................................</td>
    </tr>
    <tr>
      <td align="left" width="100%">รายได้โดยเฉลี่ยเดือนละ..................................................................................บาท (หากไม่แน่นอนให้ประมาณการ)</td>
    </tr>
    <tr>
      <td align="left" width="100%">10. พี่น้องร่วมบิดามารดามี.................คน  ชาย.................คน  หญิง.................คน  ข้าพเจ้าเป็นคนที่...........................</td>
    </tr>
    <tr>
      <td align="left" width="100%">พี่น้องกำลังศึกษาอยู่(รวมทั้งนักศึกษาด้วย) ....................คน</td>
    </tr>
  </table>
', false, 0, true, 0);

$pdf->writeHTML('
  <table border="1">
    <tr>
      <th align="center" width="10%">คนที่</th>
      <th align="center" width="10%">เพศ</th>
      <th align="center" width="10%">อายุ</th>
      <th align="center" width="20%">ชั้นปี</th>
      <th align="center" width="50%">สถาบันการศึกษา</th>
    </tr>
    <tr>
      <td align="center" width="10%"></td>
      <td align="center" width="10%"></td>
      <td align="center" width="10%"></td>
      <td align="center" width="20%"></td>
      <td align="center" width="50%"></td>
    </tr>
    <tr>
      <td align="center" width="10%"></td>
      <td align="center" width="10%"></td>
      <td align="center" width="10%"></td>
      <td align="center" width="20%"></td>
      <td align="center" width="50%"></td>
    </tr>
    <tr>
      <td align="center" width="10%"></td>
      <td align="center" width="10%"></td>
      <td align="center" width="10%"></td>
      <td align="center" width="20%"></td>
      <td align="center" width="50%"></td>
    </tr>
    <tr>
      <td align="center" width="10%"></td>
      <td align="center" width="10%"></td>
      <td align="center" width="10%"></td>
      <td align="center" width="20%"></td>
      <td align="center" width="50%"></td>
    </tr>
  </table>
',  false, 0, true, 0 );
$pdf->Ln(3);

if ($respone[0]['OldStudentID']) {
  $pdf->writeHTML('
    <table >
      <tr>
        <td align="left" width="100%">11. กรณีที่นักศึกษาลาออก/พ้นสภาพ แล้วสอบเข้าใหม่ (เฉพาะออก/พ้นสภาพ จากมหาวิทยาลัยราชภัฏกำแพงเพชร<br />เท่านัั้น)</td>
      </tr>
      <tr>
        <td align="left" width="50%">รหัสประจำตัวนักศึกษาเก่า............'.$respone[0]['OldStudentID'].'............</td>
        <td align="left" width="50%">สาขาวิชา............'.$respone[0]['oldt_mjname'].'............</td>
      </tr>
      <tr>
        <td align="left" width="50%">รหัสประจำตัวนักศึกษาใหม่............'.$respone[0]['ID_NO'].'...........</td>
        <td align="left" width="50%">สาขาวิชา............'.$respone[0]['t_mjname'].'............</td>
      </tr>
    </table>
  ', false, 0, true, 0);
}else{
  $pdf->writeHTML('
    <table >
      <tr>
        <td align="left" width="100%">11. กรณีที่นักศึกษาลาออก/พ้นสภาพ แล้วสอบเข้าใหม่ (เฉพาะออก/พ้นสภาพ จากมหาวิทยาลัยราชภัฏกำแพงเพชร<br />เท่านัั้น)</td>
      </tr>
      <tr>
        <td align="left" width="50%">รหัสประจำตัวนักศึกษาเก่า.................................................</td>
        <td align="left" width="50%">สาขาวิชา.................................................</td>
      </tr>
      <tr>
        <td align="left" width="50%">รหัสประจำตัวนักศึกษาใหม่................................................</td>
        <td align="left" width="50%">สาขาวิชา.................................................</td>
      </tr>
    </table>
  ', false, 0, true, 0);
}
$pdf->Ln(5);

$pdf->AddPage();
$pdf->Ln(5);
$pdf->writeHTML('
  <table>
    <tr>
      <td align="left" width="100%">12. มีเหตุจำเป็นอย่างไรที่ทำให้ไม่อาจชำระค่าธรรมเนียมการศึกษาได้ภายในกำหนด (เขียนอธิบายโดยละเอียด พร้อม<br />แสดงหลักฐานประกอบ)</td>
    </tr>
    <tr>
      <td align="left" width="100%">.........................................................................................................................................................................................<br />.........................................................................................................................................................................................<br />.........................................................................................................................................................................................<br />.........................................................................................................................................................................................<br />.........................................................................................................................................................................................<br />.........................................................................................................................................................................................<br />.........................................................................................................................................................................................<br />.........................................................................................................................................................................................<br />.........................................................................................................................................................................................<br />.........................................................................................................................................................................................<br />.........................................................................................................................................................................................<br />.........................................................................................................................................................................................<br />.........................................................................................................................................................................................</td>
    </tr>
  </table>
', false, 0, true, 0);

$pdf->writeHTML('
  <table>
    <tr>
      <td align="left" width="100%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ข้าพเจ้าขอรับรองว่า ข้อความทั้งหมดนี้เป็นความจริง หากปรากฏภายหลังว่าได้มีการรับรองข้อความอันเป็น<br />เท็จ ข้าพเจ้ายินยอมรับผิดชอบต่อความเสียหายที่อาจเกิดขึ้น พร้อมนี้ข้าพเจ้าได้แนบเอกสารต่างๆ เพื่อประกอบการ<br />พิจารณาแล้ว</td>
    </tr>
  </table>
', false, 0, true, 0);
$pdf->Ln(5);

$pdf->writeHTML('
  <table>
    <tr>
      <td align="center" width="50%"></td>
      <td align="center" width="50%">ลงชื่อ..........................................................</td>
    </tr>
    <tr>
      <td align="center" width="50%"></td>
      <td align="center" width="50%">( '.$respone[0]['PNAME'].$respone[0]['NAME'].' )</td>
    </tr>
    <tr>
      <td align="center" width="50%"></td>
      <td align="center" width="50%">วันที่  '.$datetime_insert[0].'  เดือน  '.$datetime_insert[1].'  พ.ศ.  '.$datetime_insert[2].'</td>
    </tr>
  </table>
', false, 0, true, 0);

$pdf->AddPage();
$pdf->Ln(10);
$pdf->SetFont('thsarabun', 'B', 18);
$htmlcontent='
  <table  >
    <tr>
      <th align="center" width="100%">หนังสือรับรองรายได้</th>
    </tr>
  </table>
';
$pdf->writeHTML($htmlcontent, false, 0, true, 0);
$pdf->SetFont('thsarabun', '', 16);
$pdf->Cell(170, 0, '(แบบฟอร์มนี้ใช้สำหรับเฉพาะผู้ประกอบอาชีพอิสระ กรณีอาชีพอื่นให้ใช้หนังสือรับรองเงินเดือนของหน่วยงาน)', 0, 1, 'C', 0, '', 0);
$pdf->Ln(5);
$pdf->SetFont('thsarabun', '', 16);
$htmlcontent='
  <table>
    <tr>
      <td align="left" width="10%"></td>
      <td align="left" width="90%">ข้าพเจ้า......................................................................ตำแหน่ง.......................................................................</td>
    </tr>
    <tr>
      <td align="left" width="100%">สถานที่ทำงาน.......................................................................เลขที่.....................................หมู่ที่......................................
      <br />ตรอก/ซอย.........................ถนน.................................ตำบล/แขวง................................อำเภอ/เขต................................
      <br />จังหวัด..........................................................รหัสไปรษณีย์......................................โทรศัพท์...........................................
      <br />ขอรับรองว่า นาย/นาง/นางสาว.......................................................................................................................................ประกอบอาชีพ...........................................................................สถานที่ทำงาน................................................................      <br />มีรายได้เฉลี่ยเดือนละ...................................................................บาท จริง</td>
    </tr>
  </table>
';
$pdf->writeHTML($htmlcontent, false, 0, true, 0);
$pdf->Ln(5);

$htmlcontent='
  <table>
    <tr>
      <td align="left" width="10%"></td>
      <td align="left" width="90%">ข้าพเจ้าขอรับรองและยืนยันว่าข้อความดังกล่าวข้างต้นเป็นความจริง หากปรากฏภายหลังว่าได้มีการ</td>
    </tr>
    <tr>
      <td align="left" width="100%">รับรองข้อความอันเป็นเท็จ ข้าพเจ้ายินยอมรับผิดชอบต่อความเสียหายที่อาจเกิดขึ้นแก่มหาวิทยาลัย<br /><br /></td>
    </tr>
    <tr>
      <td align="center" width="40%"></td>
      <td align="center" width="60%">ลงชื่อ................................................................
      <br />(................................................................)<br />ตำแหน่ง.............................................................
      <br />วันที่..................เดือน...........................พ.ศ.................<br /></td>
    </tr>
    <tr>
      <td align="left" width="100%">หมายเหตุ : บุคคลดังต่อไปนี้เท่านั้นที่สามารถรับรองรายได้</td>
    </tr>
    <tr>
      <td align="left" width="10%"></td>
      <td align="left" width="90%">1. เจ้าหน้าที่ของรัฐ หมายความว่า <br />- ข้าราชการการเมือง, ข้าราชการกรุงเทพมหานคร, ข้าราชการครู, ข้าราชการตำรวจ 
      <br />- ข้าราชการทหาร, ข้าราชการฝ่ายตุลากร, ข้าราชการฝ่านอัยการ, ข้าราชการพลเรือน <br />- ข้าราชการพลเรือนในมหาวิทยาลัย, สมาชิกสภาผู้แทนราษฎร์ และสมาชิกวุฒิสภา 
      <br />- สมาชิกสภาท้องถิ่นและหรือผู้บริหารท้องถิ่น <br />- ข้าราชการหรือพนักงานขององค์กรปกครองส่วนท้องถิ่น <br />- กำนัน ผู้ใหญ่บ้าน แพทย์ประจำตำบล สารวัตรกำนัน และผู้ช่วยผู้ใหญ่บ้าน 
      <br />- ประธานชุมชน <br />- เจ้าหน้าที่หรือพนักงานของรัฐวิสาหกิจ องค์การของรัฐ หรือองค์การมหาชน <br />- ข้าราชการ พนักงาน หรือเจ้าหน้าที่อื่นซึ่งมีพระราชกฤษฎีกากำหนดให้เป็นเจ้าหน้าที่ของรัฐตาม
      <br />พระราชบัญญัติ <br />2. เจ้าหน้าที่ของรัฐผู้รับบำเหน็จบำนาญ <br />3. สมาชิกสภาเขต สมาชิกสภากรุงเทพมหานคร ผู้ว่าราชการกรุงเทพมหานคร <br /></td>
    </tr>
  </table>
';
$pdf->writeHTML($htmlcontent, false, 0, true, 0);

$pdf->SetFont('thsarabun', '', 15);
$htmlcontent='
  <table border="1" cellspacing="0" style="padding:5px 0 5px 0;">
    <tr>
      <td align="center" width="100%">เอกสารที่ต้องแนบประกอบการรับรอง : สำเนาบัตรประจำตัวข้าราชการ หรือสำเนาบัตรประชาชนของผู้ที่รับรอง</td>
    </tr>
  </table>
';
$pdf->writeHTML($htmlcontent, false, 0, true, 0);

$pdf->lastPage();
$pdf->Output('subfinancial.pdf', 'D');