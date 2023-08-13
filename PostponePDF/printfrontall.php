<?php

header("Access-Control-Allow-Origin: *");

$token = $_GET['token'];
$typestudent = $_GET['typestudent'];
$term = $_GET['term'];

$url = "https://mua.kpru.ac.th/apipostpone/PermissionTabian/PrintFrontAll?TERM=".$term."&typestudent=".$typestudent;
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
    return $thai_date_return;   
} 

function toThaiNumber($number){
  $numthai = array("๑","๒","๓","๔","๕","๖","๗","๘","๙","๐");
  $numarabic = array("1","2","3","4","5","6","7","8","9","0");
  $str = str_replace($numarabic, $numthai, $number);
  return $str;
}

require_once('tcpdf.php');

$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8');

$pdf->SetCreator('จ่าหน้าซองขอขยายเวลา');
$pdf->SetAuthor('จ่าหน้าซองขอขยายเวลา');
$pdf->SetTitle('เอกสารประกอบ');
$pdf->SetSubject('จ่าหน้าซองขอขยายเวลา');
$pdf->SetKeywords('จ่าหน้าซองขอขยายเวลา, kpru, PDF, example, guide');

$pdf->setHeaderFont(array('thsarabun', 'B', 12));

$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, 'APPLICATION FORM POSTPONE', 'จ่าหน้าซองขอขยายเวลา', array (0, 0, 255), array (0, 64, 128));
$pdf->setFooterData(array (0, 64, 0), array (0, 64, 128));

$pdf->setPrintHeader(false);//สำหรับปิด header
$pdf->setPrintFooter(false);//สำหรับปิด header

$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
$pdf->SetFont('thsarabun', '', 16);

$i=0;
while($i<count($res)){
  $pdf->AddPage();
  $pdf->Ln(50);
  if($res[$i]['HOUSEADD_NAME'] == '-'){
    $res[$i]['HOUSEADD_NAME'] = '';
  }
  if($res[$i]['SOI'] == '-'){
    $res[$i]['SOI'] = '';
  }else{
    $res[$i]['SOI'] = 'ซอย '.$res[$i]['SOI'].'  ';
  }
  if($res[$i]['STREET'] == '-'){
    $res[$i]['STREET'] = '';
  }else{
    $res[$i]['STREET'] = 'ถนน '.$res[$i]['STREET'].'  ';
  }
  $htmlcontent1='
    <table style="width: 100%; border-collapse: collapse; border: 1px solid black; padding: 0 10px 0 10px">
      <tr>
        <th align="right" width="19%" style="padding: 10px 10px 0 10px"><br /><br /><br /><img src="https://mua.kpru.ac.th/FrontEnd_Tabian/public/images/trakud.jpg" height="50"></th>
        <th align="left" width="66%"><br /><br /><br /><br /><br /><b>งานทะเบียนและประมวลผล</b><br /><b>มหาวิทยาลัยราชภัฏกำแพงเพชร</b><br /><b>ตำบลนครชุม อำเภอเมือง</b><br /><b>จังหวัดกำแพงเพชร 62000</b><br /><b>ที่ ศธ. 0536/...............</b></th>
        <th align="center" width="15%"><br /><br /><br />
          <table style="width: 100%; border: 1px solid black;">
            <tr>
              <th align="center" width="100%"><b>ติด<br />แสตมป์<br />3 บาท</b></th>
            </tr>
          </table> 
        </th>
      </tr>
      <tr>
        <th align="right" width="37%"></th>
        <th align="left" width="63%"><b>กรุณาส่ง </b><br /><b>'.$res[$i]['FULLNAME'].$res[$i]['PAR_FNAME'].'  '.$res[$i]['PAR_LNAME'].' ผู้ปกครองของ '.$res[$i]['PNAME'].$res[$i]['NAME'].'</b>
        <br /><b>ที่อยู่  '.$res[$i]['HOUSE_NUMBER'].' หมู่ที่ '.$res[$i]['MOO'].' '.$res[$i]['HOUSEADD_NAME'].' '.$res[$i]['SOI'].$res[$i]['STREET'].'
        <br />ตำบล/แขวง '.$res[$i]['SUB_DISTRICT_NAME_TH'].'  '.'อำเภอ/เขต '.$res[$i]['DISTRICT_NAME_TH'].'  '.'
        <br />จังหวัด'.$res[$i]['PROVINCE_NAME_TH'].'  '.'รหัสไปรษณีย์ '.$res[$i]['ZIPCODE'].'  '.'</b><br /></th>	
      </tr>
    </table>
  ';
  $pdf->writeHTML($htmlcontent1, false, 0, true, 0);
  $i++;
}

$pdf->Output('printfrontall.pdf', 'D');