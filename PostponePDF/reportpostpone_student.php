<?php

header("Access-Control-Allow-Origin: *");

$token = $_GET['token'];
$term = $_GET['term'];

$url = "https://mua.kpru.ac.th/apipostpone/Report?TERM=".$term;
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

$url = "https://mua.kpru.ac.th/apipostpone/Student/StudentParent";
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
$parent = curl_exec($curl);
curl_close($curl);
$parent = json_decode($parent, true);
if($parent[0]['PAR_PHONE'] == "" || $parent[0]['PAR_PHONE'] == "null" || $parent[0]['PAR_PHONE'] == "undefined" || $parent[0]['PAR_PHONE'] == "ไม่มี" || $parent[0]['PAR_PHONE'] == null){
  $parent[0]['PAR_PHONE'] ="-";
}

$url = "https://mua.kpru.ac.th/apipostpone/Student/StudentRegister?TERM=".$term;
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
$eng4 = curl_exec($curl);
curl_close($curl);
$eng4 = json_decode($eng4, true);

if(count($eng4) == 1){
  if($eng4[0]['counteng']!=0){
    $moneyeng = 0;
  }else{
    $moneyeng = 1000;
  }
}else{
  $moneyeng = 0;
}

if($respone[0]['TERM'] == '1/2564'){
  $tax = 0;
}else{
  if(substr($respone[0]['ID_NO'], 2, 1) == '1' || substr($respone[0]['ID_NO'], 2, 1) == '5'){
    $tax = 500;
    $text = 'ภาคปกติ';
  }else if(substr($respone[0]['ID_NO'], 2, 1) == '2' || substr($respone[0]['ID_NO'], 2, 1) == '3'){
    $tax = 1500;
    $text = 'ภาค กศ.บป.';
  }
}

$url = "https://e-finance.kpru.ac.th/administrator_register/listsjcoderegister/".$respone[0]['ID_NO']."/".substr($respone[0]['TERM'], 0, 1).substr($respone[0]['TERM'], 4, 2);
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
$res = curl_exec($curl);
curl_close($curl);
$res = json_decode($res, true);
$res['DetailListMoney'][0]['Money'] = $res['DetailListMoney'][0]['Money']+$moneyeng;
$total = $res['DetailListMoney'][0]['Money']+$tax;

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
$datetime_insert = toThaiNumber($datetime_insert);
$datetime_insert = explode(" ", $datetime_insert);

$date_pay = $respone[0]['date_pay'];
$date_pay = explode("/", $date_pay);
$date_pay = $date_pay[2]."-".$date_pay[1]."-".$date_pay[0];
$date_pay = strtotime($date_pay); 
$date_pay = thai_date_and_time($date_pay);
$date_pay = toThaiNumber($date_pay);
$date_pay = explode(" ", $date_pay);

$date_finish = $respone[0]['date_finish'];
$date_finish = explode("/", $date_finish);
$date_finish = $date_finish[2]."-".$date_finish[1]."-".$date_finish[0];
$date_finish = strtotime($date_finish); 
$date_finish = thai_date_and_time($date_finish);
$date_finish = toThaiNumber($date_finish);
$date_finish = explode(" ", $date_finish);

$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8');

$pdf->SetCreator('คำร้องยกเลิกรายวิชาเรียน');
$pdf->SetAuthor('คำร้องยกเลิกรายวิชาเรียน');
$pdf->SetTitle('เอกสารประกอบ');
$pdf->SetSubject('คำร้องยกเลิกรายวิชาเรียน');
$pdf->SetKeywords('คำร้องยกเลิกรายวิชาเรียน, kpru, PDF, example, guide');

$pdf->setHeaderFont(array('thsarabun', 'B', 12));

$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, 'APPLICATION FORM FOR COURSE(S) WITHDRAWAL', 'คำร้องยกเลิกรายวิชาเรียน', array (0, 0, 255), array (0, 64, 128));
$pdf->setFooterData(array (0, 64, 0), array (0, 64, 128));

$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

$pdf->setPrintHeader(false);//สำหรับปิด header
$pdf->setPrintFooter(true);//สำหรับปิด header

$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

$pdf->AddPage();
$pdf->Ln(10);
$pdf->SetFont('thsarabun', 'B', 16);
$htmlcontent='
  <table>
    <tr>
      <th rowspan="2" width="25%"><img src="https://mua.kpru.ac.th/FrontEnd_Tabian/public/images/trakud.jpg" height="50"></th>
      <th align="center" width="65%">บันทึกข้อความ<br /></th>
      <th align="center" width="10%"></th>
    </tr>
  </table>
';
$pdf->writeHTML($htmlcontent, false, 0, true, 0);
$pdf->Ln(3);
$pdf->SetFont('thsarabun', '', 15);
$pdf->Cell(170, 0, 'ส่วนราชการ    งานทะเบียนและประมวลผล', 0, 1, 'L', 0, '', 0);
$pdf->Cell(170, 0, 'ที่.............................วันที่  '.$datetime_insert[0]." ".$datetime_insert[1]." ".$datetime_insert[2], 0, 1, 'L', 0, '', 0);
$pdf->Cell(170, 0, 'เรื่อง    ขอขยายเวลา ชำระค่าธรรมเนียมการศึกษา', 0, 1, 'L', 0, '', 0);
$pdf->Cell(170, 0, '--------------------------------------------------------------------------------------------------------------------------------------------------------------', 0, 1, 'L', 0, '', 0);
$pdf->Cell(170, 0, 'เรียน    อธิการบดีมหาวิทยาลัยราชภัฎกำแพงเพชร', 0, 1, 'L', 0, '', 0);
$pdf->Cell(170, 0, 'สิ่งที่ส่งมาด้วย    บัตรลงทะเบียน ภาคเรียน  '.toThaiNumber($term), 0, 1, 'L', 0, '', 0);
$pdf->SetFillColor(255, 255, 255);
$pdf->Ln(2);
$pdf->MultiCell(170, 0, '              ด้วยข้าพเจ้า  '.$respone[0]['PNAME'].$respone[0]['NAME'].'  เป็นนักศึกษามหาวิทยาลัยราชภัฎกำแพงเพชร 
รหัสนักศึกษา  '.toThaiNumber($respone[0]['ID_NO']).'  โปรแกรมวิชา  '.$respone[0]['t_mjname'].'  สังกัด'.$respone[0]['faculty_name'].' 
มีความประสงค์ขอขยายเวลาชำระค่าธรรมเนียมการศึกษา ครั้งที่  ๑  ในภาคเรียน  '.toThaiNumber($respone[0]['TERM']).'  เป็นจำนวนเงิน  '.toThaiNumber(number_format($res['DetailListMoney'][0]['Money'])).'  บาท พร้อมค่าปรับระเบียบของมหาวิทยาลัยฯ รวมเป็นเงินทั้งสิ้น  '.toThaiNumber(number_format($total)).'  บาท โดยจะนำเงินมาชำระค่าลงทะเบียนภายในวันที่  '.$date_pay[0]." ".$date_pay[1]." ".$date_pay[2], 0, 'L', 1, 0, '', '', true);
$pdf->Ln(45);
$pdf->Cell(170, 0, '                    จึงเรียนมาเพื่อโปรดพิจารณา', 0, 1, 'L', 0, '', 0);
$pdf->Ln(10);
$htmlcontent='
  <table>
    <tr>
      <th width="40%"></th>
      <th align="center" width="60%">ลงชื่อ.....................................................นักศึกษา</th>
    </tr>
    <tr>
      <th width="40%"></th>
      <th align="center" width="60%">( '.$respone[0]['PNAME'].$respone[0]['NAME'].' )</th>
    </tr>
    <tr>
      <th width="40%"></th>
      <th align="center" width="60%">เบอร์โทรศัพท์  '.toThaiNumber(substr($respone[0]['tel'], 0, 3)).'-'.toThaiNumber(substr($respone[0]['tel'], 3, 3)).'-'.toThaiNumber(substr($respone[0]['tel'], 6, 4)).'</th>
    </tr>
  </table>
';
$pdf->writeHTML($htmlcontent, false, 0, true, 0);

$pdf->AddPage();
$pdf->Ln(5);
$pdf->SetFont('thsarabun', 'B', 16);
$htmlcontent='
  <table>
    <tr>
      <th rowspan="2" width="25%"><img src="https://mua.kpru.ac.th/FrontEnd_Tabian/public/images/trakud.jpg" height="50"></th>
      <th align="center" width="65%">บันทึกข้อความ<br /></th>
      <th align="center" width="10%"></th>
    </tr>
  </table>
';
$pdf->writeHTML($htmlcontent, false, 0, true, 0);
$pdf->Ln(3);
$pdf->SetFont('thsarabun', '', 15);
$pdf->Cell(170, 0, 'ส่วนราชการ    งานทะเบียนและประมวลผล', 0, 1, 'L', 0, '', 0);
$pdf->Cell(170, 0, 'ที่.............................วันที่  '.$datetime_insert[0]." ".$datetime_insert[1]." ".$datetime_insert[2], 0, 1, 'L', 0, '', 0);
$pdf->Cell(170, 0, 'เรื่อง      ขออนุญาตชำระเงินค่าลงทะเบียนเรียน', 0, 1, 'L', 0, '', 0);
$pdf->Cell(170, 0, '--------------------------------------------------------------------------------------------------------------------------------------------------------------', 0, 1, 'L', 0, '', 0);
$pdf->Cell(170, 0, 'เรียน    อธิการบดีมหาวิทยาลัยราชภัฎกำแพงเพชร', 0, 1, 'L', 0, '', 0);
$pdf->Cell(170, 0, 'สิ่งที่ส่งมาด้วย    ใบระเบียนผลการเรียน', 0, 1, 'L', 0, '', 0);
$pdf->SetFillColor(255, 255, 255);
$pdf->Ln(2);

$htmlcontent1='
  <table>
    <tr>
      <td>
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ด้วยข้าพเจ้า  '.$respone[0]['PNAME'].$respone[0]['NAME'].'  
        เป็นนักศึกษา'.$text.' มหาวิทยาลัยราชภัฎกำแพงเพชร 
        รหัสประจำตัวนักศึกษา  '.toThaiNumber($respone[0]['ID_NO']).'  สาขาวิชา  '.$respone[0]['t_mjname'].'  '.$respone[0]['faculty_name'].'  
        มีความประสงค์จะขออนุญาตชำระเงินค่าลงทะเบียนเรียนย้อนหลัง ภาคเรียนที่  '.toThaiNumber($respone[0]['TERM']).'  
        จำนวนเงิน  '.toThaiNumber($res['DetailListMoney'][0]['Money']).'  บาท ค่าปรับตามระเบียบของมหาวิทยาลัยฯ เป็นเงิน  '.toThaiNumber($tax).'  บาท รวมเป็นเงินทั้งสิ้น  '.toThaiNumber($total).'  บาท 
        และได้รับหนังสือติดตามการชำระเงิน โดยให้ชำระเงินภายในวันที่  '.$date_finish[0].' '.$date_finish[1].' '.$date_finish[2].' 
        แต่ข้าพเจ้ายังไม่ได้ชำระเงินดังกล่าว ข้าพเจ้าจึงขออนุญาตมหาวิทยาลัยชำระเงินค่าธรรมเนียมการศึกษาในวันที่ '.$date_pay[0].' '.$date_pay[1].' '.$date_pay[2].'
        <br>
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        ทั้งนี้โดยมีเงื่อนไข  ดังนี้
        <br>
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        ๑. รายวิชาที่อาจารย์ผู้สอนไม่ได้ส่งผลการเรียนมา และสุดวิสัยที่จะติดตามได้จะถูกประเมินผลเป็น E และยินดีลงทะเบียนเรียนใหม่ และหากคะแนนเฉลี่ยสะสมไม่ถึงเกณฑ์นักศึกษาจะพ้นสภาพการเป็นนักศึกษาทันที
        <br>
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        ๒. อาจจะสำเร็จการศึกษาช้า
        <br>
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        ๓. อาจจะเสนอชื่อไม่ทันเข้ารับพระราชทานปริญญาบัตร
        <br>
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        ๔. หากนักศึกษาได้คะแนนเฉลี่ยสะสมต่ำกว่าเกณฑ์ (หลังจากชำระเงินแล้ว) จะต้องพ้นสภาพการเป็นนักศึกษา
        <br>
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        ๕. หากข้าพเจ้าไม่ชำระค่าธรรมเนียมตามระยะเวลาที่กำหนด ขอให้มหาวิทยาลัยดำเนินการตามระเบียบมหาวิทยาลัยราชภัฎกำแพงเพชรว่าด้วยการเก็บเงินค่าธรรมเนียมการศึกษาระดับปริญญาตรี ภาคปกติ 
        พ.ศ.๒๕๕๓ 
        และระเบียบมหาวิทยาลัยราชภัฎกำแพงเพชรว่าด้วยการเก็บเงินค่าธรรมเนียมการศึกษาระดับปริญญาตรี โครงการจัดการศึกษาสำหรับบุคลากรประจำการ พ.ศ.๒๕๔๓
        <br>
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        ข้าพเจ้าขอทำหนังสือนี้ให้ไว้ต่องานทะเบียนและประมวลผลเพื่อเป็นหลักฐานแสดงว่า ข้าพเจ้าได้รับทราบเงื่อนไขและยอมรับเงื่อนไขดังกล่าวทุกประการ และไม่มีความประสงค์จะเรียกร้องใด ๆ ทั้งสิ้น 
        ดังนั้นจึงได้ลงลายมือชื่อไว้เป็นสำคัญ
        <br>
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        จึงเรียนมาเพื่อโปรดพิจารณา
        <br>
      </td>
    </tr>
    <tr>
      <th align="left" width="60%">.....................................................ลงชื่อนักศึกษา</th>
      <th align="left" width="40%">.....................................................ลงชื่อผู้ปกครอง</th>
    </tr>
    <tr>
      <th align="left" width="60%">( '.$respone[0]['PNAME'].$respone[0]['NAME'].' )</th>
      <th align="left" width="40%">( '.$parent[0]['FULLNAME'].$parent[0]['PAR_FNAME'].' '.$parent[0]['PAR_LNAME'].' )</th>
    </tr>
    <tr>
      <th align="left" width="60%">เบอร์โทรศัพท์  '.toThaiNumber(substr($respone[0]['tel'], 0, 3)).'-'.toThaiNumber(substr($respone[0]['tel'], 3, 3)).'-'.toThaiNumber(substr($respone[0]['tel'], 6, 4)).'</th>
      <th align="left" width="40%">เบอร์โทรศัพท์  '.toThaiNumber(substr($parent[0]['PAR_PHONE'], 0, 3)).'-'.toThaiNumber(substr($parent[0]['PAR_PHONE'], 3, 3)).'-'.toThaiNumber(substr($parent[0]['PAR_PHONE'], 6, 4)).'</th>
    </tr>
    <br>
    <tr>
      <th align="left" width="60%">.....................................................ลงชื่อพยาน</th>
      <th align="left" width="40%">.....................................................ลงชื่อพยาน</th>
    </tr>
    <tr>
      <th align="left" width="60%">(....................................................)</th>
      <th align="left" width="40%">(....................................................)</th>
    </tr>
    <tr>
      <th align="left" width="60%">เบอร์โทรศัพท์...........................................</th>
      <th align="left" width="40%">เบอร์โทรศัพท์...........................................</th>
    </tr>
  </table>
';
$pdf->writeHTML($htmlcontent1, false, 0, true, 0);

$pdf->lastPage();
$pdf->Output('postpone.pdf', 'D');