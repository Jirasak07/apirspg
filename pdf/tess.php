<?php


header("Access-Control-Allow-Origin: *");

 $term = $_GET['term'];
 $idemp = $_GET['idemp'];

 $time = $_GET['time'];
 $Meeting = $_GET['Meeting'];//ครั้งที่ประชุม
 $Agenda = $_GET['Agenda'];//วาระที่
 $date = $_GET['date'];//วันที่อนุมัติ
 $date2 = $_GET['date2'];//วันที่ประชุมสภา
 $a = 2; //กำหนดวนลูปหน้าปก 

  $url = "https://git.kpru.ac.th/FrontEnd_Tabian/load/showteachingschedule/".$term."/".$idemp."";
  //$url = "https://git.kpru.ac.th/FrontEnd_Tabian/load/showteachingschedule/1/2564/3659900155237";
  $data = json_decode(file_get_contents($url), true);
  $sumdata = count($data);
  // $sumdatasc = count($data.['saka']);
  //$sumdata2sub1 = count($data2[0]['saka']);

  $urlgetteacher = "https://git.kpru.ac.th/FrontEnd_Tabian/load/getteacher/".$idemp."";
  //$url = "https://git.kpru.ac.th/FrontEnd_Tabian/load/showteachingschedule/1/2564/3659900155237";
  $datagetteacher = json_decode(file_get_contents($urlgetteacher), true);
  $sumdatagetteacher = count($datagetteacher);
  $poname = $datagetteacher[0]['poname'];
  $prenm = $datagetteacher[0]['prenm'];


//   $url2 = "https://mua.kpru.ac.th/FrontEnd_Tabian/Finish/showreportlevel/".$term."/".$year."/".$time."/3/1";
//   $data2 = json_decode(file_get_contents($url2), true);
//   $sumdata2 = count($data2);
//   //$sumdata2sub1 = count($data2[0]['saka']);
  

//   $url3 = "https://mua.kpru.ac.th/FrontEnd_Tabian/Finish/showreportlevel/".$term."/".$year."/".$time."/3/5";
//   $data3 = json_decode(file_get_contents($url3), true);
//   $sumdata3 = count($data3);


  $url4 = "http://localhost/PHPAPI/selectapprove.php";
  $approve = json_decode(file_get_contents($url4), true);
  $dataapprove = count($approve);
 // print_r($approve);

  $url5 = "http://localhost/PHPAPI/selectrefer.php";
  $refer = json_decode(file_get_contents($url5), true);
  $datarefer = count($refer);


  
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

function Footer() {
  // Position at 15 mm from bottom
  $this->SetY(-15);
  // Set font
  $this->SetFont('helvetica', 'I', 8);
  // Page number
  $this->Cell(0, 10, 'Page ssss', 0, false, 'C', 0, '', 0, false, 'T', 'M');
}

require_once('tcpdf.php');

$eng_date=strtotime($date); 
$datenew1 = thai_date_and_time($eng_date);


$eng_date2=strtotime($date2); 
$datenew2 = thai_date_and_time($eng_date2);




$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8');

$pdf->SetCreator('ตารางเรียน');
$pdf->SetAuthor('ตารางเรียน');
$pdf->SetTitle('ตารางสอน');
$pdf->SetSubject('ตารางเรียน');
$pdf->SetKeywords('ตารางเรียน, kpru, PDF, example, guide');

$pdf->setHeaderFont(array('thsarabun', 'B', 12));

$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, 'หลักสูตรและแผนการเรียน', 'การเพิ่มตัวอักษร Font (ฟอนต์) และการนำไปใช้', array (0, 0, 255), array (0, 64, 128));
$pdf->setFooterData(array (0, 64, 0), array (0, 64, 128));

$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, 'sss', PDF_FONT_SIZE_DATA));

$pdf->setPrintHeader(false);//สำหรับปิด header
$pdf->setPrintFooter(true);//สำหรับปิด header

$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);


$pdf->AddPage();
$htmlcontent2='
<table  border="0">
 
  <tr align="center">
    <th  width="7%">รหัสวิชา </th>
    <th  width="23%">ชื่อวิชา </th>
    <th  width="6%">หน่วยกิต </th>
    <th  width="7%">หมู่เรียน </th>
    <th  width="15%">โปรแกรมวิชา </th>
    <th  width="5%">จำนวน </th>
    <th  width="16%">ผู้สอน </th>
    <th  width="5%">วัน </th>
    <th  width="7%">เวลา </th>
    <th  width="10%">ห้องเรียน </th>
  </tr>

</table>
';
$pdf->SetFont('thsarabunb', 'B', 14);
$pdf->writeHTML($htmlcontentlogo,  false, 0, true, 0 );
$pdf->SetFont('thsarabun', 16);
$pdf->writeHTML('
<table  border="0">
  <tr >
  jjhj
  </tr>
</table>
',  false, 0, true, 0 );
$pdf->SetFont('thsarabunb', 'B', 16);
//$pdf->writeHTML($datalistfinish,  false, 0, true, 0 );


$pdf->Output('tess.pdf', 'I');
