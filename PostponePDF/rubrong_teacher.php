<?php

header("Access-Control-Allow-Origin: *");

$token = $_GET['token'];
$term = $_GET['term'];
$id_no = $_GET['id_no'];

$url = "https://mua.kpru.ac.th/apipostpone/Rubrong?TERM=".$term."&ID_NO=".$id_no;
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
function tomonth($month){
  $monthshort = array("ม.ค.","ก.พ.","มี.ค.","เม.ย.","พ.ค.","มิ.ย.","ก.ค.","ส.ค.","ก.ย.","ต.ค.","พ.ย.","ธ.ค.");
  $monthlong = array("มกราคม","กุมภาพันธ์","มีนาคม","เมษายน","พฤษภาคม","มิถุนายน","กรกฎาคม","สิงหาคม","กันยายน","ตุลาคม","พฤศจิกายน","ธันวาคม");
  $str = str_replace($monthshort, $monthlong, $month);
  return $str;
}

require_once('tcpdf.php');

$explodedate = explode(" ",$respone[0]['setDateSignature_Teacher']);
$explodedate[1] = tomonth($explodedate[1]);
$explodedate[2] = substr($explodedate[2], 0, 4);
$explodeterm = explode("/", $term);

$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8');

$pdf->SetCreator('ขาดแคลนทุนทรัพย์อย่างแท้จริง');
$pdf->SetAuthor('ขาดแคลนทุนทรัพย์อย่างแท้จริง');
$pdf->SetTitle('เอกสารประกอบ');
$pdf->SetSubject('ขาดแคลนทุนทรัพย์อย่างแท้จริง');
$pdf->SetKeywords('ขาดแคลนทุนทรัพย์อย่างแท้จริง, kpru, PDF, example, guide');

$pdf->setHeaderFont(array('thsarabun', 'B', 12));

$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, 'APPLICATION FORM FOR COURSE(S) WITHDRAWAL', 'ขาดแคลนทุนทรัพย์อย่างแท้จริง', array (0, 0, 255), array (0, 64, 128));
$pdf->setFooterData(array (0, 64, 0), array (0, 64, 128));

$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

$pdf->setPrintHeader(false);//สำหรับปิด header
$pdf->setPrintFooter(false);//สำหรับปิด header

$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

$pdf->AddPage();
$pdf->Ln(20);
$pdf->SetFont('thsarabun', 'B', 18);
$htmlcontent='
  <table>
    <tr>
      <th align="center" width="100%">หนังสือรับรองของอาจารย์ที่ปรึกษา</th>
    </tr>
  </table>
';
$pdf->writeHTML($htmlcontent, false, 0, true, 0);
$pdf->Ln(5);
$pdf->SetFont('thsarabun', '', 16);
$pdf->Cell(170, 0, 'วันที่  '.$explodedate[0].'  เดือน  '.$explodedate[1].'  พ.ศ  '.$explodedate[2], 0, 1, 'R', 0, '', 0);
$pdf->Ln(5);
$pdf->SetFont('thsarabun', '', 16);

$classstudent = substr($respone[0]['TERM'], 4, 2) - substr($respone[0]['ID_NO'], 0, 2) + 1;
$htmlcontent='
  <table>
    <tr>
      <td align="left" width="10%"></td>
      <td align="left" width="90%">ข้าพเจ้า&nbsp;&nbsp;&nbsp;'.$respone[0]['prename_full_tha'].$respone[0]['first_name_tha'].'  '.$respone[0]['last_name_tha'].'&nbsp;&nbsp;&nbsp;ตำแหน่ง&nbsp;&nbsp;&nbsp;'.$respone[0]['position_rank_name'].'&nbsp;&nbsp;&nbsp;</td>
    </tr>
    <tr>
      <td align="left" width="100%">อาจารย์ประจำสาขาวิชา&nbsp;&nbsp;&nbsp;'.$respone[0]['organization_name_tha'].'&nbsp;&nbsp;&nbsp;คณะ&nbsp;&nbsp;&nbsp;'.str_replace("คณะ" , "", $respone[0]['organization_parent']).'&nbsp;&nbsp;&nbsp;
      <br />สังกัดสถานศึกษา มหาวิทยาลัยราชภัฏกำแพงเพชร เป็นอาจารย์ที่ปรึกษาของ&nbsp;&nbsp;&nbsp;'.$respone[0]['PNAME'].$respone[0]['NAME'].'&nbsp;&nbsp;&nbsp;
      <br />รหัสประจำตัวนักศึกษา&nbsp;&nbsp;&nbsp;'.$respone[0]['ID_NO'].'&nbsp;&nbsp;&nbsp;นักศึกษาชั้นปีที่&nbsp;&nbsp;'.$classstudent.'&nbsp;&nbsp;สาขาวิชา&nbsp;&nbsp;&nbsp;'.$respone[0]['t_mjname'].'&nbsp;&nbsp;&nbsp;
      <br />คณะ&nbsp;&nbsp;&nbsp;'.str_replace("คณะ" , "", $respone[0]['faculty_name']).'&nbsp;&nbsp;&nbsp;มีผลการเรียนเฉลี่ยสะสม&nbsp;&nbsp;&nbsp;'.$respone[0]['GRADE'].'&nbsp;&nbsp;&nbsp;</td>
    </tr>
    <tr>
      <td align="left" width="100%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ความเห็นของอาจารย์ที่ปรึกษา&nbsp;&nbsp;&nbsp;'.$respone[0]['reason_Teacher'].'</td>
    </tr>
    <tr>
      <br />
      <td align="center" width="40%"></td>
      <td align="center" width="60%">ลงชื่ออาจารย์ที่ปรึกษา
      <br /><img src="'.$respone[0]['setDataImages_Teacher'].'" width="100" />
      <br />( '.$respone[0]['prename_full_tha'].$respone[0]['first_name_tha'].'  '.$respone[0]['last_name_tha'].' )
      <br />( '.$respone[0]['setDateSignature_Teacher'].' )
      <br />( '.$respone[0]['setCodeSignature_Teacher'].' )<br /></td>
    </tr>
    <tr>
      <td align="left" width="100%">หมายเหตุ : ใช้รับรองเพื่อให้นักศึกษานำไปประกอบเป็นหลักฐานยื่นคำร้องเป็นผู้ขาดแคลนอย่างแท้จริงของนักศึกษา<br />ระดับปริญญาตรี</td>
    </tr>
  </table>
';
$pdf->writeHTML($htmlcontent, false, 0, true, 0);

$pdf->lastPage();
$pdf->Output($_SERVER['DOCUMENT_ROOT'].'apipostpone/public/uploads/'.$id_no.'/'.$explodeterm[1].'_'.$explodeterm[0].'/'.$id_no.'_rubrongteacher.pdf', 'FI');