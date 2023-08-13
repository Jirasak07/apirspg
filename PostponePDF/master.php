<?php


header("Access-Control-Allow-Origin: *");

 $term = $_GET['term'];
 $year = $_GET['year'];
 $time = $_GET['time'];
 $Meeting = $_GET['Meeting'];
 $Agenda = $_GET['Agenda'];
 $date = $_GET['date'];//วันที่อนุมัติ
 $date2 = $_GET['date2'];//วันที่ประชุมสภา
 $a = 2; //กำหนดวนลูปหน้าปก 

  $url = "https://mua.kpru.ac.th/FrontEnd_Tabian/Finish/showreportlevel/".$term."/".$year."/".$time."/3";
  $data = json_decode(file_get_contents($url), true);
  $sumdata = count($data);
  //$sumdata2sub1 = count($data2[0]['saka']);

  $url2 = "https://mua.kpru.ac.th/FrontEnd_Tabian/Finish/showreportlevel/".$term."/".$year."/".$time."/3/1";
  $data2 = json_decode(file_get_contents($url2), true);
  $sumdata2 = count($data2);
  //$sumdata2sub1 = count($data2[0]['saka']);
  

  $url3 = "https://mua.kpru.ac.th/FrontEnd_Tabian/Finish/showreportlevel/".$term."/".$year."/".$time."/3/5";
  $data3 = json_decode(file_get_contents($url3), true);
  $sumdata3 = count($data3);


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

require_once('tcpdf.php');

$eng_date=strtotime($date); 
$datenew1 = thai_date_and_time($eng_date);


$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8');

$pdf->SetCreator('บัณฑิตศึกษา');
$pdf->SetAuthor('บัณฑิตศึกษา');
$pdf->SetTitle('เอกสารประกอบการ');
$pdf->SetSubject('บัณฑิตศึกษา');
$pdf->SetKeywords('บัณฑิตศึกษา, TCPDF, PDF, example, guide');

$pdf->setHeaderFont(array('thsarabun', 'B', 12));

$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, 'Mindphp Example 05', 'การเพิ่มตัวอักษร Font (ฟอนต์) และการนำไปใช้', array (0, 0, 255), array (0, 64, 128));
$pdf->setFooterData(array (0, 64, 0), array (0, 64, 128));

$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

$pdf->setPrintHeader(false);//สำหรับปิด header
$pdf->setPrintFooter(false);//สำหรับปิด header

$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

$pdf->AddPage();
$pdf->SetFont('thsarabunb', 'B', 20);
$htmlcontent='
<p align="center"><img  src="http://localhost:3000/KPRU-LOGO-line2.png" align="center" alt="Girl in a jacket" width="80" height="100"> </p> 
<h1 align="center">เอกสารประกอบ</h1>
<h2 align="center">การพิจารณาอนุมัติผลการศึกษา</h2>
<h2 align="center">ครั้งที่ '.$time.'</h2>
<h2 align="center">ระดับบัณฑิตศึกษา </h2>
';
//$pdf->Cell('', '', 'ยินดีต้อนรับสู่ MINDPHP.COM', 0, 0, 'C', false, 'http://www.mindphp.com');
$pdf->writeHTML($htmlcontent,  false, 0, true, 0);
$pdf->Ln(5);

$pdf->writeHTML('<h2>สาขาวิชาการบริหารการศึกษา</h2>',  false, 0, true, 0, 'C');
$pdf->Ln(5);


if ($time > 1) {
  $pdf->writeHTML('<h2>ภาคเรียนที่ '.$term.' ปีการศึกษา '.$year.' (เพิ่มเติม)</h2>',  false, 0, true, 0, 'C');
  $pdf->Ln();
}else{
  $pdf->writeHTML('<h2>ภาคเรียนที่ 1 ปีการศึกษา 2562</h2>',  false, 0, true, 0, 'C');
}
$pdf->Ln(8);
$pdf->Cell(395, 0, 'วันที่เสนอ '.$datenew1.' ', 0, 1, 'C', 0, '', 0);


//หน้า2------------------------------------------------------
$pdf->AddPage();
$htmlcontent2='
<table  border="1">
 
  <tr align="center">
    <th rowspan="3" width="40%">หลักสูตร </th>
    <th colspan="9" width="45%">ระดับปริญญาตรี </th>
    <th rowspan="3" width="10%">ยอดรวม </th>
  </tr>

  <tr align="center">
  <th colspan="3">เกียรตินิยมอันดับ 1</th>
  <th colspan="3">เกียรตินิยมอันดับ 2</th>
  <th colspan="3">ปริญญาตรี</th>
  </tr>

  <tr align="center">
    <td>ชาย</td>
    <td>หญิง</td>
    <td>รวม</td>

    <td>ชาย</td>
    <td>หญิง</td>
    <td>รวม</td>

    <td>ชาย</td>
    <td>หญิง</td>
    <td>รวม</td>
  </tr>

</table>
';

if ($data[$sumdata]['finish_a_sex_1'] != '0') { $p1finish_a_sex_1 =$data[$sumdata]['finish_a_sex_1']; }else{ $p1finish_a_sex_1 = '-';}
if ($data[$sumdata]['finish_a_sex_2'] != '0') { $p1finish_a_sex_2 =$data[$sumdata]['finish_a_sex_2']; }else{ $p1finish_a_sex_2 = '-';}
if ($data[$sumdata]['finish_a_sex_all'] != '0') {$p1finish_a_sex_all =$data[$sumdata]['finish_a_sex_all']; }else{ $p1finish_a_sex_all = '-';}
if ($data[$sumdata]['finish_b_sex_1'] != '0') {$p1finish_b_sex_1 =$data[$sumdata]['finish_b_sex_1']; }else{ $p1finish_b_sex_1 = '-';}
if ($data[$sumdata]['finish_b_sex_2'] != '0') {$p1finish_b_sex_2 =$data[$sumdata]['finish_b_sex_2']; }else{ $p1finish_b_sex_2 = '-';}
if ($data[$sumdata]['finish_b_sex_all'] != '0') {$p1finish_b_sex_all =$data[$sumdata]['finish_b_sex_all']; }else{ $p1finish_b_sex_all = '-';}
if ($data[$sumdata]['finish_sex_1'] != '0') {$p1finish_sex_1 =$data[$sumdata]['finish_sex_1']; }else{ $p1finish_sex_1 = '-';}
if ($data[$sumdata]['finish_sex_2'] != '0') {$p1finish_sex_2 =$data[$sumdata]['finish_sex_2']; }else{ $p1finish_sex_2 = '-';}
if ($data[$sumdata]['finish_sex_all'] != '0') {$p1finish_sex_all =$data[$sumdata]['finish_sex_all']; }else{ $p1finish_sex_all = '-';}
if ($data[$sumdata]['finish_all'] != '0') {$p1finish_all =$data[$sumdata]['finish_all']; }else{ $p1finish_all = '-';}


$datalistfinish ='
<table  border="1">
  <tr align="center">
    <td width="40%"> '.$data[$sumdata]['level'].'</td>
    <td width="5%"> '.$p1finish_a_sex_1.'</td>
    <td width="5%"> '.$p1finish_a_sex_2.'</td>
    <td width="5%"> '.$p1finish_a_sex_all.'</td>
    <td width="5%"> '.$p1finish_b_sex_1.'</td>
    <td width="5%"> '.$p1finish_b_sex_2.'</td>
    <td width="5%"> '.$p1finish_b_sex_all.'</td>
    <td width="5%"> '.$p1finish_sex_1.'</td>
    <td width="5%"> '.$p1finish_sex_2.'</td>
    <td width="5%"> '.$p1finish_sex_all.'</td>
    <td width="10%"> '.$p1finish_all.'</td>
  </tr>
</table>';

$pdf->SetFont('thsarabunb', 'B', 16);
$pdf->writeHTML('ตารางสรุปจำนวนผู้สำเร็จการศึกษาจำแนกตามหลักสูตร เพื่อขออนุมัติผลการศึกษา',  false, 0, true, 0, 'C');
$pdf->Ln();
$pdf->writeHTML('เสนอต่อคณะกรรมการอนุมัติผลการศึกษามหาวิทยาลัยราชภัฏกำแพงเพชร',  false, 0, true, 0, 'C');
$pdf->Ln();
$pdf->writeHTML('ภาคเรียนที่ 1 ปีการศึกษา 2562',  false, 0, true, 0, 'C');
$pdf->Ln(8);

$pdf->writeHTML($htmlcontent2,  false, 0, true, 0 );

$pdf->SetFont('thsarabun', 16);
for ($i=0; $i <= $sumdata-2; $i++) { 

 if ($data[$i]['finish_a_sex_1'] != '0') { $finish_a_sex_1 = $data[$i]['finish_a_sex_1']; }else{ $finish_a_sex_1 = '-';}
 if ($data[$i]['finish_a_sex_2'] != '0') { $finish_a_sex_2 = $data[$i]['finish_a_sex_2']; }else{ $finish_a_sex_2 = '-';}
 if ($data[$i]['finish_a_sex_all'] != '0') { $finish_a_sex_all = $data[$i]['finish_a_sex_all']; }else{ $finish_a_sex_all = '-';}
 if ($data[$i]['finish_b_sex_1'] != '0') { $finish_b_sex_1 = $data[$i]['finish_b_sex_1']; }else{ $finish_b_sex_1 = '-';}
 if ($data[$i]['finish_b_sex_2'] != '0') { $finish_b_sex_2 = $data[$i]['finish_b_sex_2']; }else{ $finish_b_sex_2 = '-';}
 if ($data[$i]['finish_b_sex_all'] != '0') { $finish_b_sex_all = $data[$i]['finish_b_sex_all']; }else{ $finish_b_sex_all = '-';}
 if ($data[$i]['finish_sex_1'] != '0') { $finish_sex_1 = $data[$i]['finish_sex_1']; }else{ $finish_sex_1 = '-';}
 if ($data[$i]['finish_sex_2'] != '0') { $finish_sex_2 = $data[$i]['finish_sex_2']; }else{ $finish_sex_2 = '-';}
 if ($data[$i]['finish_sex_all'] != '0') { $finish_sex_all = $data[$i]['finish_sex_all']; }else{ $finish_sex_all = '-';}
 if ($data[$i]['finish_all'] != '0') { $finish_all = $data[$i]['finish_all']; }else{ $finish_all = '-';}

 
$pdf->writeHTML('
<table  border="1">
  <tr >
    <td width="40%"> '.$data[$i]['level'].'</td>
    <td width="5%" align="center"> '.$finish_a_sex_1.'</td>
    <td width="5%" align="center"> '.$finish_a_sex_2.'</td>
    <td width="5%" align="center"> '.$finish_a_sex_all.'</td>
    <td width="5%" align="center"> '.$finish_b_sex_1.'</td>
    <td width="5%" align="center"> '.$finish_b_sex_2.'</td>
    <td width="5%" align="center"> '.$finish_b_sex_all.'</td>
    <td width="5%" align="center"> '.$finish_sex_1.'</td>
    <td width="5%" align="center"> '.$finish_sex_2.'</td>
    <td width="5%" align="center"> '.$finish_sex_all.'</td>
    <td width="10%" align="center"> '.$finish_all.'</td>
  </tr>
</table>
',  false, 0, true, 0 );
}
$pdf->SetFont('thsarabunb', 'B', 16);
$pdf->writeHTML($datalistfinish,  false, 0, true, 0 );


//หน้า..3---------------------------------------------------------------
$pdf->AddPage();
$pdf->SetFont('thsarabunb', 'B', 16);
$htmlcontent3='
<table  border="1">
 
  <tr align="center">
    <th rowspan="3" width="40%">หลักสูตร </th>
    <th colspan="9" width="45%">ระดับปริญญาตรี </th>
    <th rowspan="3" width="10%">รวม </th>
  </tr>

  <tr align="center">
  <th colspan="3">เกียรตินิยมอันดับ 1</th>
  <th colspan="3">เกียรตินิยมอันดับ 2</th>
  <th colspan="3">ปริญญาตรี</th>
  </tr>

  <tr align="center">
    <td>ชาย</td>
    <td>หญิง</td>
    <td>รวม</td>

    <td>ชาย</td>
    <td>หญิง</td>
    <td>รวม</td>

    <td>ชาย</td>
    <td>หญิง</td>
    <td>รวม</td>
  </tr>

</table>
';

if ($data2[$sumdata2]['finish_a_sex_1'] != '0') { $pf2finish_a_sex_1 = $data2[$sumdata2]['finish_a_sex_1']; }else{ $pf2finish_a_sex_1 = '-';}   
if ($data2[$sumdata2]['finish_a_sex_2'] != '0') { $pf2finish_a_sex_2 = $data2[$sumdata2]['finish_a_sex_2']; }else{ $pf2finish_a_sex_2 = '-';} 
if ($data2[$sumdata2]['finish_a_sex_all'] != '0') { $pf2finish_a_sex_all = $data2[$sumdata2]['finish_a_sex_all']; }else{ $pf2finish_a_sex_all = '-';} 
if ($data2[$sumdata2]['finish_b_sex_1'] != '0') { $pf2finish_b_sex_1 = $data2[$sumdata2]['finish_b_sex_1']; }else{ $pf2finish_b_sex_1 = '-';} 
if ($data2[$sumdata2]['finish_b_sex_2'] != '0') { $pf2finish_b_sex_2 = $data2[$sumdata2]['finish_b_sex_2']; }else{ $pf2finish_b_sex_2 = '-';} 
if ($data2[$sumdata2]['finish_b_sex_all'] != '0') { $pf2finish_b_sex_all = $data2[$sumdata2]['finish_b_sex_all']; }else{ $pf2finish_b_sex_all = '-';} 
if ($data2[$sumdata2]['finish_sex_1'] != '0') { $pf2finish_sex_1 = $data2[$sumdata2]['finish_sex_1']; }else{ $pf2finish_sex_1 = '-';}
if ($data2[$sumdata2]['finish_sex_2'] != '0') { $pf2finish_sex_2 = $data2[$sumdata2]['finish_sex_2']; }else{ $pf2finish_sex_2 = '-';}
if ($data2[$sumdata2]['finish_sex_all'] != '0') { $pf2finish_sex_all = $data2[$sumdata2]['finish_sex_all']; }else{ $pf2finish_sex_all = '-';}
if ($data2[$sumdata2]['finish_all'] != '0') { $pf2finish_all = $data2[$sumdata2]['finish_all']; }else{ $pf2finish_all = '-';}

$data2listfinish ='
<table  border="1">
  <tr align="center">
    <td width="40%"> '.$data2[$sumdata2]['level'].'</td>
    <td width="5%"> '.$pf2finish_a_sex_1.'</td>
    <td width="5%"> '.$pf2finish_a_sex_2.'</td>
    <td width="5%"> '.$pf2finish_a_sex_all.'</td>
    <td width="5%"> '.$pf2finish_b_sex_1.'</td>
    <td width="5%"> '.$pf2finish_b_sex_2.'</td>
    <td width="5%"> '.$pf2finish_b_sex_all.'</td>
    <td width="5%"> '.$pf2finish_sex_1.'</td>
    <td width="5%"> '.$pf2finish_sex_2.'</td>
    <td width="5%"> '.$pf2finish_sex_all.'</td>
    <td width="10%"> '.$data2[$sumdata2]['finish_all'].'</td>
  </tr>
</table>';

$pdf->SetFont('thsarabunb', 'B', 16);
$pdf->writeHTML('ตารางสรุปจำนวนผู้สำเร็จการศึกษามหาวิทยาลัยราชภัฏกำแพงเพชร จำแนกตามหลักสูตร เพื่อขออนุมัติผลการศึกษา',  false, 0, true, 0, 'C');
$pdf->Ln();
$pdf->writeHTML('เสนอต่อคณะกรรมการอนุมัติผลการศึกษามหาวิทยาลัยราชภัฏกำแพงเพชร',  false, 0, true, 0, 'C');
$pdf->Ln();
$pdf->writeHTML('ภาคเรียนที่ 1 ปีการศึกษา 2562',  false, 0, true, 0, 'C');
$pdf->Ln(8);
$pdf->writeHTML($htmlcontent3,  false, 0, true, 0 );
$pdf->SetFont('thsarabun', 16);

for ($i=0; $i <= $sumdata2-2; $i++) { 

if ($data2[$i]['finish_a_sex_1'] != '0') { $p2finish_a_sex_1 = $data2[$i]['finish_a_sex_1']; }else{ $p2finish_a_sex_1 = '-';}   
if ($data2[$i]['finish_a_sex_2'] != '0') { $p2finish_a_sex_2 = $data2[$i]['finish_a_sex_2']; }else{ $p2finish_a_sex_2 = '-';}  
if ($data2[$i]['finish_a_sex_all'] != '0') { $p2finish_a_sex_all = $data2[$i]['finish_a_sex_all']; }else{ $p2finish_a_sex_all = '-';}  
if ($data2[$i]['finish_b_sex_1'] != '0') { $p2finish_b_sex_1 = $data2[$i]['finish_b_sex_1']; }else{ $p2finish_b_sex_1 = '-';}  
if ($data2[$i]['finish_b_sex_2'] != '0') { $p2finish_b_sex_2 = $data2[$i]['finish_b_sex_2']; }else{ $p2finish_b_sex_2 = '-';}  
if ($data2[$i]['finish_b_sex_all'] != '0') { $p2finish_b_sex_all = $data2[$i]['finish_b_sex_all']; }else{ $p2finish_b_sex_all = '-';} 
if ($data2[$i]['finish_sex_1'] != '0') { $p2finish_sex_1 = $data2[$i]['finish_sex_1']; }else{ $p2finish_sex_1 = '-';} 
if ($data2[$i]['finish_sex_2'] != '0') { $p2finish_sex_2 = $data2[$i]['finish_sex_2']; }else{ $p2finish_sex_2 = '-';} 
if ($data2[$i]['finish_sex_all'] != '0') { $p2finish_sex_all = $data2[$i]['finish_sex_all']; }else{ $p2finish_sex_all = '-';} 
if ($data2[$i]['finish_all'] != '0') { $p2finish_all = $data2[$i]['finish_all']; }else{ $p2finish_all = '-';}

    $pdf->writeHTML('
    <table  border="1">
      <tr>
        <td width="40%" > '.$data2[$i]['level'].'</td>
        <td width="5%" align="center"> '.$p2finish_a_sex_1.'</td>
        <td width="5%" align="center"> '.$p2finish_a_sex_2.'</td>
        <td width="5%" align="center"> '.$p2finish_a_sex_all.'</td>
        <td width="5%" align="center"> '.$p2finish_b_sex_1.'</td>
        <td width="5%" align="center"> '.$p2finish_b_sex_2.'</td>
        <td width="5%" align="center"> '.$p2finish_b_sex_all.'</td>
        <td width="5%" align="center"> '.$p2finish_sex_1.'</td>
        <td width="5%" align="center"> '.$p2finish_sex_2.'</td>
        <td width="5%" align="center"> '.$p2finish_sex_all.'</td>
        <td width="10%" align="center"> '.$p2finish_all.'</td>
      </tr>
    </table>
    ',  false, 0, true, 0 );
    }

    $pdf->SetFont('thsarabunb', 'B', 16);
    $pdf->writeHTML($data2listfinish,  false, 0, true, 0 );


//หน้า..4---------------------------------------------------------------
for ($i=0; $i <= $sumdata2-2; $i++) { 
$pdf->AddPage();
$pdf->SetFont('thsarabun', 'B', 16);
$htmlcontent4='
<table  border="1">
 
  <tr align="center">
    <th rowspan="3" width="40%">หลักสูตร/สาขาวิชา </th>
    <th colspan="9" width="45%">ระดับปริญญาตรี </th>
    <th rowspan="3" width="10%">รวม </th>
  </tr>

  <tr align="center">
  <th colspan="3">เกียรตินิยมอันดับ 1</th>
  <th colspan="3">เกียรตินิยมอันดับ 2</th>
  <th colspan="3">ปริญญาตรี</th>
  </tr>

  <tr align="center">
    <td>ชาย</td>
    <td>หญิง</td>
    <td>รวม</td>

    <td>ชาย</td>
    <td>หญิง</td>
    <td>รวม</td>

    <td>ชาย</td>
    <td>หญิง</td>
    <td>รวม</td>
  </tr>

</table>
';

$sumdata2sub = count($data2[$i]['saka']);
$sumdata2listname = count($data2[$i]['listname']);
$sumdata2listname_a = count($data2[$i]['listname_a']);



$level = '<table  border="0">
<tr>
  <td width="60%"> </td>
  <td width="40%"> หลักสูตร'.$data2[$i]['level'].'</td>
</tr>
</table>';


if ($data2[$i]['saka'][$sumdata2sub]['finish_a_sex_1'] != '0') { $pf3finish_a_sex_1 = $data2[$i]['saka'][$sumdata2sub]['finish_a_sex_1']; }else{ $pf3finish_a_sex_1 = '-';}
if ($data2[$i]['saka'][$sumdata2sub]['finish_a_sex_2'] != '0') { $pf3finish_a_sex_2 = $data2[$i]['saka'][$sumdata2sub]['finish_a_sex_2']; }else{ $pf3finish_a_sex_2 = '-';}
if ($data2[$i]['saka'][$sumdata2sub]['finish_a_sex_all'] != '0') { $pf3finish_a_sex_all = $data2[$i]['saka'][$sumdata2sub]['finish_a_sex_all']; }else{ $pf3finish_a_sex_all = '-';}
if ($data2[$i]['saka'][$sumdata2sub]['finish_b_sex_1'] != '0') { $pf3finish_b_sex_1 = $data2[$i]['saka'][$sumdata2sub]['finish_b_sex_1']; }else{ $pf3finish_b_sex_1 = '-';}
if ($data2[$i]['saka'][$sumdata2sub]['finish_b_sex_2'] != '0') { $pf3finish_b_sex_2 = $data2[$i]['saka'][$sumdata2sub]['finish_b_sex_2']; }else{ $pf3finish_b_sex_2 = '-';}
if ($data2[$i]['saka'][$sumdata2sub]['finish_b_sex_all'] != '0') { $pf3finish_b_sex_all = $data2[$i]['saka'][$sumdata2sub]['finish_b_sex_all']; }else{ $pf3finish_b_sex_all = '-';}
if ($data2[$i]['saka'][$sumdata2sub]['finish_sex_1'] != '0') { $pf3finish_sex_1 = $data2[$i]['saka'][$sumdata2sub]['finish_sex_1']; }else{ $pf3finish_sex_1 = '-';}
if ($data2[$i]['saka'][$sumdata2sub]['finish_sex_2'] != '0') { $pf3finish_sex_2 = $data2[$i]['saka'][$sumdata2sub]['finish_sex_2']; }else{ $pf3finish_sex_2 = '-';}
if ($data2[$i]['saka'][$sumdata2sub]['finish_sex_all'] != '0') { $pf3finish_sex_all = $data2[$i]['saka'][$sumdata2sub]['finish_sex_all']; }else{ $pf3finish_sex_all = '-';}
if ($data2[$i]['saka'][$sumdata2sub]['finish_all'] != '0') { $pf3finish_all = $data2[$i]['saka'][$sumdata2sub]['finish_all']; }else{ $pf3finish_all = '-';}


$data4listfinish ='
<table  border="1">
  <tr align="center">
    <td width="40%"> '.$data2[$i]['saka'][$sumdata2sub]['level'].'</td>
    <td width="5%"> '.$pf3finish_a_sex_1.'</td>
    <td width="5%"> '.$pf3finish_a_sex_2.'</td>
    <td width="5%"> '.$pf3finish_a_sex_all.'</td>
    <td width="5%"> '.$pf3finish_b_sex_1.'</td>
    <td width="5%"> '.$pf3finish_b_sex_2.'</td>
    <td width="5%"> '.$pf3finish_b_sex_all.'</td>
    <td width="5%"> '.$pf3finish_sex_1 .'</td>
    <td width="5%"> '.$pf3finish_sex_2.'</td>
    <td width="5%"> '.$pf3finish_sex_all.'</td>
    <td width="10%"> '.$pf3finish_all.'</td>
  </tr>
</table>';


$pdf->SetFont('thsarabunb', 'B', 16);
$pdf->writeHTML($level,  false, 0, true, 0, 'C');
$pdf->Ln();
$pdf->writeHTML('ตารางสรุปจำนวนผู้สำเร็จการศึกษาจำแนกตามสาขา เพื่อขออนุมัติผลการศึกษา',  false, 0, true, 0, 'C');
$pdf->Ln();
$pdf->writeHTML('เสนอต่อคณะกรรมการอนุมัติผลการศึกษามหาวิทยาลัยราชภัฏกำแพงเพชร',  false, 0, true, 0, 'C');
$pdf->Ln();
$pdf->writeHTML('ภาคเรียนที่ 1 ปีการศึกษา 2562',  false, 0, true, 0, 'C');
$pdf->Ln(8);
$pdf->SetFont('thsarabun', 'B', 16);
$pdf->writeHTML($htmlcontent4,  false, 0, true, 0 );

$pdf->SetFont('thsarabun', 16);
$nn = 0;
for ($t=0; $t <= $sumdata2sub-2; $t++) { 
    $nn = $nn +1;
    if ($data2[$i]['saka'][$t]['finish_a_sex_1'] != '0') { $p3finish_a_sex_1 = $data2[$i]['saka'][$t]['finish_a_sex_1']; }else{ $p3finish_a_sex_1 = '-';}
    if ($data2[$i]['saka'][$t]['finish_a_sex_2'] != '0') { $p3finish_a_sex_2 = $data2[$i]['saka'][$t]['finish_a_sex_2']; }else{ $p3finish_a_sex_2 = '-';}
    if ($data2[$i]['saka'][$t]['finish_a_sex_all'] != '0') { $p3finish_a_sex_all = $data2[$i]['saka'][$t]['finish_a_sex_all']; }else{ $p3finish_a_sex_all = '-';}
    if ($data2[$i]['saka'][$t]['finish_b_sex_1'] != '0') { $p3finish_b_sex_1 = $data2[$i]['saka'][$t]['finish_b_sex_1']; }else{ $p3finish_b_sex_1 = '-';}
    if ($data2[$i]['saka'][$t]['finish_b_sex_2'] != '0') { $p3finish_b_sex_2 = $data2[$i]['saka'][$t]['finish_b_sex_2']; }else{ $p3finish_b_sex_2 = '-';}
    if ($data2[$i]['saka'][$t]['finish_b_sex_all'] != '0') { $p3finish_b_sex_all = $data2[$i]['saka'][$t]['finish_b_sex_all']; }else{ $p3finish_b_sex_all = '-';}
    if ($data2[$i]['saka'][$t]['finish_sex_1'] != '0') { $p3finish_sex_1 = $data2[$i]['saka'][$t]['finish_sex_1']; }else{ $p3finish_sex_1 = '-';}
    if ($data2[$i]['saka'][$t]['finish_sex_2'] != '0') { $p3finish_sex_2 = $data2[$i]['saka'][$t]['finish_sex_2']; }else{ $p3finish_sex_2 = '-';}
    if ($data2[$i]['saka'][$t]['finish_sex_all'] != '0') { $p3finish_sex_all = $data2[$i]['saka'][$t]['finish_sex_all']; }else{ $p3finish_sex_all = '-';}
    if ($data2[$i]['saka'][$t]['finish_all'] != '0') { $p3finish_all = $data2[$i]['saka'][$t]['finish_all']; }else{ $p3finish_all = '-';}

    $pdf->writeHTML('
    <table  border="1">
      <tr>
        <td width="40%"> '.$data2[$i]['saka'][$t]['level'].'</td>
        <td width="5%" align="center"> '.$p3finish_a_sex_1 .'</td>
        <td width="5%" align="center"> '.$p3finish_a_sex_2.'</td>
        <td width="5%" align="center"> '.$p3finish_a_sex_all.'</td>
        <td width="5%" align="center"> '.$p3finish_b_sex_1.'</td>
        <td width="5%" align="center"> '.$p3finish_b_sex_2.'</td>
        <td width="5%" align="center"> '.$p3finish_b_sex_all.'</td>
        <td width="5%" align="center"> '.$p3finish_sex_1.'</td>
        <td width="5%" align="center"> '.$p3finish_sex_2.'</td>
        <td width="5%" align="center"> '.$p3finish_sex_all.'</td>
        <td width="10%" align="center"> '.$p3finish_all.'</td>
      </tr>
    </table>
    ',  false, 0, true, 0 );

    }

    if ($nn > 6) {
        $pdf->AddPage();
        $pdf->SetFont('thsarabun', 'B', 16);
        $pdf->writeHTML($htmlcontent4,  false, 0, true, 0 );
    }

    $pdf->SetFont('thsarabun', 'B', 16);
    $pdf->writeHTML($data4listfinish,  false, 0, true, 0 );
    $pdf->Ln();
    $pdf->SetFont('thsarabun', 16);
    $pdf->Cell(260, 0, 'ตรวจสอบและตรวจทานถูกต้องแล้ว', 0, 1, 'C', 0, '', 0);
    $pdf->Cell(292, 0, 'ลงชื่อ                                 ('.$approve[0]['pepleapproval1'].')', 0, 1, 'C', 0, '', 0);
    $pdf->Cell(324, 0, $approve[0]['position'], 0, 1, 'C', 0, '', 0);
    $pdf->Cell(325, 0, 'ลงชื่อ                                 ('.$approve[0]['pepleapproval2'].')', 0, 1, 'C', 0, '', 0);
    $pdf->Cell(380, 0, $approve[0]['position2'], 0, 1, 'C', 0, '', 0);
    $pdf->Cell(306, 0, 'ลงชื่อ                                 ('.$approve[0]['pepleapproval3'].')', 0, 1, 'C', 0, '', 0);
    $pdf->Cell(355, 0, $approve[0]['position3'], 0, 1, 'C', 0, '', 0);
    $pdf->Cell(368, 0, $approve[0]['position4'], 0, 1, 'C', 0, '', 0);
   
    $pdf->Ln();

    //ปริญญาตรี
    for ($w=0; $w <= 10  ; $w++) { 
        $c[$w] = $data2[$i]['listname'][$w]['t_mjname'];
       // $d[$w] = $data2[$i]['listname'];
      }
      $b= array_unique ($c);
      $sumdatab = count($b);

      $pdf->AddPage();
      $pdf->SetFont('thsarabun', 'B', 16);
      $htmlcontent4sub=' <table  border="1">
 
        <tr align="center">
          <th  width="3%">ที่</th>
          <th  width="30%">ชื่อ-สกุล</th>
          <th  width="4%">นก.</th>
          <th  width="15%">ได้ค่าระดับคะแนนเฉลี่ย</th>
          <th  width="50%">หมายเหตุ</th>
        </tr>
    
      </table>
      ';  
      $pdf->SetFont('thsarabunb', 'B', 16);
      $pdf->writeHTML('บัญชีรายชื่อผู้สำเร็จการศึกษามหาวิทยาลัยราชภัฏกำแพงเพชร ขออนุมัติผลการศึกษา',  false, 0, true, 0, 'C');
      $pdf->Ln();
      $pdf->writeHTML('เสนอต่อคณะกรรมการอนุมัติผลการศึกษามหาวิทยาลัยราชภัฏกำแพงเพชร',  false, 0, true, 0, 'C');
      $pdf->Ln();
      $pdf->writeHTML('ภาคเรียนที่ 1 ปีการศึกษา 2562',  false, 0, true, 0, 'C');
      $pdf->Ln(8);
      $pdf->writeHTML('<table border="0"> 
                        <tr>
                            <th  width="60%" align="left">ระดับ : ปริญญาตรี</th>
                            <th  width="40%" align="center">หลักสูตร : '.$data2[$i]['level'].'</th>
                        </tr>
                        </table>',  false, 0, true, 0, 'C');
      $pdf->Ln(3);
      $pdf->SetFont('thsarabun', 'B', 16);
      $pdf->writeHTML($htmlcontent4sub,  false, 0, true, 0 );
      
      $or=0;
      $ortt=0;
      $or2=1;
      $tr = 3;
      $sumdloop1 = count($b);
      $sumdloop2 = $sumdloop1 +1;
      $numbers =0;

      foreach($b as $value){
        $or = $or+1;


       if (strlen($value) >= 1 ) {
        $pdf->SetFont('thsarabunb', 'B', 16);
        $pdf->writeHTML('
        <table  border="1">
          <tr>
            <td width="3%"></td>
            <td width="30%"> '.$value.'</td>
            <td width="4%"> </td>
            <td width="15%"> </td>
            <td width="50%"> </td>
          </tr>
        </table>
        ',  false, 0, true, 0 );
        }

        $find1 = $value;

                for ($l=0; $l < $sumdata2listname; $l++) {
                    $key1 = array( $data2[$i]['listname'][$l]['t_mjname']);
                   

                    if (in_array($find1 , $key1)) {
                        $ortt = $ortt+1;
                        $sumdata7 = count($data2[$i]['listname']);
                        $sumdata8 = $sumdata7 /15;
                         
                        $numbers =  $numbers+1;

                                if ($ortt >= $tr ) {
                                    $tr =  $tr+11; //จำนวนสำหรับขึ้นหน้าใหม่บัญชีรายชื่อ
                                    $pdf->AddPage();
                                    $pdf->SetFont('thsarabun', 'B', 16);
                                    $pdf->writeHTML($htmlcontent4sub,  false, 0, true, 0 );

                                }

                        $pdf->SetFont('thsarabun', 14);

                        // $refercount = count($refer);
                        // for ($f=0; $f <= $datarefer ; $f++) { 
                        //   $refer1 = array($refer[$f]['OLDID_refer']);
                           
                        // }
                       $refer1 = array($refer[$l]['OLDID_refer']);
                       $refer2 = $data2[$i]['listname'][$l]['OLDID'];

                        // if ( in_array($refer , $refer2)) {
                        //          $refers = $refer[$l]['article_refer'];
                        //       }else{
                        //           $refers ='';
                        //       }
                              $urlgetrefer = "http://localhost/PHPAPI/selectrefer.php?idd=".$refer2."";
                              $getrefer = json_decode(file_get_contents($urlgetrefer), true);
                             // $datarefer = count($refer);
                              $refers = $getrefer[0]['article_refer'];

                        //print_r($refers);
                        $pdf->writeHTML('
                        <style type="text/css">
                          p {margin-buttom: 0cm 0cm 0cm 0cm}
                        </style>
                        <table  border="1">
                          <tr>
                            <td width="3%" align="center"> '.$numbers.'</td>
                            <td width="30%"> '.$pdf->SetFont('thsarabun', 16).''.$data2[$i]['listname'][$l]['PNAME'].''.$data2[$i]['listname'][$l]['NAME'].'</td>
                            <td width="4%" align="center"> '.$data2[$i]['listname'][$l]['TCRD'].' </td>
                            <td width="15%" align="center"> '.$data2[$i]['listname'][$l]['TGPA'].' </td>
                            <td width="50%" > อนุมัติผลการศึกษา วันที่ 5 กันยายน พ.ศ. 2563'.$refers.' </td>
                            
                          </tr>
                        </table>
                        ',  false, 0, true, 0 ); 
                        $pdf->SetFont('thsarabun', 14);

                    }
                }

      }


}






//หน้า..
// $pdf->AddPage();
// $pdf->SetFont('thsarabun', 'B', 16);

// $htmlcontent3='
// <table  border="1">
// <tr>
//   <th>Firstname</th>
//   <th>Lastname</th>
//   <th>Age</th>
// </tr>
// <tr>
//   <td>Jill</td>
//   <td><p style="margin: 0cm">สสวว</p> <p style="margin: 0cm">สสวว</p></td>
//   <td><p>สุพรรณี จันทร์งาม. (2563, มกราคม-มิถุนายน). แนวทางพัฒนาการมี<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ส่วนร่วมของผู้ปกครองนักเรียนในการจัดการศึกษาของโรงเรียน<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; เทศบาลตำบลแม่กุ อำเภอแม่สอด จังหวัดตาก. <strong>วารสารครุศาสตร์</strong><br /><strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; คณะครุศาสตร์ มหาวิทยาลัยราชภัฏกำแพงเพชร,</strong> 5(9),<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; หน้า...(รอการตีพิมพ์). (วารสารไม่ได้อยู่ในฐาน TCI)</p>
//   <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p></td>
// </tr>
// <tr>
//   <td>Eve</td>
//   <td>Jackson</td>
//   <td>
//   <p>สุพรรณี จันทร์งาม. (2563, มกราคม-มิถุนายน). แนวทางพัฒนาการมี<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ส่วนร่วมของผู้ปกครองนักเรียนในการจัดการศึกษาของโรงเรียน<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; เทศบาลตำบลแม่กุ อำเภอแม่สอด จังหวัดตาก. <strong>วารสารครุศาสตร์</strong><br /><strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; คณะครุศาสตร์ มหาวิทยาลัยราชภัฏกำแพงเพชร,</strong> 5(9),<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; หน้า...(รอการตีพิมพ์). (วารสารไม่ได้อยู่ในฐาน TCI)</p>
//   <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p>
//   </td>
// </tr>
// </table> 
// ';
// $pdf->writeHTML('ตารางสรุปจำนวนผู้สำเร็จการศึกษาจำแนกตามหลักสูตร เพื่อขออนุมัติผลการศึกษา',  false, 0, true, 0, 'C');
// $pdf->Ln();
// $pdf->writeHTML('เสนอต่อคณะกรรมการอนุมัติผลการศึกษามหาวิทยาลัยราชภัฏกำแพงเพชร',  false, 0, true, 0, 'C');
// $pdf->Ln();
// $pdf->writeHTML('ภาคเรียนที่ 1 ปีการศึกษา 2562',  false, 0, true, 0, 'C');
// $pdf->Ln(8);
// $pdf->SetFont('thsarabun', 'B', 13);
// $pdf->writeHTML($htmlcontent3,  false, 0, true, 0 );
// $pdf->Ln();








$pdf->Output('mindphp05.pdf', 'I');
