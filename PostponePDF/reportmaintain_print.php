<?php
date_default_timezone_set("Asia/Bangkok");

$ID_NO = $_GET['ID_NO'];
$TERM = $_GET['TERM'];

$scope = [];
$scope['TERM'] = $TERM;

$url = "https://mua.kpru.ac.th/apimaintian/Maintain/ReportMaintainID/" . $ID_NO . "/" . $TERM;
$curl = curl_init($url);
curl_setopt($curl, CURLOPT_URL, $url);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
$headers = array(
  "Access-Control-Allow-Origin: *",
  "Content-Type: application/json",
);
curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
$respone = curl_exec($curl);
curl_close($curl);
$respone = json_decode($respone, true);

//วันที่
$dayTH = ["อาทิตย์", "จันทร์", "อังคาร", "พุธ", "พฤหัสบดี", "ศุกร์", "เสาร์"];
$monthTH = [null, "มกราคม", "กุมภาพันธ์", "มีนาคม", "เมษายน", "พฤษภาคม", "มิถุนายน", "กรกฎาคม", "สิงหาคม", "กันยายน", "ตุลาคม", "พฤศจิกายน", "ธันวาคม"];
$monthTH_brev = [null, "ม.ค.", "ก.พ.", "มี.ค.", "เม.ย.", "พ.ค.", "มิ.ย.", "ก.ค.", "ส.ค.", "ก.ย.", "ต.ค.", "พ.ย.", "ธ.ค."];
function thai_date_and_time($time)
{   // 19 ธันวาคม 2556 เวลา 10:10:43
  global $dayTH, $monthTH;
  $thai_date_return = date("j", $time);
  $thai_date_return .= " " . $monthTH[date("n", $time)];
  $thai_date_return .= " " . (date("Y", $time) + 543);
  // $thai_date_return.= " เวลา ".date("H:i:s",$time);
  return $thai_date_return;
}

function shortthai_date_and_time($time)
{   // 19 ธันวาคม 2556 เวลา 10:10:43
  global $dayTH, $monthTH_brev;
  $thai_date_return = date("j", $time);
  $thai_date_return .= " " . $monthTH_brev[date("n", $time)];
  $thai_date_return .= " " . (date("Y", $time) + 543);
  // $thai_date_return.= " เวลา ".date("H:i:s",$time);
  return $thai_date_return;
}

function toThaiNumber($number)
{
  $numthai = array("๑", "๒", "๓", "๔", "๕", "๖", "๗", "๘", "๙", "๐");
  $numarabic = array("1", "2", "3", "4", "5", "6", "7", "8", "9", "0");
  $str = str_replace($numarabic, $numthai, $number);
  return $str;
}

function toMonth($month)
{
  $longmonth = array(null, "มกราคม", "กุมภาพันธ์", "มีนาคม", "เมษายน", "พฤษภาคม", "มิถุนายน", "กรกฎาคม", "สิงหาคม", "กันยายน", "ตุลาคม", "พฤศจิกายน", "ธันวาคม");
  $shortmonth = array(null, "ม.ค.", "ก.พ.", "มี.ค.", "เม.ย.", "พ.ค.", "มิ.ย.", "ก.ค.", "ส.ค.", "ก.ย.", "ต.ค.", "พ.ย.", "ธ.ค.");
  $str = str_replace($shortmonth, $longmonth, $month);
  return $str;
}

function GenarateSignature()
{
  function randomString($length = 5)
  {
    $str = "";
    $characters = array_merge(range("A", "Z"), range("a", "z"));
    $max = count($characters) - 1;
    for ($i = 0; $i < $length; $i++) {
      $rand = mt_rand(0, $max);
      $str .= $characters[$rand];
    }
    return $str;
  }

  $strDate = date("Y-m-d");
  $strTime = date("H:i:s");
  $strYear = date("Y", strtotime($strDate)) + 543;
  $strMonth = date("n", strtotime($strDate));
  $strDay = date("j", strtotime($strDate));
  $strMonthCut = array("", "ม.ค.", "ก.พ.", "มี.ค.", "เม.ย.", "พ.ค.", "มิ.ย.", "ก.ค.", "ส.ค.", "ก.ย.", "ต.ค.", "พ.ย.", "ธ.ค.");
  $strMonthThai = $strMonthCut[$strMonth];
  $setDateSignature = "$strDay $strMonthThai $strYear";

  $setCodeSignature = sprintf("%05s-%05s-%05s-%05s", randomString(), randomString(), randomString(), randomString(), randomString());
  $a = array(
    "setDateSignature" => $setDateSignature . " เวลา " . $strTime . ", Non-PKI Server Sign,",
    "setCodeSignature" => "Signature Code : " . $setCodeSignature
  );
  return $a;
}

$setFontColour_main = [0, 0, 0];
$setFontColour_answer = [0, 0, 225];

// print_r($respone[0]);
$scope["ID_NO"] = toThaiNumber($ID_NO);
$scope["OLDID"] = toThaiNumber($respone[0]["OLDID"]);
$scope["GROUP_NO"] = toThaiNumber(substr($respone[0]["OLDID"], 0, 7));
$scope["Term_request"] = toThaiNumber($respone[0]["Term_request"]);
$scope["PNAME"] = $respone[0]["PNAME"];
$scope["NAME"] = $respone[0]["NAME"];
$scope["t_mjname"] = $respone[0]["t_mjname"];
$scope["Type_name"] = $respone[0]["type_std_name"];
$scope["Type_request"] = $respone[0]["Type_request"];
$scope["std_type"] = $respone[0]["std_type"];
$scope["LEVEL1"] = $respone[0]["LEVEL1"];
$scope["reason"] = $respone[0]["reason"];
$scope["KI"] = $respone[0]["KI"];
$scope["setDateSignature_request"] = toThaiNumber($respone[0]["setDateSignature_request"]);
$scope["setCodeSignature_request"] = $respone[0]["setCodeSignature_request"];
$scope["tel"] = toThaiNumber($respone[0]["tel"]);
$scope["amout"] = toThaiNumber(number_format($respone[0]["amout"]));
$scope["status_request"] = $respone[0]["status_request"];

if (($respone[0]['status_request'] > 1 && $respone[0]['status_request'] != 9) || $respone[0]['status_request'] == 0) {
  $scope['NAMETEACHER'] = $respone[0]['prenm'] . $respone[0]['poname'];
  $scope['reason_Teacher'] = $respone[0]['reason_Teacher'];
  $scope['setDateSignature_Teacher'] = toThaiNumber($respone[0]['setDateSignature_Teacher']);
  $scope['setCodeSignature_Teacher'] = $respone[0]['setCodeSignature_Teacher'];
} else {
  $scope['reason_Teacher'] = '';
  $scope['NAMETEACHER'] = '';
  $scope['setDataImages_Teacher'] = '';
}

if (($respone[0]['status_request'] > 2 && $respone[0]['status_request'] != 9) || $respone[0]['status_request'] == 0) {
  if ($respone[0]['status_request'] != 0) {
    $scope['reason_Tabian'] = "เห็นควรชำระเงิน";
  } else {
    $scope['reason_Tabian'] = "ไม่เห็นควรชำระเงิน เนื่องจาก" . $respone[0]['reason_Tabian'];
  }
  $scope['setDataImages_Tabian'] = $respone[0]['setDataImages_Tabian'];
  // $scope['setDateSignature_Tabian'] = toThaiNumber($respone[0]['setDateSignature_Tabian']);
  $scope['setDateSignature_Tabian'] = explode(" ", $respone[0]['setDateSignature_Tabian']);
  $scope['setDateSignature_TabianDAY'] = toThaiNumber($scope['setDateSignature_Tabian'][0]);
  $scope['setDateSignature_TabianMONTH'] = toMonth($scope['setDateSignature_Tabian'][1]);
  $scope['setDateSignature_TabianYEAR'] = toThaiNumber($scope['setDateSignature_Tabian'][2]);
  $scope['setDateSignature_Tabian'] = toThaiNumber($scope['setDateSignature_Tabian']);
  $scope['setCodeSignature_Tabian'] = $respone[0]['setCodeSignature_Tabian'];
} else {
  $scope['reason_Tabian'] = "เห็นควรชำระเงิน";
  $scope['setDateSignature_Tabian'] = '........../....................../.............';
  $scope['setCodeSignature_Tabian'] = '';
}

$currentdatetime = $respone[0]["setDateSignature_request"];
$currentdatetime = explode(" ", $currentdatetime);
$currentdatetime = toMonth($currentdatetime);
$currentdatetime = toThaiNumber($currentdatetime);
$scope['currentday'] = $currentdatetime[0];
$scope['currentmonth'] = $currentdatetime[1];
$scope['currentyear'] = $currentdatetime[2];

if ($scope["Type_request"] == "1") {
  $scope["Type_requestname"] = "รักษาสภาพการเป็นศึกษา";
} else {
  $scope["Type_requestname"] = "คืนสภาพและรักษาสภาพการเป็นศึกษา";
}

if ($respone[0]['status_request'] > 3 && $respone[0]['status_request'] != 9) {
  $scope['BILL_MONEY'] = toThaiNumber(number_format($respone[0]['BILL_MONEY']));
  $scope['BILL'] = toThaiNumber($respone[0]['BILL']);
  $scope['BILL_NO'] = toThaiNumber($respone[0]['BILL_NO']);
  $BILL_DATE = $respone[0]['BILL_DATE'];
  $BILL_DATE = explode("/", $BILL_DATE);
  $scope['BILL_DAY'] = toThaiNumber($BILL_DATE[0]);
  $scope['BILL_MONTH'] = $monthTH[($BILL_DATE[1])+0];
  $scope['BILL_YEAR'] = toThaiNumber(($BILL_DATE[2] - 543));
  $BILL_DATE = ($BILL_DATE[2] - 543) . "-" . $BILL_DATE[1] . "-" . $BILL_DATE[0];
  $BILL_DATE = strtotime($BILL_DATE);
  $BILL_DATE = thai_date_and_time($BILL_DATE);
  $BILL_DATE = toThaiNumber($BILL_DATE);
  $scope['BILL_DATE'] = $BILL_DATE;
  $scope['SIGNATURE_MONEY'] = 'https://e-par.kpru.ac.th/timeKPRU/contents/signature/1620500086527.jpg';
  $scope['SETCODESIGNATURE_MONEY'] = GenarateSignature();
  $scope['SETCODESIGNATURE_MONEY'] = $scope['SETCODESIGNATURE_MONEY']['setCodeSignature'];
} else {
  $scope['BILL_MONEY'] = "";
  $scope['BILL'] = "";
  $scope['BILL_NO'] = "";
  $scope['BILL_DATE'] = "";
}

if ($respone[0]['status_request'] > 4 && $respone[0]['status_request'] != 9) {
  // $scope['checkbox_allow'] = '<img src="https://mua.kpru.ac.th/apipostpone/PostponePDF/images/checkbox.png" width="10" />';
  // $scope['checkbox_notallow'] = '<img src="https://mua.kpru.ac.th/apipostpone/PostponePDF/images/blank-checkbox.png" width="10" />';
  $scope['checkbox_allow'] = 'images/checkbox.png';
  $scope['checkbox_notallow'] = 'images/blank-checkbox.png';
  $scope['setDataImages_Vicerector'] = $respone[0]['setDataImages_Vicerector'];
  $scope['setDateSignature_Vicerector'] = explode(" ", $respone[0]['setDateSignature_Vicerector']);
  $scope['setDateSignature_VicerectorDAY'] = toThaiNumber($scope['setDateSignature_Vicerector'][0]);
  $scope['setDateSignature_VicerectorMONTH'] = toMonth($scope['setDateSignature_Vicerector'][1]);
  $scope['setDateSignature_VicerectorYEAR'] = toThaiNumber($scope['setDateSignature_Vicerector'][2]);
  $scope['setDateSignature_Vicerector'] = toThaiNumber($respone[0]['setDateSignature_Vicerector']);
  $scope['setCodeSignature_Vicerector'] = $respone[0]['setCodeSignature_Vicerector'];
} else {
  // $scope['checkbox_allow'] = '<img src="https://mua.kpru.ac.th/apipostpone/PostponePDF/images/blank-checkbox.png" width="10" />';
  // $scope['checkbox_notallow'] = '<img src="https://mua.kpru.ac.th/apipostpone/PostponePDF/images/blank-checkbox.png" width="10" />';
  $scope['checkbox_allow'] = 'images/blank-checkbox.png';
  $scope['checkbox_notallow'] = 'images/blank-checkbox.png';
  $scope['setDataImages_Vicerector'] = '';
  $scope['setDateSignature_Vicerector'] = '';
  $scope['setCodeSignature_Vicerector'] = '';
}

if($respone[0]["print_status"] == "1"){
  $scope['setDateSignature_print'] = toThaiNumber($respone[0]["setDateSignature_print"]);
  $scope['setCodeSignature_print'] = $respone[0]["setCodeSignature_print"];
}

require_once('tcpdf.php');

$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8');

$pdf->SetCreator('คำร้องขอคืนสภาพและรักษาสภาพการเป็นนักศึกษา');
$pdf->SetAuthor('คำร้องขอคืนสภาพและรักษาสภาพการเป็นนักศึกษา');
$pdf->SetTitle('เอกสารประกอบ');
$pdf->SetSubject('คำร้องขอคืนสภาพและรักษาสภาพการเป็นนักศึกษา');
$pdf->SetKeywords('คำร้องขอคืนสภาพและรักษาสภาพการเป็นนักศึกษา, kpru, PDF, example, guide');

$pdf->setHeaderFont(array('thsarabun', 'B', 12));

$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, 'APPLICATION FORM FOR RESUME/MAINTAIN STUDENT STATUS', 'คำร้องขอคืนสภาพและรักษาสภาพการเป็นนักศึกษา', array(0, 0, 255), array(0, 64, 128));
$pdf->setFooterData(array(0, 64, 0), array(0, 64, 128));

// $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetMargins(10, 15, 10);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

$pdf->setPrintHeader(false); //สำหรับปิด header
$pdf->setPrintFooter(false); //สำหรับปิด header

$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

$pdf->AddPage();
$pdf->Ln(8);
$pdf->SetFont('thsarabun', 'B', 16);
$pdf->SetFont('thsarabun', 'B', 16);
$pdf->Cell(0, 0, 'คำร้อง', 0, 1, 'C', 0, '', 0);
$pdf->SetFont('thsarabun', 'B', 16);
$pdf->Cell(0, 0, 'ขอคืนสภาพและรักษาสภาพการเป็นนักศึกษา', 0, 1, 'C', 0, '', 0);
$pdf->SetFont('thsarabun', 'B', 15);
$pdf->Cell(0, 0, 'APPLICATION FORM FOR RESUME/MAINTAIN STUDENT STATUS', 0, 1, 'C', 0, '', 0);
$pdf->SetFont('thsarabun', '', 16);
$pdf->Cell(0, 0, 'มหาวิทยาลัยราชภัฎกำแพงเพชร', 0, 1, 'R', 0, '', 0);
$pdf->SetFont('thsarabun', '', 15);
$pdf->Cell(0, 0, 'Kamphaeng Phet Rajabhat University', 0, 1, 'R', 0, '', 0);
$pdf->SetFont('thsarabun', '', 16);
$pdf->Cell(0, 0, "วันที่/Date.............เดือน/Month...................................พ.ศ./Year.....................", 0, 0, 'R');
$pdf->Ln(-1);
$pdf->SetFont('thsarabun', 'B', 16);
$pdf->SetTextColor($setFontColour_answer[0], $setFontColour_answer[1], $setFontColour_answer[2]);
$pdf->Cell(91, 0, '', 0, 0, 'L');
$pdf->Cell(0, 0, $scope['currentday'], 0, 0, 'L');
$pdf->SetTextColor($setFontColour_main[0], $setFontColour_main[1], $setFontColour_main[2]);
$pdf->SetFont('thsarabun', '', 16);

$pdf->Ln(0);
$pdf->SetFont('thsarabun', 'B', 16);
$pdf->SetTextColor($setFontColour_answer[0], $setFontColour_answer[1], $setFontColour_answer[2]);
$pdf->Cell(124, 0, '', 0, 0, 'L');
$pdf->Cell(0, 0, $scope['currentmonth'], 0, 0, 'L');
$pdf->SetTextColor($setFontColour_main[0], $setFontColour_main[1], $setFontColour_main[2]);
$pdf->SetFont('thsarabun', '', 16);

$pdf->Ln(0);
$pdf->SetFont('thsarabun', 'B', 16);
$pdf->SetTextColor($setFontColour_answer[0], $setFontColour_answer[1], $setFontColour_answer[2]);
$pdf->Cell(171, 0, '', 0, 0, 'L');
$pdf->Cell(0, 0, $scope['currentyear'], 0, 0, 'L');
$pdf->SetTextColor($setFontColour_main[0], $setFontColour_main[1], $setFontColour_main[2]);
$pdf->SetFont('thsarabun', '', 16);
$pdf->Ln();


if ($scope['Type_request'] == "1") {
  $pdf->SetFont('thsarabun', '', 16);
  $pdf->Cell(0, 0, "เรื่อง", 0, 0, 'L');
  $pdf->Ln(0.5);
  $pdf->SetFont('thsarabun', '', 15);
  $pdf->Cell(7, 0, '', 0, 0, 'L');
  $pdf->Cell(23, 0, "/Subject: ".$pdf->Image("images/checkbox.png", $pdf->GetX() + 18, $pdf->GetY() + 2, 3.5), 0, 0, 'L');
  $pdf->SetFont('thsarabun', '', 16);
  $pdf->Cell(20, 0, 'ขอรักษาสภาพการเป็นศึกษา', 0, 0, 'L');
  $pdf->Ln(0.2);
  $pdf->SetFont('thsarabun', '', 15);
  $pdf->Cell(72, 0, '', 0, 0, 'L');
  $pdf->Cell(30, 0, '/Request to maintain a student status ภาคเรียนที่..............................................', 0, 0, 'L');
  $pdf->Ln(-1.5);
  $pdf->SetFont('thsarabun', 'B', 16);
  $pdf->SetTextColor($setFontColour_answer[0], $setFontColour_answer[1], $setFontColour_answer[2]);
  $pdf->Cell(151, 0, '', 0, 0, 'L');
  $pdf->Cell(0, 0, $scope['Term_request'], 0, 0, 'L');
  $pdf->SetTextColor($setFontColour_main[0], $setFontColour_main[1], $setFontColour_main[2]);
  $pdf->SetFont('thsarabun', '', 16);
  $pdf->Ln();
  
  $pdf->Cell(0, 0, "", 0, 0, 'L');
  $pdf->Ln(0.5);
  $pdf->SetFont('thsarabun', '', 15);
  $pdf->Cell(7, 0, '', 0, 0, 'L');
  $pdf->Cell(23, 0, $pdf->Image("images/blank-checkbox.png", $pdf->GetX() + 18, $pdf->GetY() + 2, 3.5), 0, 0, 'L');
  $pdf->SetFont('thsarabun', '', 16);
  $pdf->Cell(20, 0, 'ขอคืนสภาพและรักษาสภาพการเป็นศึกษา', 0, 0, 'L');
  $pdf->Ln(0.2);
  $pdf->SetFont('thsarabun', '', 15);
  $pdf->Cell(92, 0, '', 0, 0, 'L');
  $pdf->Cell(0, 0, '/Request to resume a student status', 0, 0, 'L');
  $pdf->Ln();
  
  // $pdf->Cell(170, 0, 'เรื่อง/Subject' . $pdf->Image("images/checkbox.png", $pdf->GetX() + 16, $pdf->GetY() + 2, 3.5) . 'ขอรักษาสภาพการเป็นศึกษา ภาคเรียนที่ ' . $scope['Term_request'], 0, 1, 'L', 0, '', 0);
  // $pdf->Cell(170, 0, $pdf->Image("images/blank-checkbox.png", $pdf->GetX() + 16, $pdf->GetY() + 2, 3.5) . '                  ขอคืนสภาพและรักษาสภาพการเป็นศึกษา ', 0, 1, 'L', 0, '', 0);
} else {
  $pdf->SetFont('thsarabun', '', 16);
  $pdf->Cell(0, 0, "เรื่อง", 0, 0, 'L');
  $pdf->Ln(0.5);
  $pdf->SetFont('thsarabun', '', 15);
  $pdf->Cell(7, 0, '', 0, 0, 'L');
  $pdf->Cell(23, 0, "/Subject: ".$pdf->Image("images/blank-checkbox.png", $pdf->GetX() + 18, $pdf->GetY() + 2, 3.5), 0, 0, 'L');
  $pdf->SetFont('thsarabun', '', 16);
  $pdf->Cell(20, 0, 'ขอรักษาสภาพการเป็นศึกษา', 0, 0, 'L');
  $pdf->Ln(0.2);
  $pdf->SetFont('thsarabun', '', 15);
  $pdf->Cell(72, 0, '', 0, 0, 'L');
  $pdf->Cell(30, 0, '/Request to maintain a student status ภาคเรียนที่..............................................', 0, 0, 'L');
  $pdf->Ln(-1.5);
  $pdf->SetFont('thsarabun', 'B', 16);
  $pdf->SetTextColor($setFontColour_answer[0], $setFontColour_answer[1], $setFontColour_answer[2]);
  $pdf->Cell(151, 0, '', 0, 0, 'L');
  $pdf->Cell(0, 0, $scope['Term_request'], 0, 0, 'L');
  $pdf->SetTextColor($setFontColour_main[0], $setFontColour_main[1], $setFontColour_main[2]);
  $pdf->SetFont('thsarabun', '', 16);
  $pdf->Ln();
  
  $pdf->Cell(0, 0, "", 0, 0, 'L');
  $pdf->Ln(0.5);
  $pdf->SetFont('thsarabun', '', 15);
  $pdf->Cell(7, 0, '', 0, 0, 'L');
  $pdf->Cell(23, 0, $pdf->Image("images/checkbox.png", $pdf->GetX() + 18, $pdf->GetY() + 2, 3.5), 0, 0, 'L');
  $pdf->SetFont('thsarabun', '', 16);
  $pdf->Cell(20, 0, 'ขอคืนสภาพและรักษาสภาพการเป็นศึกษา', 0, 0, 'L');
  $pdf->Ln(0.2);
  $pdf->SetFont('thsarabun', '', 15);
  $pdf->Cell(92, 0, '', 0, 0, 'L');
  $pdf->Cell(0, 0, '/Request to resume a student status', 0, 0, 'L');
  $pdf->Ln();
  // $pdf->Cell(170, 0, 'เรื่อง/Subject' . $pdf->Image("images/blank-checkbox.png", $pdf->GetX() + 16, $pdf->GetY() + 2, 3.5) . 'ขอรักษาสภาพการเป็นศึกษา ภาคเรียนที่ ' . $scope['Term_request'], 0, 1, 'L', 0, '', 0);
  // $pdf->Cell(170, 0, $pdf->Image("images/checkbox.png", $pdf->GetX() + 16, $pdf->GetY() + 2, 3.5) . '                  ขอคืนสภาพและรักษาสภาพการเป็นศึกษา ', 0, 1, 'L', 0, '', 0);
}
$pdf->SetFont('thsarabun', '', 16);
$pdf->Cell(0, 0, "เรียน", 0, 0, 'L');
$pdf->Ln(0.4);
$pdf->SetFont('thsarabun', '', 15);
$pdf->Cell(8, 0, '', 0, 0, 'L');
$pdf->Cell(16, 0, "/To: ", 0, 0, 'L');
$pdf->SetFont('thsarabun', '', 16);
$pdf->Cell(62, 0, 'อธิการบดีมหาวิทยาลัยราชภัฎกำแพงเพชร', 0, 0, 'L');
$pdf->Ln(0.4);
$pdf->SetFont('thsarabun', '', 15);
$pdf->Cell(86, 0, '', 0, 0, 'L');
$pdf->Cell(0, 0, '/President', 0, 0, 'L');
$pdf->SetFont('thsarabun', '', 16);
$pdf->Ln();

$pdf->SetFont('thsarabun', '', 16);
$pdf->Cell(24, 0, '', 0, 0, 'L');
$pdf->Cell(48, 0, 'ด้วยข้าพเจ้า (นาย/นาง/นางสาว)', 0, 0, 'L');
$pdf->Ln(0.5);
$pdf->SetFont('thsarabun', '', 15);
$pdf->Cell(72, 0, '', 0, 0, 'L');
$pdf->Cell(20, 0, '/Name (Mr./Mrs./Miss).................................................................................................', 0, 0, 'L');
$pdf->Ln(-1);
$pdf->SetFont('thsarabun', 'B', 16);
$pdf->SetTextColor($setFontColour_answer[0], $setFontColour_answer[1], $setFontColour_answer[2]);
$pdf->Cell(110, 0, '', 0, 0, 'L');
$pdf->Cell(0, 0, $scope['PNAME'] . $scope['NAME'], 0, 0, 'L');
$pdf->SetFont('thsarabun', '', 16);
$pdf->SetTextColor($setFontColour_main[0], $setFontColour_main[1], $setFontColour_main[2]);
$pdf->Ln();

if (($scope['std_type'] == "1" || $scope['std_type'] == "5") && ($scope["LEVEL1"] == "2" || $scope["LEVEL1"] == "5" || $scope["LEVEL1"] == "s")) {
  $pdf->Image("images/check-box.png", $pdf->GetX() + 1, $pdf->GetY() + 2, 3.5);
  $pdf->Image("images/blank-check-box.png", $pdf->GetX() + 53, $pdf->GetY() + 2, 3.5);
  $pdf->Image("images/blank-check-box.png", $pdf->GetX() + 112, $pdf->GetY() + 2, 3.5);
  $pdf->SetFont('thsarabun', '', 16);
  $pdf->Cell(6, 0, '', 0, 0, 'L');
  $pdf->Cell(20, 0, 'ภาคปกติ                                ภาคกศ.บป.                                  ปริญญาตรี ๔ ปี', 0, 0, 'L');
  $pdf->Ln(0.3);
  $pdf->SetFont('thsarabun', '', 15);
  $pdf->Cell(19.4, 0, '', 0, 0, 'L');
  $pdf->Cell(20, 0, "/Regular Student                          /Weekend Student                              /Bachelor's Degree ๔ Years", 0, 0, 'L');
  $pdf->SetFont('thsarabun', '', 16);
  $pdf->Ln();

//   // $pdf->Cell(170, 0, '      ภาคปกติ        ภาคกศ.บป.         ปริญญาตรี ๔ ปี        ปริญญาตรี ๕ ปี        ปริญญาตรี ๒ ปีหลัง                                                                ' .
//   //   $pdf->Image("images/check-box.png", $pdf->GetX() + 1, $pdf->GetY() + 2, 3.5) .
//   //   $pdf->Image("images/blank-check-box.png", $pdf->GetX() + 25, $pdf->GetY() + 2, 3.5) .
//   //   $pdf->Image("images/blank-check-box.png", $pdf->GetX() + 53, $pdf->GetY() + 2, 3.5) .
//   //   $pdf->Image("images/blank-check-box.png", $pdf->GetX() + 86, $pdf->GetY() + 2, 3.5) .
//   //   $pdf->Image("images/check-box.png", $pdf->GetX() + 119, $pdf->GetY() + 2, 3.5), 0, 1, 'L', 0, '', 0);
// } else if (($scope['std_type'] == "1" || $scope['std_type'] == "5") && $scope["LEVEL1"] == "s") {
//   $pdf->Image("images/check-box.png", $pdf->GetX() + 1, $pdf->GetY() + 2, 3.5);
//   $pdf->Image("images/blank-check-box.png", $pdf->GetX() + 53, $pdf->GetY() + 2, 3.5);
//   $pdf->Image("images/blank-check-box.png", $pdf->GetX() + 112, $pdf->GetY() + 2, 3.5);
//   $pdf->SetFont('thsarabun', '', 16);
//   $pdf->Cell(6, 0, '', 0, 0, 'L');
//   $pdf->Cell(20, 0, 'ภาคปกติ                                ภาคกศ.บป.                                  ปริญญาตรี ๔ ปี', 0, 0, 'L');
//   $pdf->Ln(0.3);
//   $pdf->SetFont('thsarabun', '', 15);
//   $pdf->Cell(19.4, 0, '', 0, 0, 'L');
//   $pdf->Cell(20, 0, "/Regular Student                          /Weekend Student                              /Bachelor's Degree ๔ Years", 0, 0, 'L');
//   $pdf->SetFont('thsarabun', '', 16);
//   $pdf->Ln();

//   // $pdf->Cell(170, 0, '      ภาคปกติ        ภาคกศ.บป.         ปริญญาตรี ๔ ปี        ปริญญาตรี ๕ ปี        ปริญญาตรี ๒ ปีหลัง                                                                ' .
//   //   $pdf->Image("images/check-box.png", $pdf->GetX() + 1, $pdf->GetY() + 2, 3.5) .
//   //   $pdf->Image("images/blank-check-box.png", $pdf->GetX() + 25, $pdf->GetY() + 2, 3.5) .
//   //   $pdf->Image("images/blank-check-box.png", $pdf->GetX() + 53, $pdf->GetY() + 2, 3.5) .
//   //   $pdf->Image("images/check-box.png", $pdf->GetX() + 86, $pdf->GetY() + 2, 3.5) .
//   //   $pdf->Image("images/blank-check-box.png", $pdf->GetX() + 119, $pdf->GetY() + 2, 3.5), 0, 1, 'L', 0, '', 0);
} else if ($scope['std_type'] == "1" || $scope['std_type'] == "5") {
  $pdf->Image("images/check-box.png", $pdf->GetX() + 1, $pdf->GetY() + 2, 3.5);
  $pdf->Image("images/blank-check-box.png", $pdf->GetX() + 53, $pdf->GetY() + 2, 3.5);
  $pdf->Image("images/check-box.png", $pdf->GetX() + 112, $pdf->GetY() + 2, 3.5);
  $pdf->SetFont('thsarabun', '', 16);
  $pdf->Cell(6, 0, '', 0, 0, 'L');
  $pdf->Cell(20, 0, 'ภาคปกติ                                ภาคกศ.บป.                                  ปริญญาตรี ๔ ปี', 0, 0, 'L');
  $pdf->Ln(0.3);
  $pdf->SetFont('thsarabun', '', 15);
  $pdf->Cell(19.4, 0, '', 0, 0, 'L');
  $pdf->Cell(20, 0, "/Regular Student                          /Weekend Student                              /Bachelor's Degree ๔ Years", 0, 0, 'L');
  $pdf->SetFont('thsarabun', '', 16);
  $pdf->Ln();
} else if (($scope['std_type'] == "2" || $scope['std_type'] == "3") && ($scope["LEVEL1"] == "2" || $scope["LEVEL1"] == "5" || $scope["LEVEL1"] == "s")) {
  $pdf->Image("images/blank-check-box.png", $pdf->GetX() + 1, $pdf->GetY() + 2, 3.5);
  $pdf->Image("images/check-box.png", $pdf->GetX() + 53, $pdf->GetY() + 2, 3.5);
  $pdf->Image("images/blank-check-box.png", $pdf->GetX() + 112, $pdf->GetY() + 2, 3.5);
  $pdf->SetFont('thsarabun', '', 16);
  $pdf->Cell(6, 0, '', 0, 0, 'L');
  $pdf->Cell(20, 0, 'ภาคปกติ                                ภาคกศ.บป.                                  ปริญญาตรี ๔ ปี', 0, 0, 'L');
  $pdf->Ln(0.3);
  $pdf->SetFont('thsarabun', '', 15);
  $pdf->Cell(19.4, 0, '', 0, 0, 'L');
  $pdf->Cell(20, 0, "/Regular Student                          /Weekend Student                              /Bachelor's Degree ๔ Years", 0, 0, 'L');
  $pdf->SetFont('thsarabun', '', 16);
  $pdf->Ln();
//   // $pdf->Cell(170, 0, '      ภาคปกติ        ภาคกศ.บป.         ปริญญาตรี ๔ ปี        ปริญญาตรี ๕ ปี        ปริญญาตรี ๒ ปีหลัง                                                                ' .
//   //   $pdf->Image("images/blank-check-box.png", $pdf->GetX() + 1, $pdf->GetY() + 2, 3.5) .
//   //   $pdf->Image("images/check-box.png", $pdf->GetX() + 25, $pdf->GetY() + 2, 3.5) .
//   //   $pdf->Image("images/blank-check-box.png", $pdf->GetX() + 53, $pdf->GetY() + 2, 3.5) .
//   //   $pdf->Image("images/blank-check-box.png", $pdf->GetX() + 86, $pdf->GetY() + 2, 3.5) .
//   //   $pdf->Image("images/check-box.png", $pdf->GetX() + 119, $pdf->GetY() + 2, 3.5), 0, 1, 'L', 0, '', 0);
// } else if (($scope['std_type'] == "2" || $scope['std_type'] == "3") && $scope["LEVEL1"] == "s") {
//   $pdf->Cell(170, 0, '      ภาคปกติ        ภาคกศ.บป.         ปริญญาตรี ๔ ปี        ปริญญาตรี ๕ ปี        ปริญญาตรี ๒ ปีหลัง                                                                ' .
//     $pdf->Image("images/blank-check-box.png", $pdf->GetX() + 1, $pdf->GetY() + 2, 3.5) .
//     $pdf->Image("images/check-box.png", $pdf->GetX() + 25, $pdf->GetY() + 2, 3.5) .
//     $pdf->Image("images/blank-check-box.png", $pdf->GetX() + 53, $pdf->GetY() + 2, 3.5) .
//     $pdf->Image("images/check-box.png", $pdf->GetX() + 86, $pdf->GetY() + 2, 3.5) .
//     $pdf->Image("images/blank-check-box.png", $pdf->GetX() + 119, $pdf->GetY() + 2, 3.5), 0, 1, 'L', 0, '', 0);
} else if ($scope['std_type'] == "2" || $scope['std_type'] == "3") {
  $pdf->Image("images/blank-check-box.png", $pdf->GetX() + 1, $pdf->GetY() + 2, 3.5);
  $pdf->Image("images/check-box.png", $pdf->GetX() + 53, $pdf->GetY() + 2, 3.5);
  $pdf->Image("images/check-box.png", $pdf->GetX() + 112, $pdf->GetY() + 2, 3.5);
  $pdf->SetFont('thsarabun', '', 16);
  $pdf->Cell(6, 0, '', 0, 0, 'L');
  $pdf->Cell(20, 0, 'ภาคปกติ                                ภาคกศ.บป.                                  ปริญญาตรี ๔ ปี', 0, 0, 'L');
  $pdf->Ln(0.3);
  $pdf->SetFont('thsarabun', '', 15);
  $pdf->Cell(19.4, 0, '', 0, 0, 'L');
  $pdf->Cell(20, 0, "/Regular Student                          /Weekend Student                              /Bachelor's Degree ๔ Years", 0, 0, 'L');
  $pdf->SetFont('thsarabun', '', 16);
  $pdf->Ln();
  // $pdf->Cell(170, 0, '      ภาคปกติ        ภาคกศ.บป.         ปริญญาตรี ๔ ปี        ปริญญาตรี ๕ ปี        ปริญญาตรี ๒ ปีหลัง                                                                ' .
  //   $pdf->Image("images/blank-check-box.png", $pdf->GetX() + 1, $pdf->GetY() + 2, 3.5) .
  //   $pdf->Image("images/check-box.png", $pdf->GetX() + 25, $pdf->GetY() + 2, 3.5) .
  //   $pdf->Image("images/check-box.png", $pdf->GetX() + 53, $pdf->GetY() + 2, 3.5) .
  //   $pdf->Image("images/blank-check-box.png", $pdf->GetX() + 86, $pdf->GetY() + 2, 3.5) .
  //   $pdf->Image("images/blank-check-box.png", $pdf->GetX() + 119, $pdf->GetY() + 2, 3.5), 0, 1, 'L', 0, '', 0);
} else {
  $pdf->Image("images/blank-check-box.png", $pdf->GetX() + 1, $pdf->GetY() + 2, 3.5);
  $pdf->Image("images/blank-check-box.png", $pdf->GetX() + 53, $pdf->GetY() + 2, 3.5);
  $pdf->Image("images/blank-check-box.png", $pdf->GetX() + 112, $pdf->GetY() + 2, 3.5);
  $pdf->SetFont('thsarabun', '', 16);
  $pdf->Cell(6, 0, '', 0, 0, 'L');
  $pdf->Cell(20, 0, 'ภาคปกติ                                ภาคกศ.บป.                                  ปริญญาตรี ๔ ปี', 0, 0, 'L');
  $pdf->Ln(0.3);
  $pdf->SetFont('thsarabun', '', 15);
  $pdf->Cell(19.4, 0, '', 0, 0, 'L');
  $pdf->Cell(20, 0, "/Regular Student                          /Weekend Student                              /Bachelor's Degree ๔ Years", 0, 0, 'L');
  $pdf->SetFont('thsarabun', '', 16);
  $pdf->Ln();
  // $pdf->Cell(170, 0, '      ภาคปกติ        ภาคกศ.บป.         ปริญญาตรี ๔ ปี        ปริญญาตรี ๕ ปี        ปริญญาตรี ๒ ปีหลัง                                                                ' .
  //   $pdf->Image("images/blank-check-box.png", $pdf->GetX() + 1, $pdf->GetY() + 2, 3.5) .
  //   $pdf->Image("images/blank-check-box.png", $pdf->GetX() + 25, $pdf->GetY() + 2, 3.5) .
  //   $pdf->Image("images/blank-check-box.png", $pdf->GetX() + 53, $pdf->GetY() + 2, 3.5) .
  //   $pdf->Image("images/blank-check-box.png", $pdf->GetX() + 86, $pdf->GetY() + 2, 3.5) .
  //   $pdf->Image("images/blank-check-box.png", $pdf->GetX() + 119, $pdf->GetY() + 2, 3.5), 0, 1, 'L', 0, '', 0);
}

if($scope["LEVEL1"] == "s"){
  $pdf->Image("images/check-box.png", $pdf->GetX() + 1, $pdf->GetY() + 2, 3.5);
  $pdf->Image("images/blank-check-box.png", $pdf->GetX() + 77, $pdf->GetY() + 2, 3.5);
  $pdf->SetFont('thsarabun', '', 16);
  $pdf->Cell(6, 0, '', 0, 0, 'L');
  $pdf->Cell(20, 0, 'ปริญญาตรี ๕ ปี                                           ปริญญาตรี ๒ ปีหลัง', 0, 0, 'L');
  $pdf->Ln(0.3);
  $pdf->SetFont('thsarabun', '', 15);
  $pdf->Cell(29, 0, '', 0, 0, 'L');
  $pdf->Cell(20, 0, "/Bachelor's Degree ๕ Years                                   /Bachelor's Degree ๒ Years Continuing Program", 0, 0, 'L');
  $pdf->SetFont('thsarabun', '', 16);
  $pdf->Ln();
}else if($scope["LEVEL1"] == "2" || $scope["LEVEL1"] == "5"){
  $pdf->Image("images/blank-check-box.png", $pdf->GetX() + 1, $pdf->GetY() + 2, 3.5);
  $pdf->Image("images/check-box.png", $pdf->GetX() + 77, $pdf->GetY() + 2, 3.5);
  $pdf->SetFont('thsarabun', '', 16);
  $pdf->Cell(6, 0, '', 0, 0, 'L');
  $pdf->Cell(20, 0, 'ปริญญาตรี ๕ ปี                                           ปริญญาตรี ๒ ปีหลัง', 0, 0, 'L');
  $pdf->Ln(0.3);
  $pdf->SetFont('thsarabun', '', 15);
  $pdf->Cell(29, 0, '', 0, 0, 'L');
  $pdf->Cell(20, 0, "/Bachelor's Degree ๕ Years                                   /Bachelor's Degree ๒ Years Continuing Program", 0, 0, 'L');
  $pdf->SetFont('thsarabun', '', 16);
  $pdf->Ln();
}else{
  $pdf->Image("images/blank-check-box.png", $pdf->GetX() + 1, $pdf->GetY() + 2, 3.5);
  $pdf->Image("images/blank-check-box.png", $pdf->GetX() + 77, $pdf->GetY() + 2, 3.5);
  $pdf->SetFont('thsarabun', '', 16);
  $pdf->Cell(6, 0, '', 0, 0, 'L');
  $pdf->Cell(20, 0, 'ปริญญาตรี ๕ ปี                                           ปริญญาตรี ๒ ปีหลัง', 0, 0, 'L');
  $pdf->Ln(0.3);
  $pdf->SetFont('thsarabun', '', 15);
  $pdf->Cell(29.5, 0, '', 0, 0, 'L');
  $pdf->Cell(20, 0, "/Bachelor's Degree ๕ Years                                  /Bachelor's Degree ๒ Years Continuing Program", 0, 0, 'L');
  $pdf->SetFont('thsarabun', '', 16);
  $pdf->Ln();
}

if ($scope['KI'] == "6") {
  $pdf->Image("images/check-box.png", $pdf->GetX() + 1, $pdf->GetY() + 2, 3.5);
  $pdf->Image("images/blank-check-box.png", $pdf->GetX() + 53, $pdf->GetY() + 2, 3.5);
  $pdf->Image("images/blank-check-box.png", $pdf->GetX() + 112, $pdf->GetY() + 2, 3.5);
  $pdf->SetFont('thsarabun', '', 16);
  $pdf->Cell(6, 0, '', 0, 0, 'L');
  $pdf->Cell(20, 0, 'ประกาศนียบัตรบัณฑิตศึกษา       ปริญญาโท              ปริญญาเอก', 0, 0, 'L');
  $pdf->Ln(0.3);
  $pdf->SetFont('thsarabun', '', 15);
  $pdf->Cell(19.4, 0, '', 0, 0, 'L');
  $pdf->Cell(20, 0, "/Graduate Diploma       /Master's Degree              /Ph.D", 0, 0, 'L');
  $pdf->SetFont('thsarabun', '', 16);
  $pdf->Ln();
  // $pdf->Cell(170, 0, '      ประกาศนียบัตรบัณฑิตศึกษา       ปริญญาโท              ปริญญาเอก                                                                  ' .
  //   $pdf->Image("images/check-box.png", $pdf->GetX() + 1, $pdf->GetY() + 2, 3.5) .
  //   $pdf->Image("images/blank-check-box.png", $pdf->GetX() + 53, $pdf->GetY() + 2, 3.5) .
  //   $pdf->Image("images/blank-check-box.png", $pdf->GetX() + 86, $pdf->GetY() + 2, 3.5), 0, 1, 'L', 0, '', 0);
} else if ($scope['KI'] == "7") {
  $pdf->Image("images/blank-check-box.png", $pdf->GetX() + 1, $pdf->GetY() + 2, 3.5);
  $pdf->Image("images/blank-check-box.png", $pdf->GetX() + 53, $pdf->GetY() + 2, 3.5);
  $pdf->Image("images/check-box.png", $pdf->GetX() + 112, $pdf->GetY() + 2, 3.5);
  $pdf->SetFont('thsarabun', '', 16);
  $pdf->Cell(6, 0, '', 0, 0, 'L');
  $pdf->Cell(20, 0, 'ประกาศนียบัตรบัณฑิตศึกษา       ปริญญาโท              ปริญญาเอก', 0, 0, 'L');
  $pdf->Ln(0.3);
  $pdf->SetFont('thsarabun', '', 15);
  $pdf->Cell(19.4, 0, '', 0, 0, 'L');
  $pdf->Cell(20, 0, "/Graduate Diploma       /Master's Degree              /Ph.D", 0, 0, 'L');
  $pdf->SetFont('thsarabun', '', 16);
  $pdf->Ln();
  // $pdf->Cell(170, 0, '      ประกาศนียบัตรบัณฑิตศึกษา       ปริญญาโท              ปริญญาเอก                                                                  ' .
  //   $pdf->Image("images/blank-check-box.png", $pdf->GetX() + 1, $pdf->GetY() + 2, 3.5) .
  //   $pdf->Image("images/blank-check-box.png", $pdf->GetX() + 53, $pdf->GetY() + 2, 3.5) .
  //   $pdf->Image("images/check-box.png", $pdf->GetX() + 86, $pdf->GetY() + 2, 3.5), 0, 1, 'L', 0, '', 0);
} else if ($scope['KI'] == "4") {
  $pdf->Image("images/blank-check-box.png", $pdf->GetX() + 1, $pdf->GetY() + 2, 3.5);
  $pdf->Image("images/check-box.png", $pdf->GetX() + 53, $pdf->GetY() + 2, 3.5);
  $pdf->Image("images/blank-check-box.png", $pdf->GetX() + 112, $pdf->GetY() + 2, 3.5);
  $pdf->SetFont('thsarabun', '', 16);
  $pdf->Cell(6, 0, '', 0, 0, 'L');
  $pdf->Cell(20, 0, 'ประกาศนียบัตรบัณฑิตศึกษา       ปริญญาโท              ปริญญาเอก', 0, 0, 'L');
  $pdf->Ln(0.3);
  $pdf->SetFont('thsarabun', '', 15);
  $pdf->Cell(19.4, 0, '', 0, 0, 'L');
  $pdf->Cell(20, 0, "/Graduate Diploma       /Master's Degree              /Ph.D", 0, 0, 'L');
  $pdf->SetFont('thsarabun', '', 16);
  $pdf->Ln();
  // $pdf->Cell(170, 0, '      ประกาศนียบัตรบัณฑิตศึกษา       ปริญญาโท              ปริญญาเอก                                                                  ' .
  //   $pdf->Image("images/blank-check-box.png", $pdf->GetX() + 1, $pdf->GetY() + 2, 3.5) .
  //   $pdf->Image("images/check-box.png", $pdf->GetX() + 53, $pdf->GetY() + 2, 3.5) .
  //   $pdf->Image("images/blank-check-box.png", $pdf->GetX() + 86, $pdf->GetY() + 2, 3.5), 0, 1, 'L', 0, '', 0);
} else {
  $pdf->Image("images/blank-check-box.png", $pdf->GetX() + 1, $pdf->GetY() + 2, 3.5);
  $pdf->Image("images/blank-check-box.png", $pdf->GetX() + 83, $pdf->GetY() + 2, 3.5);
  $pdf->Image("images/blank-check-box.png", $pdf->GetX() + 135, $pdf->GetY() + 2, 3.5);
  $pdf->SetFont('thsarabun', '', 16);
  $pdf->Cell(6, 0, '', 0, 0, 'L');
  $pdf->Cell(20, 0, 'ประกาศนียบัตรบัณฑิตศึกษา                                ปริญญาโท                              ปริญญาเอก', 0, 0, 'L');
  $pdf->Ln(0.3);
  $pdf->SetFont('thsarabun', '', 15);
  $pdf->Cell(48.5, 0, '', 0, 0, 'L');
  $pdf->Cell(20, 0, "/Graduate Diploma                      /Master's Degree                        /Ph.D", 0, 0, 'L');
  $pdf->SetFont('thsarabun', '', 16);
  $pdf->Ln();
  // $pdf->Cell(170, 0, '      ประกาศนียบัตรบัณฑิตศึกษา       ปริญญาโท              ปริญญาเอก                                                                  ' .
  //   $pdf->Image("images/blank-check-box.png", $pdf->GetX() + 1, $pdf->GetY() + 2, 3.5) .
  //   $pdf->Image("images/blank-check-box.png", $pdf->GetX() + 53, $pdf->GetY() + 2, 3.5) .
  //   $pdf->Image("images/blank-check-box.png", $pdf->GetX() + 86, $pdf->GetY() + 2, 3.5), 0, 1, 'L', 0, '', 0);
}
$pdf->Image("images/blank-check-box.png", $pdf->GetX() + 1, $pdf->GetY() + 2, 3.5);
$pdf->SetFont('thsarabun', '', 16);
$pdf->Cell(6, 0, '', 0, 0, 'L');
$pdf->Cell(20, 0, 'อื่นๆ', 0, 0, 'L');
$pdf->Ln(0.3);
$pdf->SetFont('thsarabun', '', 15);
$pdf->Cell(13, 0, '', 0, 0, 'L');
$pdf->Cell(20, 0, "/Other..........................................................................", 0, 0, 'L');
$pdf->SetFont('thsarabun', '', 15);
$pdf->Ln();

$pdf->Cell(20, 0, 'โปรแกรมวิชา                                                         หมู่เรียน                             รหัสประจําตัว', 0, 0, 'L');
$pdf->Ln(0.3);
$pdf->SetFont('thsarabun', '', 14);
$pdf->Cell(18.8, 0, '', 0, 0, 'L');
$pdf->Cell(20, 0, '/Program of study................................................          /Class No........................                  /Student ID. No..............................', 0, 0, 'L');
$pdf->Ln(-1);
$pdf->SetFont('thsarabun', 'B', 15);
$pdf->SetTextColor($setFontColour_answer[0], $setFontColour_answer[1], $setFontColour_answer[2]);
$pdf->Cell(46, 0, '', 0, 0, 'L');
$pdf->Cell(63, 0, $scope['t_mjname'], 0, 0, 'L');
$pdf->Cell(62, 0, $scope['GROUP_NO'], 0, 0, 'L');
$pdf->Cell(20, 0, $scope['OLDID'], 0, 0, 'L');
$pdf->SetFont('thsarabun', '', 16);
$pdf->SetTextColor($setFontColour_main[0], $setFontColour_main[1], $setFontColour_main[2]);
$pdf->Ln();

$pdf->Cell(20, 0, 'ปัจจุบันอยู่บ้านเลขที่                 หมู่                         ถนน                            ตําบล', 0, 0, 'L');
$pdf->Ln(0.3);
$pdf->SetFont('thsarabun', '', 15);
$pdf->Cell(30, 0, '', 0, 0, 'L');
$pdf->Cell(20, 0, '/No..................    /Village No...............       /Road...........................        /Sub-district........................................', 0, 0, 'L');
$pdf->Ln(-1);
$pdf->SetFont('thsarabun', 'B', 16);
$pdf->SetTextColor($setFontColour_answer[0], $setFontColour_answer[1], $setFontColour_answer[2]);
$pdf->Cell(40, 0, '', 0, 0, 'L');
$pdf->Cell(35, 0, $scope['NUM_HOME'], 0, 0, 'L');
$pdf->Cell(30, 0, $scope['NUM_MOO'], 0, 0, 'L');
$pdf->Cell(50, 0, $scope['STREET'], 0, 0, 'L');
$pdf->Cell(30, 0, $scope['STREET'], 0, 0, 'L');
$pdf->SetFont('thsarabun', '', 16);
$pdf->SetTextColor($setFontColour_main[0], $setFontColour_main[1], $setFontColour_main[2]);
$pdf->Ln();

$pdf->Cell(20, 0, 'อําเภอ                                  จังหวัด                                          รหัสไปรษณีย์', 0, 0, 'L');
$pdf->Ln(0.3);
$pdf->SetFont('thsarabun', '', 15);
$pdf->Cell(10, 0, '', 0, 0, 'L');
$pdf->Cell(20, 0, '/District..................................         /Province..........................................                 /Postal Code...........................................', 0, 0, 'L');
$pdf->Ln(-1);
$pdf->SetFont('thsarabun', 'B', 16);
$pdf->SetTextColor($setFontColour_answer[0], $setFontColour_answer[1], $setFontColour_answer[2]);
$pdf->Cell(23, 0, '', 0, 0, 'L');
$pdf->Cell(55, 0, $scope['DISTRICT_NAME'], 0, 0, 'L');
$pdf->Cell(78, 0, $scope['PROVINCE_NAME'], 0, 0, 'L');
$pdf->Cell(50, 0, $scope['ZIPCODE'], 0, 0, 'L');
$pdf->SetFont('thsarabun', '', 16);
$pdf->SetTextColor($setFontColour_main[0], $setFontColour_main[1], $setFontColour_main[2]);
$pdf->Ln();

$pdf->Cell(20, 0, 'โทรศัพท์', 0, 0, 'L');
$pdf->Ln(0.3);
$pdf->SetFont('thsarabun', '', 15);
$pdf->Cell(13.5, 0, '', 0, 0, 'L');
$pdf->Cell(20, 0, '/Phone No....................................................', 0, 0, 'L');
$pdf->Ln(-1);
$pdf->SetFont('thsarabun', 'B', 16);
$pdf->SetTextColor($setFontColour_answer[0], $setFontColour_answer[1], $setFontColour_answer[2]);
$pdf->Cell(35, 0, '', 0, 0, 'L');
$pdf->Cell(55, 0, $scope['tel'], 0, 0, 'L');
$pdf->SetFont('thsarabun', '', 16);
$pdf->SetTextColor($setFontColour_main[0], $setFontColour_main[1], $setFontColour_main[2]);
$pdf->Ln();

$pdf->Cell(20, 0, 'คําร้องและเหตุผลประกอบ', 0, 0, 'L');
$pdf->Ln(0.3);
$pdf->SetFont('thsarabun', '', 15);
$pdf->Cell(39, 0, '', 0, 0, 'L');
$pdf->Cell(20, 0, '/Reasonsof request ……………………………………………………………………………………………………………………………', 0, 0, 'L');
$pdf->Ln(-1);
$pdf->SetFont('thsarabun', 'B', 16);
$pdf->SetTextColor($setFontColour_answer[0], $setFontColour_answer[1], $setFontColour_answer[2]);
$pdf->Cell(73, 0, '', 0, 0, 'L');
$pdf->Cell(55, 0, $scope['reason'], 0, 0, 'L');
$pdf->SetFont('thsarabun', '', 16);
$pdf->SetTextColor($setFontColour_main[0], $setFontColour_main[1], $setFontColour_main[2]);
$pdf->Ln();

$pdf->Ln();
$pdf->SetFont('thsarabun', '', 16);
$pdf->Cell(14, 0, '', 0, 0, 'L');
$pdf->Cell(48, 0, 'จึงเรียนมาเพื่อโปรดพิจารณา', 0, 0, 'L');
$pdf->Ln(0.3);
$pdf->SetFont('thsarabun', '', 15);
$pdf->Cell(56, 0, '', 0, 0, 'L');
$pdf->Cell(48, 0, '/For your consideration', 0, 0, 'L');
$pdf->Ln();

$pdf->SetFont('thsarabun', '', 16);
$pdf->Cell(66, 0, '', 0, 0, 'L');
$pdf->Cell(48, 0, 'ขอแสดงความนับถือ', 0, 0, 'L');
$pdf->Ln(0.3);
$pdf->SetFont('thsarabun', '', 15);
$pdf->Cell(96, 0, '', 0, 0, 'L');
$pdf->Cell(48, 0, '/Sincerely yours', 0, 0, 'L');
$pdf->Ln();

$pdf->SetFont('thsarabun', '', 16);
$pdf->Cell(48, 0, '', 0, 0, 'L');
$pdf->Cell(96, 0, 'ลงชื่อ', 0, 0, 'L');
$pdf->Ln(0.3);
$pdf->SetFont('thsarabun', '', 15);
$pdf->Cell(57, 0, '', 0, 0, 'L');
$pdf->Cell(96, 0, '/Signature......................................................', 0, 0, 'L');
$pdf->Ln(-1);
$pdf->SetFont('thsarabun', 'B', 16);
$pdf->SetTextColor($setFontColour_answer[0], $setFontColour_answer[1], $setFontColour_answer[2]);
$pdf->Cell(0, 0, $scope['NAME'], 0, 1, 'C', 0, '', 0);
$pdf->SetFont('thsarabun', '', 16);
$pdf->SetTextColor($setFontColour_main[0], $setFontColour_main[1], $setFontColour_main[2]);
$pdf->Cell(63, 0, '', 0, 0, 'L');
$pdf->Cell(96, 0, '(..........................................................................)', 0, 0, 'L');
$pdf->Ln(-1);
$pdf->SetFont('thsarabun', 'B', 16);
$pdf->SetTextColor($setFontColour_answer[0], $setFontColour_answer[1], $setFontColour_answer[2]);
$pdf->Cell(75, 0, '', 0, 0, 'L');
$pdf->Cell(50, 0, $scope['PNAME'] . $scope['NAME'], 0, 1, 'L', 0, '', 0);
$pdf->Cell(0, 0, $scope['setDateSignature_request'], 0, 1, 'C', 0, '', 0);
$pdf->Cell(0, 0, $scope['setCodeSignature_request'], 0, 1, 'C', 0, '', 0);
$pdf->SetTextColor($setFontColour_main[0], $setFontColour_main[1], $setFontColour_main[2]);

$pdf->SetFont('thsarabun', '', 16);
$pdf->Cell(14, 0, '', 0, 0, 'L');
$pdf->Cell(48, 0, 'ความเห็นของอาจารย์ที่ปรึกษา', 0, 0, 'L');
$pdf->Ln(0.3);
$pdf->SetFont('thsarabun', '', 15);
$pdf->Cell(59, 0, '', 0, 0, 'L');
$pdf->Cell(48, 0, "/Advisor's recommendation", 0, 0, 'L');
$pdf->Ln();

$pdf->SetFont('thsarabun', '', 16);
$pdf->Cell(14, 0, '', 0, 0, 'L');
$pdf->Cell(48, 0, '……………………………………………………………………………………………………………………………………………………………………………', 0, 0, 'L');
$pdf->Ln(-1);
$pdf->SetFont('thsarabun', 'B', 16);
$pdf->SetTextColor($setFontColour_answer[0], $setFontColour_answer[1], $setFontColour_answer[2]);
$pdf->Cell(16, 0, '', 0, 0, 'L');
$pdf->Cell(0, 0, $scope['reason_Teacher'], 0, 0, 'L');
$pdf->SetTextColor($setFontColour_main[0], $setFontColour_main[1], $setFontColour_main[2]);
$pdf->Ln();
$pdf->Ln();

$pdf->SetFont('thsarabun', '', 16);
$pdf->Cell(48, 0, '', 0, 0, 'L');
$pdf->Cell(96, 0, 'ลงชื่อ', 0, 0, 'L');
$pdf->Ln(0.3);
$pdf->SetFont('thsarabun', '', 15);
$pdf->Cell(57, 0, '', 0, 0, 'L');
$pdf->Cell(96, 0, '/Signature......................................................', 0, 0, 'L');
$pdf->SetFont('thsarabun', 'B', 16);
$pdf->SetTextColor($setFontColour_answer[0], $setFontColour_answer[1], $setFontColour_answer[2]);
if (($scope['status_request'] > 1 && $scope['status_request'] != 9) || $scope['status_request'] == 0) {
  // $pdf->Cell(57, 0, '', 0, 0, 'L');
  $pdf->Ln(-2);
  $pdf->Cell(200, 0, '', 0, 0, 'L');
  $pdf->Cell(0, 0, $pdf->Image($respone[0]['setDataImages_Teacher'], $pdf->GetX()-120, $pdf->GetY()-2, 20, 0, 'JPG'), 0, 0, 'L');
  $pdf->Ln(8);
} else {
  $pdf->Cell(0, 0, '', 0, 1, 'C', 0, '', 0);
}
$pdf->SetFont('thsarabun', '', 16);
$pdf->SetTextColor($setFontColour_main[0], $setFontColour_main[1], $setFontColour_main[2]);
$pdf->Cell(63, 0, '', 0, 0, 'L');
$pdf->Cell(96, 0, '(..........................................................................)', 0, 0, 'L');
$pdf->Ln(-1);
$pdf->SetFont('thsarabun', 'B', 16);
$pdf->SetTextColor($setFontColour_answer[0], $setFontColour_answer[1], $setFontColour_answer[2]);
$pdf->Cell(73, 0, '', 0, 0, 'L');
$pdf->Cell(50, 0, $scope['NAMETEACHER'], 0, 1, 'L', 0, '', 0);
if (($scope['status_request'] > 1 && $scope['status_request'] != 9) || $scope['status_request'] == 0) {
  $pdf->Cell(0, 0, $scope['setDateSignature_Teacher'], 0, 1, 'C', 0, '', 0);
  $pdf->Cell(0, 0, $scope['setCodeSignature_Teacher'], 0, 1, 'C', 0, '', 0);
}
$pdf->SetFont('thsarabun', '', 16);
$pdf->SetTextColor($setFontColour_main[0], $setFontColour_main[1], $setFontColour_main[2]);

$pdf->AddPage();
$pdf->Cell(76, 18, '', '1', 0, 'L');
$pdf->Cell(57, 18, '', '1', 0, 'L');
$pdf->Cell(57, 18, '', '1', 0, 'L');
$pdf->Ln(0);
$pdf->Cell(76, 18, '', '1', 0, 'L');
$pdf->Cell(57, 18, '', '1', 0, 'L');
$pdf->Cell(57, 18, '', '1', 0, 'L');

$pdf->Ln(-1);
$pdf->SetFont('thsarabun', 'B', 16);
$pdf->Cell(76, 13, "ความเห็นของเจ้าหน้าที่/", 0, 1, 'C', 0, '', 0);
$pdf->Ln(-7);
$pdf->SetFont('thsarabun', 'B', 15);
$pdf->Cell(76, 13, "The Officer's Recommendation", 0, 1, 'C', 0, '', 0);
$pdf->Ln();
$pdf->Ln(-26);
$pdf->SetFont('thsarabun', 'B', 16);
$pdf->Cell(0, 0, "                                                                ฝ่ายการเงิน", 0, 1, 'L', 0, '', 0);
$pdf->Ln(-7);
$pdf->SetFont('thsarabun', 'B', 15);
$pdf->Cell(0, 0, "                                 /Finance Office", 0, 1, 'C', 0, '', 0);
$pdf->SetFont('thsarabun', 'B', 16);
$pdf->Ln(-7);
$pdf->Cell(0, 0, "คำสั่ง                        ", 0, 1, 'R', 0, '', 0);
$pdf->Ln(-6.6);
$pdf->SetFont('thsarabun', 'B', 15);
$pdf->Cell(0, 0, "/Order                ", 0, 1, 'R', 0, '', 0);
$pdf->Ln(6);
if($scope["status_request"] == "1" || $scope["status_request"] == "2"){
  $pdf->Cell(76, 110, '', '1', 0, 'L');
  $pdf->Cell(57, 110, '', '1', 0, 'L');
  $pdf->Cell(57, 110, '', '1', 0, 'L');
  $pdf->Ln(0);
  $pdf->Cell(76, 110, '', '1', 0, 'L');
  $pdf->Cell(57, 110, '', '1', 0, 'L');
  $pdf->Cell(57, 110, '', '1', 0, 'L');
}else{
  $pdf->Cell(76, 120, '', '1', 0, 'L');
  $pdf->Cell(57, 120, '', '1', 0, 'L');
  $pdf->Cell(57, 120, '', '1', 0, 'L');
  $pdf->Ln(0);
  $pdf->Cell(76, 120, '', '1', 0, 'L');
  $pdf->Cell(57, 120, '', '1', 0, 'L');
  $pdf->Cell(57, 120, '', '1', 0, 'L');
}

$pdf->Ln();

if($scope["status_request"] == "1" || $scope["status_request"] == "2"){
  $pdf->Ln(-112);
}else{
  $pdf->Ln(-122);
}
$pdf->SetFont('thsarabun', '', 14);
$pdf->Cell(76, 18, ' เรียน       รองอธิการฝ่ายวิชาการ', '0', 0, 'L');
$pdf->Cell(57, 18, ' รับชําระเงินจํานวน', '0', 0, 'L');
$pdf->Image($scope['checkbox_allow'], $pdf->GetX() + 4, $pdf->GetY() + 7, 3.5);
$pdf->Cell(57, 18, '        อนุญาต', '0', 0, 'L');
$pdf->SetFont('thsarabun', '', 13);
$pdf->Ln(0);
$pdf->Cell(76, 18, '        /To:                               /Vice President for', '0', 0, 'L');
$pdf->Cell(57, 18, '                          /The office has', '0', 0, 'L');
$pdf->Cell(57, 18, '                   /Approved', '0', 0, 'L');
$pdf->Ln();
$pdf->Ln(10);

$pdf->Ln(-20);
$pdf->SetFont('thsarabun', '', 13);
$pdf->Cell(76, 18, ' Academic Affairs', '0', 0, 'L');
$pdf->Cell(57, 18, ' received money of', '0', 0, 'L');
$pdf->SetFont('thsarabun', '', 14);
$pdf->Image($scope['checkbox_notallow'], $pdf->GetX() + 4, $pdf->GetY() + 7, 3.5);
$pdf->Cell(57, 18, '        ไม่อนุญาต', '0', 0, 'L');
$pdf->Ln(0);
$pdf->Cell(76, 18, '', '0', 0, 'L');
$pdf->Cell(57, 18, '', '0', 0, 'L');
$pdf->Cell(57, 18, '                     /Not Approved', '0', 0, 'L');
$pdf->Ln();
$pdf->Ln(10);

$pdf->Ln(-20);
$pdf->SetFont('thsarabun', '', 14);
$pdf->Cell(76, 18, ' ด้วย', '0', 0, 'L');
$pdf->Cell(57, 18, ' ...................................บาท', '0', 0, 'L');
$pdf->SetFont('thsarabun', '', 13);
$pdf->Ln(0);
$pdf->Cell(76, 18, '       /Name.....................................................................', '0', 0, 'L');
$pdf->Cell(57, 18, '                                   /Baht', '0', 0, 'L');
$pdf->Ln(-1);
$pdf->SetFont('thsarabun', 'B', 14);
$pdf->SetTextColor($setFontColour_answer[0], $setFontColour_answer[1], $setFontColour_answer[2]);
$pdf->Cell(20, 0, '', 0, 0, 'L');
$pdf->Cell(56, 18, $scope['PNAME'] . $scope['NAME'], '0', 0, 'L');
$pdf->Cell(10, 0, '', 0, 0, 'L');
$pdf->Cell(47, 18, $scope['BILL_MONEY'], '0', 0, 'L');
$pdf->SetTextColor($setFontColour_main[0], $setFontColour_main[1], $setFontColour_main[2]);
$pdf->Ln();
$pdf->Ln(10);

$pdf->Ln(-20);
$pdf->SetFont('thsarabun', '', 14);
$pdf->Cell(76, 18, ' โปรแกรมวิชา', '0', 0, 'L');
$pdf->Cell(57, 18, ' ตามใบเสร็จเล่มที่', '0', 0, 'L');
$pdf->SetFont('thsarabun', '', 13);
$pdf->Ln(0);
$pdf->Cell(76, 18, '                   /Program...................................................', '0', 0, 'L');
$pdf->Cell(57, 18, '                        /Receipt Book No.', '0', 0, 'L');
$pdf->Ln(-1);
$pdf->SetFont('thsarabun', 'B', 14);
$pdf->SetTextColor($setFontColour_answer[0], $setFontColour_answer[1], $setFontColour_answer[2]);
$pdf->Cell(33, 0, '', 0, 0, 'L');
$pdf->Cell(76, 18, $scope['t_mjname'], '0', 0, 'L');
$pdf->SetTextColor($setFontColour_main[0], $setFontColour_main[1], $setFontColour_main[2]);
$pdf->Ln();
$pdf->Ln(10);

$pdf->Ln(-20);
$pdf->SetFont('thsarabun', '', 14);
$pdf->Cell(76, 18, ' หมู่เรียน', '0', 0, 'L');
$pdf->SetFont('thsarabun', '', 13);
$pdf->Ln(0);
$pdf->Cell(76, 18, '            /Class No...........................................................', '0', 0, 'L');
$pdf->Cell(57, 18, ' ..................................................................', '0', 0, 'L');
$pdf->Ln(-1);
$pdf->SetFont('thsarabun', 'B', 14);
$pdf->SetTextColor($setFontColour_answer[0], $setFontColour_answer[1], $setFontColour_answer[2]);
$pdf->Cell(30, 0, '', 0, 0, 'L');
$pdf->Cell(46, 18, $scope['GROUP_NO'], '0', 0, 'L');
$pdf->Cell(3, 18, '', '0', 0, 'L');
$pdf->Cell(55, 18, $scope['BILL'], '0', 0, 'L');
$pdf->SetTextColor($setFontColour_main[0], $setFontColour_main[1], $setFontColour_main[2]);
$pdf->Ln();
$pdf->Ln(10);

$pdf->Ln(-20);
$pdf->SetFont('thsarabun', '', 14);
$pdf->Cell(76, 18, ' นักศึกษาภาค', '0', 0, 'L');
$pdf->Cell(57, 18, ' เลขที่', '0', 0, 'L');
$pdf->SetFont('thsarabun', '', 13);
$pdf->Ln(0);
$pdf->Cell(76, 18, '                   /Student Type.........................................', '0', 0, 'L');
$pdf->Cell(57, 18, '        /Receipt No.................................', '0', 0, 'L');
$pdf->Ln(-1);
$pdf->SetFont('thsarabun', 'B', 14);
$pdf->SetTextColor($setFontColour_answer[0], $setFontColour_answer[1], $setFontColour_answer[2]);
$pdf->Cell(40, 0, '', 0, 0, 'L');
$pdf->Cell(36, 18, $scope['Type_name'], '0', 0, 'L');
$pdf->Cell(27, 18, '', '0', 0, 'L');
$pdf->Cell(30, 18, $scope['BILL_NO'], '0', 0, 'L');
$pdf->SetTextColor($setFontColour_main[0], $setFontColour_main[1], $setFontColour_main[2]);
$pdf->Ln();
$pdf->Ln(10);

$pdf->Ln(-20);
$pdf->SetFont('thsarabun', '', 14);
$pdf->Cell(76, 18, ' มีความประสงค์จะขอ', '0', 0, 'L');
$pdf->SetFont('thsarabun', '', 13);
$pdf->Ln(0);
$pdf->Cell(76, 18, '                             /Kindly Request.........................', '0', 0, 'L');
$pdf->Ln();
$pdf->Ln(10);

$pdf->Ln(-20);
$pdf->SetFont('thsarabun', '', 13);
$pdf->Ln(0);
$pdf->Cell(76, 18, ' ...........................................................................................', '0', 0, 'L');
$pdf->Ln(-1);
$pdf->SetFont('thsarabun', 'B', 14);
$pdf->SetTextColor($setFontColour_answer[0], $setFontColour_answer[1], $setFontColour_answer[2]);
$pdf->Cell(3, 0, '', 0, 0, 'L');
$pdf->Cell(76, 18, $scope["Type_requestname"], '0', 0, 'L');
$pdf->SetTextColor($setFontColour_main[0], $setFontColour_main[1], $setFontColour_main[2]);
$pdf->Ln();
$pdf->Ln(10);

$pdf->Ln(-20);
$pdf->SetFont('thsarabun', '', 14);
$pdf->Cell(76, 18, ' ภาคเรียนที่', '0', 0, 'L');
$pdf->SetFont('thsarabun', '', 13);
$pdf->Cell(57, 18, '', '0', 0, 'L');
$pdf->Cell(57, 18, '', '0', 0, 'C');
$pdf->Ln(0);
$pdf->Cell(76, 18, '                /Semester.......................................................', '0', 0, 'L');
$pdf->Ln(-1);
$pdf->SetFont('thsarabun', 'B', 14);
$pdf->SetTextColor($setFontColour_answer[0], $setFontColour_answer[1], $setFontColour_answer[2]);
$pdf->Cell(32, 0, '', 0, 0, 'L');
$pdf->Cell(44, 18, $scope['Term_request'], '0', 0, 'L');
$pdf->Cell(57, 18, '', '0', 0, 'L');
$pdf->Image($scope['setDataImages_Vicerector'], $pdf->GetX() + 22, $pdf->GetY() + 4, 15, 0, 'JPG');
$pdf->SetTextColor($setFontColour_main[0], $setFontColour_main[1], $setFontColour_main[2]);
$pdf->Ln(1);
$pdf->SetFont('thsarabun', '', 14);
$pdf->Cell(76, 18, '', '0', 0, 'L');
$pdf->Cell(57, 18, '', '0', 0, 'L');
$pdf->Cell(57, 18, '...............................', '0', 0, 'C');
$pdf->Ln();
$pdf->Ln(10);

$pdf->Ln(-20);
$pdf->SetFont('thsarabun', '', 14);
$pdf->Cell(76, 18, ' เห็นควรชําระเงิน', '0', 0, 'L');
$pdf->SetFont('thsarabun', '', 13);
$pdf->Cell(57, 18, '', '0', 0, 'L');
$pdf->Cell(57, 18, '.........../........................./..............', '0', 0, 'C');
$pdf->Ln(0);
$pdf->Cell(76, 18, '                       /see as appropriate to to collect ', '0', 0, 'L');
$pdf->Ln(-1);
$pdf->Cell(76, 18, '', '0', 0, 'L');
$pdf->Cell(57, 18, '', '0', 0, 'L');

$pdf->SetFont('thsarabun', 'B', 13);
$pdf->SetTextColor($setFontColour_answer[0], $setFontColour_answer[1], $setFontColour_answer[2]);
$pdf->Cell(9, 18, '', '0', 0, 'C');
$pdf->Cell(9, 18, $scope['setDateSignature_VicerectorDAY'], '0', 0, 'C');
$pdf->Cell(20, 18, $scope['setDateSignature_VicerectorMONTH'], '0', 0, 'C');
$pdf->Cell(12, 18, $scope['setDateSignature_VicerectorYEAR'], '0', 0, 'C');
$pdf->Ln();
$pdf->Ln(10);

$pdf->Ln(-20);
$pdf->SetTextColor($setFontColour_main[0], $setFontColour_main[1], $setFontColour_main[2]);
$pdf->SetFont('thsarabun', '', 14);
$pdf->Cell(76, 18, '                                                      บาท', '0', 0, 'L');
$pdf->SetFont('thsarabun', '', 13);
$pdf->Ln(0);
$pdf->Cell(76, 18, ' a fee of..............................................................      /Baht', '0', 0, 'L');
$pdf->Ln(-1);
$pdf->SetFont('thsarabun', 'B', 14);
$pdf->SetTextColor($setFontColour_answer[0], $setFontColour_answer[1], $setFontColour_answer[2]);
$pdf->Cell(30, 0, '', 0, 0, 'L');
$pdf->Cell(76, 18, $scope['amout'], '0', 0, 'L');
$pdf->SetTextColor($setFontColour_main[0], $setFontColour_main[1], $setFontColour_main[2]);
$pdf->Ln();
$pdf->Ln(10);

$pdf->Ln(-20);
$pdf->SetFont('thsarabun', '', 14);
$pdf->Cell(76, 18, '    จึงเรียนมาเพื่อโปรดพิจารณา', '0', 0, 'L');
$pdf->SetFont('thsarabun', '', 13);
$pdf->Ln(0);
$pdf->Cell(76, 18, '                                          /For your consideration', '0', 0, 'L');
$pdf->Ln();
$pdf->Ln(10);

$pdf->Ln(-20);
$pdf->SetFont('thsarabun', '', 14);
$pdf->Cell(76, 18, ' ลงชื่อ', '0', 0, 'L');
$pdf->Cell(57, 18, ' ลงชื่อ', '0', 0, 'L');
$pdf->SetFont('thsarabun', '', 13);
$pdf->Ln(0);
$pdf->Image($scope['setDataImages_Tabian'], $pdf->GetX() + 25, $pdf->GetY() + 4, 15, 0, 'JPG');
$pdf->Cell(76, 18, '         /Signature...................................................................', '0', 0, 'L');
$pdf->Image($scope['SIGNATURE_MONEY'], $pdf->GetX() + 25, $pdf->GetY() + 4, 15, 0, 'JPG');
$pdf->Cell(57, 18, '         /Signature......................................', '0', 0, 'L');
$pdf->Ln();
$pdf->Ln(10);

$pdf->Ln(-20);
$pdf->Cell(76, 18, '.........../........................./..............', '0', 0, 'C');
$pdf->Cell(57, 18, '.........../........................./..............', '0', 0, 'C');
if(($scope['status_request'] > 2 && $scope['status_request'] != 9) || $scope['status_request'] == 0){
  $pdf->SetFont('thsarabun', 'B', 13);
  $pdf->SetTextColor($setFontColour_answer[0], $setFontColour_answer[1], $setFontColour_answer[2]);
}else{
  $pdf->SetFont('thsarabun', '', 13);
}
$pdf->Ln(-1);
$pdf->Cell(18, 18, '', '0', 0, 'C');
$pdf->Cell(9, 18, $scope['setDateSignature_TabianDAY'], '0', 0, 'C');
$pdf->Cell(20, 18, $scope['setDateSignature_TabianMONTH'], '0', 0, 'C');
$pdf->Cell(12, 18, $scope['setDateSignature_TabianYEAR'], '0', 0, 'C');
$pdf->Cell(17, 18, '', '0', 0, 'C');
$pdf->SetTextColor($setFontColour_main[0], $setFontColour_main[1], $setFontColour_main[2]);
$pdf->Cell(7, 18, '', '0', 0, 'L');
if($scope['status_request'] > 3 && $scope['status_request'] != 9){
  $pdf->SetFont('thsarabun', 'B', 13);
  $pdf->SetTextColor($setFontColour_answer[0], $setFontColour_answer[1], $setFontColour_answer[2]);
}else{
  $pdf->SetFont('thsarabun', '', 13);
}
$pdf->Cell(9, 18, $scope['BILL_DAY'], '0', 0, 'C');
$pdf->Cell(22, 18, $scope['BILL_MONTH'], '0', 0, 'C');
$pdf->Cell(12, 18, $scope['BILL_YEAR'], '0', 0, 'C');
$pdf->SetTextColor($setFontColour_main[0], $setFontColour_main[1], $setFontColour_main[2]);
$pdf->Ln();
$pdf->Ln(8);

// if (($scope['status_request'] > 2 && $scope['status_request'] != 9) || $scope['status_request'] == 0) {
//   $pdf->Ln(-20);
//   $pdf->SetFont('thsarabun', 'B', 13);
//   $pdf->SetTextColor($setFontColour_answer[0], $setFontColour_answer[1], $setFontColour_answer[2]);
//   $pdf->Cell(76, 18, $scope['setCodeSignature_Tabian'], '0', 0, 'C');
//   $pdf->SetTextColor($setFontColour_main[0], $setFontColour_main[1], $setFontColour_main[2]);
//   $pdf->Ln();
// }
$pdf->Ln();  

// $htmlcontent2 = '
//     <table border="1">
//       <thead>
//         <tr>
//           <th align="center" width="50%"><b>ความเห็นเจ้าหน้าที่</b></th>
//           <th align="center" width="30%"><b>ฝ่ายการเงิน</b></th>
//           <th align="center" width="23%"><b>คำสั่ง</b></th>
//         </tr>
//       </thead>
//       <tbody>
//         <tr>
//           <td width="50%"> 
//             เรียนรองอธิการฝ่ายวิชาการ <br />&nbsp;&nbsp;&nbsp;&nbsp;ด้วย ' . $scope['PNAME'] . $scope['NAME'] . ' โปรแกรมวิชา ' . $scope['t_mjname'] . 
//             ' หมู่เรียน ' . $scope['GROUP_NO'] . ' นักศึกษาภาค ' . $scope['Type_name'] . ' มีความประสงค์จะขอ ' . $scope["Type_requestname"] . ' ภาคเรียนที่ ' . 
//             $scope['Term_request'] . ' ' . $scope['reason_Tabian'] . ' ' . $scope['amout'] . ' บาท 
//             <br />จึงเรียนมาเพื่อโปรดพิจารณา
//             <br />ลงชื่อ' . $scope['setDataImages_Tabian'] . '
//             <br />' . $scope['setDateSignature_Tabian'] . '
//             <br />' . $scope['setCodeSignature_Tabian'] . '
//           </td>
//           <td width="30%">
//             รับชำระเงินจำนวน ' . $scope['BILL_MONEY'] . ' บาท ตามใบเสร็จเล่มที่ ' . $scope['BILL'] . ' เลขที่ ' . $scope['BILL_NO'] . '
//             <br />ลงชื่อ' . $scope['SIGNATURE_MONEY'] . '<br />' . $scope['BILL_DATE'] . $scope['SETCODESIGNATURE_MONEY'] . '
//           </td>
//           <td width="23%">
//             ' . $scope['checkbox_allow'] . ' อนุญาต<br />&nbsp;' . $scope['checkbox_notallow'] . ' ไม่อนุญาต
//             <br />' . $scope['setDataImages_Vicerector'] . '<br />' . $scope['setDateSignature_Vicerector'] . '<br />' . $scope['setCodeSignature_Vicerector'] . '
//           </td>
//         </tr>
//       </tbody>
//     </table>
//   ';
// $pdf->SetFont('thsarabun', '', 15);
// $pdf->writeHTML($htmlcontent2, false, 0, true, 0);

$pdf->SetFont('thsarabun', '', 16);
$pdf->Cell(14, 0, '', 0, 0, 'L');
$pdf->Cell(48, 0, 'ข้าพเจ้าได้รับทราบคําสั่งเรียบร้อยแล้ว', 0, 0, 'L');
$pdf->Ln(0.3);
$pdf->SetFont('thsarabun', '', 15);
$pdf->Cell(70, 0, '', 0, 0, 'L');
$pdf->Cell(48, 0, "/I have acknowledged the order", 0, 0, 'L');
$pdf->Ln();

$pdf->SetFont('thsarabun', '', 16);
$pdf->Cell(70, 0, '', 0, 0, 'L');
$pdf->Cell(48, 0, "ลงชื่อนักศึกษา", 0, 0, 'L');
$pdf->Ln(0.3);
$pdf->SetFont('thsarabun', '', 15);
$pdf->Cell(92, 0, '', 0, 0, 'L');
$pdf->Cell(48, 0, "/Signature..................................................................................", 0, 0, 'L');
if($respone[0]["print_status"] == "1"){
  $pdf->Ln(-1);
  $pdf->SetFont('thsarabun', 'B', 16);
  $pdf->SetTextColor($setFontColour_answer[0], $setFontColour_answer[1], $setFontColour_answer[2]);
  $pdf->Cell(115, 0, '', 0, 0, 'L');
  $pdf->Cell(48, 0, $scope['NAME'], 0, 0, 'L');
  $pdf->Ln();
  $pdf->SetFont('thsarabun', '', 16);
  $pdf->SetTextColor($setFontColour_main[0], $setFontColour_main[1], $setFontColour_main[2]);
  $pdf->Cell(83, 0, '', 0, 0, 'L');
  $pdf->Cell(48, 0, "........................................................................................................", 0, 0, 'L');
  $pdf->Ln(-1);
  $pdf->SetFont('thsarabun', 'B', 16);
  $pdf->SetTextColor($setFontColour_answer[0], $setFontColour_answer[1], $setFontColour_answer[2]);
  $pdf->Cell(85, 0, '', 0, 0, 'L');
  $pdf->Cell(48, 0, $scope["setDateSignature_print"], 0, 0, 'L');
  $pdf->Ln();
  $pdf->SetFont('thsarabun', '', 16);
  $pdf->SetTextColor($setFontColour_main[0], $setFontColour_main[1], $setFontColour_main[2]);
  $pdf->Cell(83, 0, '', 0, 0, 'L');
  $pdf->Cell(48, 0, "........................................................................................................", 0, 0, 'L');
  $pdf->Ln(-1);
  $pdf->SetFont('thsarabun', 'B', 16);
  $pdf->SetTextColor($setFontColour_answer[0], $setFontColour_answer[1], $setFontColour_answer[2]);
  $pdf->Cell(87, 0, '', 0, 0, 'L');
  $pdf->Cell(48, 0, $scope["setCodeSignature_print"], 0, 0, 'L');
}else{
  $pdf->Ln();
  $pdf->SetFont('thsarabun', '', 16);
  $pdf->Cell(92, 0, '', 0, 0, 'L');
  $pdf->Cell(48, 0, "................../......................................./.......................", 0, 0, 'L');
}
$pdf->SetFont('thsarabun', '', 16);
$pdf->SetTextColor($setFontColour_main[0], $setFontColour_main[1], $setFontColour_main[2]);
$pdf->Ln();

$regbill_FILE = "test182222222.pdf";
$pdf->Output($regbill_FILE, "I", true);
