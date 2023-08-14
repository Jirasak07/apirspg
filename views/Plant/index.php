﻿<?php
$Connect_Viwes = new Plant();  //connet Viwe/// แสดงผล
$Connect_Models = new Plant_model(); // connect Model // ติดต่อฐานข้อมูล

/// ประเภทนักศึกษา //

    $TypesStd1 = $Connect_Models::PDF();

// $TypesStd2 = $Connect_Models::quota($TypesStd1[0]['REGISTER_TYPE_ID']);
class PDF extends FPDF();
{
    function RotatedText($x, $y, $txt, $angle)
    {
        //Text rotated around its origin
        $this->Rotate($angle, $x, $y);
        $this->Text($x, $y, $txt);
        $this->Rotate(0);
    }

    function RotatedImage($file, $x, $y, $w, $h, $angle)
    {
        //Image rotated around its upper-left corner
        $this->Rotate($angle, $x, $y);
        $this->Image($file, $x, $y, $w, $h);
        $this->Rotate(0);
    }

    function Footer()
    {
        $this->SetY(-10);
        $this->SetFont('angsana', '', 14);
        $this->Cell(0, 10, iconv('UTF-8', 'cp874', 'พัฒนาโดย งานเทคโนโลยีสารสนเทศ สำนักส่งเสริมวิชาการและงานทะเบียน'), 0, 0, 'L');
        $this->Cell(0, 10, iconv('UTF-8', 'cp874', 'หน้าที่ ' . $this->PageNo() . ' จาก  tp'), 0, 0, 'R');
    }
}


date_default_timezone_set('Asia/Bangkok');

function DateThai($strDate)
{
    $strYear = date("Y", strtotime($strDate)) + 543;
    $strMonth = date("n", strtotime($strDate));
    $strDay = date("j", strtotime($strDate));
    $strMonthCut = Array("", "มกราคม", "กุมภาพันธ์", "มีนาคม", "เมษายน", "พฤษภาคม", "มิถุนายน", "กรกฎาคม", "สิงหาคม", "กันยายน", "ตุลาคม", "พฤศจิกายน", "ธันวาคม");
    $strMonthThai = $strMonthCut[$strMonth];
    return "$strDay $strMonthThai $strYear";
}

function thainumDigit($num)
{
    return str_replace(array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9'), array("o", "๑", "๒", "๓", "๔", "๕", "๖", "๗", "๘", "๙"), $num);
}

;
$strDate = date("Y-m-d");
$strTime = date("H:i:s");


$pdf = new PDF_Code128('P', 'mm', 'A4');

$pdf->AddFont('angsana', '', 'THSarabun.php');
$pdf->AddFont('angsana', 'B', 'THSarabun Bold.php');
$pdf->AddFont('angsana', 'I', 'THSarabun Italic.php');
$pdf->AddFont('angsana', 'BI', 'THSarabun BoldItalic.php');

$setFontColour_main = [0, 0, 0];
$setFontColour_answer = [0, 0, 225];
$set_general_Id = 1;
$set_quotaGrade_Id = 2;
$set_quotaSport_Id = 3;
$set_quotaTalent_Id = 4;

 
$pdf->AddPage();
$pdf->SetMargins(10, 10, 10, 10);

// $pdf->Ln(13);
$pdf->Image('public/images/kpru-logo-line2.png', 11, 8, 12, 0, 'PNG');

$yearsss = date("Y") + 543;

$setYear = substr($yearsss, 2, 2); // 2 digit format yy

$ref11 = $setYear . $_GET['code_test'];

$pdf->Cell(20, 6, '', 0, 0, 'C');
$pdf->SetFont('angsana', '', 18);
$pdf->Cell(50, 6, iconv('UTF-8', 'cp874', 'มหาวิทยาลัยราชภัฏกำแพงเพชร'), 0, 0, 'L');
$pdf->SetFont('angsana', '', 8);
$pdf->SetFillColor(200, 220, 255);
$pdf->Cell(95, 6, '', 0, 0, 'C');
$pdf->Cell(0, 6, iconv('UTF-8', 'cp874', 'สำหรับผู้สมัคร'), 1, 0, 'C', true);
$pdf->Ln();
$pdf->SetFont('angsana', '', 13);
$pdf->Cell(20, 6, '', 0, 0, 'C');
$pdf->Cell(120, 6, iconv('UTF-8', 'cp874', 'ใบแจ้งการชำระเงินค่ารายงานตัวภาค' . $TypesStd1[0]['type_std_name'].' '.$robmoney), 0, 0, 'L');
$pdf->Cell(0, 6, iconv('UTF-8', 'cp874', 'พิมพ์รายการเมื่อ : ' . DateThai($strDate) . '  ' . $strTime), 0, 1, 'R');
$pdf->Ln(0);
$pdf->Cell(19, 6, '', 0, 0, 'C');
if ($TypesStd1[0]['MAJOR_TYPE'] == '1' || $TypesStd1[0]['MAJOR_TYPE'] == '5') {
    if($_GET['code_test']!='6511304021'){
        $pdf->Cell(120, 6, iconv('UTF-8', 'cp874', ' ประเภท' . $TypesStd1[0]['QUOTA_NAME'] . $TypesStd1[0]['ROB_NAME'] . ' ประจำปีการศึกษา ' . $TypesStd1[0]['year']), 0, 0, 'L');
    } else {
        $pdf->Cell(120, 6, iconv('UTF-8', 'cp874', ' ประเภท' . $TypesStd1[0]['QUOTA_NAME'] . $TypesStd1[0]['ROB_NAME'] . ' ประจำปีการศึกษา 2565'), 0, 0, 'L');
    }

} else if ($TypesStd1[0]['MAJOR_TYPE'] == '2' || $TypesStd1[0]['MAJOR_TYPE'] == '3') {
    $pdf->Cell(120, 6, iconv('UTF-8', 'cp874', ' ประเภทคัดเลือกทั่วไป  ประจำปีการศึกษา ' . $TypesStd1[0]['year']), 0, 0, 'L');
} else if ($TypesStd1[0]['MAJOR_TYPE'] == '6') {
    $pdf->Cell(120, 6, iconv('UTF-8', 'cp874', ' หลักสูตรประกาศนียบัตรบัณฑิตวิชาชีพครู  ประจำปีการศึกษา ' . $TypesStd1[0]['year']), 0, 0, 'L');
}

$pdf->Cell(0, 6, iconv('UTF-8', 'cp874', 'อ้างอิงเอกสาร : ' . $ref11 . ' โดย WEB'), 0, 1, 'R');
$pdf->Ln(0);
$pdf->Ln(2);
$pdf->SetFont('angsana', '', 12);
$pdf->Cell(30, 6, iconv('UTF-8', 'cp874', 'เลขประจำตัวผู้สมัคร'), 0, 0, 'L');
$pdf->SetFillColor(000, 000, 000);
$pdf->Code128(135, 10, $_GET['code_test'], 38, 6);  ////////////////////// ต้องใส่
$pdf->SetFont('angsana', '', 14);
$pdf->Cell(60, 6, iconv('UTF-8', 'cp874', $_GET['code_test']), 0, 0, 'L');
$pdf->SetFont('angsana', '', 12);
$pdf->Cell(30, 6, iconv('UTF-8', 'cp874', 'เบอร์โทรศัพท์/มือถือ'), 0, 0, 'L');
$pdf->SetFont('angsana', '', 14);
$pdf->Cell(30, 6, iconv('UTF-8', 'cp874', $TypesStd1[0]['PHONE_T']), 0, 0, 'L');

$pdf->Ln(3);
$pdf->SetFont('angsana', '', 10);
$pdf->Cell(30, 6, iconv('UTF-8', 'cp874', 'Student ID :'), 0, 0, 'L');
$pdf->Cell(60, 6, iconv('UTF-8', 'cp874', ''), 0, 0, 'L');
$pdf->Cell(15, 6, iconv('UTF-8', 'cp874', 'Telephone/Mobile :'), 0, 0, 'L');
$pdf->Cell(30, 6, iconv('UTF-8', 'cp874', ''), 0, 0, 'L');
$pdf->Ln(5);
$pdf->SetFont('angsana', '', 12);
$pdf->Cell(30, 6, iconv('UTF-8', 'cp874', 'ชื่อ - นามสกุล'), 0, 0, 'L');
$pdf->SetFont('angsana', '', 14);
$pdf->Cell(60, 6, iconv('UTF-8', 'cp874', $TypesStd1[0]['FULLNAME'] . $TypesStd1[0]['FRISTNAME_LOGIN'] . '  ' . $TypesStd1[0]['LASTNAME_LOGIN']), 0, 0, 'L');
$pdf->SetFont('angsana', '', 12);
$pdf->Cell(30, 6, iconv('UTF-8', 'cp874', 'เลขประจำประชาชน'), 0, 0, 'L');
$pdf->SetFont('angsana', '', 14);
//$textnew = str_replace("คณะ", "",$fpro_fac_std['faculty_name']);
$pdf->Cell(30, 6, iconv('UTF-8', 'cp874', $TypesStd1[0]['CITIZENT_LOGIN']), 0, 0, 'L');

////////// ตรวจสอบสิทธิ์ กยศ 2564 //////////
$servername_gys = "db-itars.kpru.ac.th";
$username_gys = "root_alarmni";
$password_gys = "root_alarmni";
$dbname_gys = "db_alarmni";
$conn_gys = new mysqli($servername_gys, $username_gys, $password_gys, $dbname_gys);
$sql_gys = "SELECT * FROM tb_confirmloan where citizen = '".$TypesStd1[0]['CITIZENT_LOGIN']."' and cf_status = 'T'";
$result_gys = $conn_gys->query($sql_gys);
$creck_alarmni_gys =  $result_gys->num_rows;

//////////////////////////////////////////



$pdf->Ln(3);
$pdf->SetFont('angsana', '', 10);
$pdf->Cell(30, 6, iconv('UTF-8', 'cp874', 'Name - Surname :'), 0, 0, 'L');
$pdf->Cell(60, 6, iconv('UTF-8', 'cp874', ''), 0, 0, 'L');
$pdf->Cell(15, 6, iconv('UTF-8', 'cp874', 'Citizen ID:'), 0, 0, 'L');
$pdf->Cell(30, 6, iconv('UTF-8', 'cp874', ''), 0, 0, 'L');
$pdf->Ln(5);


$pdf->Ln(1);
$pdf->SetFont('angsana', '', 12);
$pdf->Cell(30, 6, iconv('UTF-8', 'cp874', 'สาขาวิชา/คณะ'), 0, 0, 'L');
$pdf->Cell(0, 6, iconv('UTF-8', 'cp874', $TypesStd1[0]['MOJOR_ID'].' '.$TypesStd1[0]['MAJOR_NAME'].' '.$TypesStd1[0]['faculty_name']), 0, 0, 'L');
$pdf->Ln();
$pdf->SetFont('angsana', '', 11);
$pdf->SetFillColor(139, 137, 137);
$pdf->Cell(10, 6, iconv('UTF-8', 'cp874', 'ลำดับ'), 1, 0, 'C', true);
$pdf->Cell(15, 6, iconv('UTF-8', 'cp874', 'รหัสรายการ'), 1, 0, 'C', true);
$pdf->Cell(120, 6, iconv('UTF-8', 'cp874', 'รายการ'), 1, 0, 'C', true);
$pdf->Cell(0, 6, iconv('UTF-8', 'cp874', 'จำนวนเงิน'), 1, 0, 'C', true);
$pdf->Ln();
$nubkit = 0;
$numsssss = 1;
//$sub_major = substr($sql_fprostd['oldid'],0,7);




if($ROB_ID==55 || $ROB_ID==37 || $ROB_ID==54 ){ 
    $rate_covid19 = ($rate*10)/100;
    $sum = $sum - $rate_covid19;
}


$pdf->Cell(10, 6, iconv('UTF-8', 'cp874', '1'), 0, 0, 'C');
$pdf->Cell(15, 6, iconv('UTF-8', 'cp874', 'R0002'), 0, 0, 'C');
$pdf->Cell(120, 6, iconv('UTF-8', 'cp874', 'ค่าธรรมเนียมการศึกษาประกอบด้วย'), 0, 0, 'L');

// echo $_GET['typebill'];


if($_GET['code_test']=='6511304021'){
    $rate = 7300;
    $sum = 18100;
}

if($creck_alarmni_gys>=1 && $_GET['typebill'] == "1"){
    if($ROB_ID!=93){
         $pdf->Cell(0, 6, iconv('UTF-8', 'cp874', number_format($rate-$sum, 2)), 0, 0, 'C');
    } else {
        $pdf->Cell(0, 6, iconv('UTF-8', 'cp874', number_format($sum, 2)), 0, 0, 'C');
    }
} else {
    $pdf->Cell(0, 6, iconv('UTF-8', 'cp874', number_format($sum, 2)), 0, 0, 'C');

}



$pdf->Ln();

if((substr($group_no,2,1) != 6)){
    $pdf->SetFont('angsana', 'I', 11);
    if($_GET['typebill']=='1'){ // จ่ายงวดที่ 1
        if($ROB_ID == '21'){ /// รอบนิทรรศการ  ฟรี
            $pdf->Cell(10, 6, iconv('UTF-8', 'cp874', ''), 0, 0, 'C');
            $pdf->Cell(15, 6, iconv('UTF-8', 'cp874', ''), 0, 0, 'C');
            $pdf->Cell(120, 6, iconv('UTF-8', 'cp874', '1. ค่าประกันของเสียหาย '.number_format($GUARANTEE).' บาท'), 0, 0, 'L');
            $pdf->Cell(0, 6, iconv('UTF-8', 'cp874', ''), 0, 0, 'C');
            $pdf->Ln();
            $pdf->Cell(10, 6, iconv('UTF-8', 'cp874', ''), 0, 0, 'C');
            $pdf->Cell(15, 6, iconv('UTF-8', 'cp874', ''), 0, 0, 'C');
            $pdf->Cell(120, 6, iconv('UTF-8', 'cp874', '2. ค่าธรรมเนียมการศึกษาเหมาจ่าย จำนวนหน่วยกิต '.$kedit.' หน่วยกิต  เป็นเงิน '.number_format($rate).' บาท (ชำระงวดที่ 1 จำนวน 5,000 บาท)'), 0, 0, 'L');
            $pdf->Cell(0, 6, iconv('UTF-8', 'cp874', ''), 0, 0, 'C');
            $pdf->Ln();
            $pdf->Cell(10, 6, iconv('UTF-8', 'cp874', ''), 0, 0, 'C');
            $pdf->Cell(15, 6, iconv('UTF-8', 'cp874', ''), 0, 0, 'C');
            $pdf->Cell(120, 6, iconv('UTF-8', 'cp874', '3. ค่าคู่มือนักศึกษา '.number_format($manual).' บาท'), 0, 0, 'L');
            $pdf->Cell(0, 6, iconv('UTF-8', 'cp874', ''), 0, 0, 'C');
            if($eng!=0){
                $pdf->Ln();
                $pdf->Cell(10, 6, iconv('UTF-8', 'cp874', ''), 0, 0, 'C');
                $pdf->Cell(15, 6, iconv('UTF-8', 'cp874', ''), 0, 0, 'C');
                $pdf->Cell(120, 6, iconv('UTF-8', 'cp874', '4. ค่าธรรมเนียมการศึกษาจากนักศึกษาที่ไม่ถือสัญชาติไทย '.number_format($eng).' บาท'), 0, 0, 'L');
                $pdf->Cell(0, 6, iconv('UTF-8', 'cp874', ''), 0, 0, 'C');
                $pdf->Ln(12);
                $pdf->Ln();
                $pdf->Ln(-22);
            } else {
                $pdf->Ln(12);
                $pdf->Ln();
                $pdf->Ln(-22);
            }
        } else {
            
            $pdf->Cell(10, 6, iconv('UTF-8', 'cp874', ''), 0, 0, 'C');
            $pdf->Cell(15, 6, iconv('UTF-8', 'cp874', ''), 0, 0, 'C');
            $pdf->Cell(120, 6, iconv('UTF-8', 'cp874', '1. ค่าธรรมเนียมขึ้นทะเบียนเป็นนักศึกษาใหม่ '.number_format($p1).' บาท'), 0, 0, 'L');
            $pdf->Cell(0, 6, iconv('UTF-8', 'cp874', ''), 0, 0, 'C');
            $pdf->Ln();
            $pdf->Cell(10, 6, iconv('UTF-8', 'cp874', ''), 0, 0, 'C');
            $pdf->Cell(15, 6, iconv('UTF-8', 'cp874', ''), 0, 0, 'C');
            $pdf->Cell(120, 6, iconv('UTF-8', 'cp874', '2. ค่าออกบัตรประจำตัวนักศึกษา '.number_format($p2).' บาท'), 0, 0, 'L');
            $pdf->Cell(0, 6, iconv('UTF-8', 'cp874', ''), 0, 0, 'C');
            $pdf->Ln();
            $pdf->Cell(10, 6, iconv('UTF-8', 'cp874', ''), 0, 0, 'C');
            $pdf->Cell(15, 6, iconv('UTF-8', 'cp874', ''), 0, 0, 'C');
            $pdf->Cell(120, 6, iconv('UTF-8', 'cp874', '3. ค่าประกันของเสียหาย '.number_format($GUARANTEE).' บาท'), 0, 0, 'L');
            $pdf->Cell(0, 6, iconv('UTF-8', 'cp874', ''), 0, 0, 'C');
            $pdf->Ln();
            $pdf->Cell(10, 6, iconv('UTF-8', 'cp874', ''), 0, 0, 'C');
            $pdf->Cell(15, 6, iconv('UTF-8', 'cp874', ''), 0, 0, 'C');
            if($creck_alarmni_gys>=1 && $_GET['typebill'] == "1"){
                if($ROB_ID==117){ //พยาบาล
                    $pdf->Cell(120, 6, iconv('UTF-8', 'cp874', '4. ค่าธรรมเนียมการศึกษาเหมาจ่าย จำนวนหน่วยกิต '.$kedit.' หน่วยกิต  เป็นเงิน '.number_format($rate).' บาท (ชำระงวดที่ 1 จำนวน 5,000 บาท) ไม่ต้องชำระเงินค่าธรรมเนียมการศึกษา'), 0, 0, 'L');
                } else {
                    $pdf->Cell(120, 6, iconv('UTF-8', 'cp874', '4. ค่าธรรมเนียมการศึกษาเหมาจ่าย จำนวนหน่วยกิต '.$kedit.' หน่วยกิต  เป็นเงิน '.number_format($rate).' บาท (ชำระงวดที่ 1 จำนวน 4,000 บาท) ไม่ต้องชำระเงินค่าธรรมเนียมการศึกษา'), 0, 0, 'L');
                }
            } else {
                if($ROB_ID==117){ //พยาบาล
                    $pdf->Cell(120, 6, iconv('UTF-8', 'cp874', '4. ค่าธรรมเนียมการศึกษาเหมาจ่าย จำนวนหน่วยกิต '.$kedit.' หน่วยกิต  เป็นเงิน '.number_format($rate).' บาท (ชำระงวดที่ 1 จำนวน 5,000 บาท)'), 0, 0, 'L');
                } else {
                    $pdf->Cell(120, 6, iconv('UTF-8', 'cp874', '4. ค่าธรรมเนียมการศึกษาเหมาจ่าย จำนวนหน่วยกิต '.$kedit.' หน่วยกิต  เป็นเงิน '.number_format($rate).' บาท (ชำระงวดที่ 1 จำนวน 4,000 บาท)'), 0, 0, 'L');
                }
            }
            
            

            $pdf->Cell(0, 6, iconv('UTF-8', 'cp874', ''), 0, 0, 'C');
            $pdf->Ln();
            $pdf->Cell(10, 6, iconv('UTF-8', 'cp874', ''), 0, 0, 'C');
            $pdf->Cell(15, 6, iconv('UTF-8', 'cp874', ''), 0, 0, 'C');
            $pdf->Cell(120, 6, iconv('UTF-8', 'cp874', '5. ค่าคู่มือนักศึกษา '.number_format($manual).' บาท'), 0, 0, 'L');
            $pdf->Cell(0, 6, iconv('UTF-8', 'cp874', ''), 0, 0, 'C');
            if($eng!=0){
                $pdf->Ln();
                $pdf->Cell(10, 6, iconv('UTF-8', 'cp874', ''), 0, 0, 'C');
                $pdf->Cell(15, 6, iconv('UTF-8', 'cp874', ''), 0, 0, 'C');
                $pdf->Cell(120, 6, iconv('UTF-8', 'cp874', '6. ค่าธรรมเนียมการศึกษาจากนักศึกษาที่ไม่ถือสัญชาติไทย '.number_format($eng).' บาท'), 0, 0, 'L');
                $pdf->Cell(0, 6, iconv('UTF-8', 'cp874', ''), 0, 0, 'C');
                $pdf->Ln(-22);
            } else {
                $pdf->Ln();
                $pdf->Ln(-22);
            }
        }
        
        
        
    } else if($_GET['typebill']=='2'){ // จ่ายงวดที่ 2  
        $pdf->Cell(10, 6, iconv('UTF-8', 'cp874', ''), 0, 0, 'C');
        $pdf->Cell(15, 6, iconv('UTF-8', 'cp874', ''), 0, 0, 'C');
        $pdf->Cell(120, 6, iconv('UTF-8', 'cp874', '1. ค่าธรรมเนียมการศึกษา จำนวนหน่วยกิต '.$kedit.' หน่วยกิต  เป็นเงิน '.number_format($rate).' บาท (ชำระงวดที่ 2 จำนวน '.number_format($sum).' บาท)'), 0, 0, 'L');
        $pdf->Cell(0, 6, iconv('UTF-8', 'cp874', ''), 0, 0, 'C');
        $pdf->Ln(30);
        $pdf->Ln(-22);
    } else if($_GET['typebill']=='3'){ // จ่ายเต็มจำนวน  
        if($ROB_ID == '21'){ /// รอบนิทรรศการ  ฟรี
            $pdf->Cell(10, 6, iconv('UTF-8', 'cp874', ''), 0, 0, 'C');
            $pdf->Cell(15, 6, iconv('UTF-8', 'cp874', ''), 0, 0, 'C');
            $pdf->Cell(120, 6, iconv('UTF-8', 'cp874', '1. ค่าประกันของเสียหาย '.number_format($GUARANTEE).' บาท'), 0, 0, 'L');
            $pdf->Cell(0, 6, iconv('UTF-8', 'cp874', ''), 0, 0, 'C');
            $pdf->Ln();
            $pdf->Cell(10, 6, iconv('UTF-8', 'cp874', ''), 0, 0, 'C');
            $pdf->Cell(15, 6, iconv('UTF-8', 'cp874', ''), 0, 0, 'C');
            $pdf->Cell(120, 6, iconv('UTF-8', 'cp874', '2. ค่าธรรมเนียมการศึกษาเหมาจ่าย จำนวนหน่วยกิต '.$kedit.' หน่วยกิต  เป็นเงิน '.number_format($rate).' บาท'), 0, 0, 'L');
            $pdf->Cell(0, 6, iconv('UTF-8', 'cp874', ''), 0, 0, 'C');
            $pdf->Ln();
            $pdf->Cell(10, 6, iconv('UTF-8', 'cp874', ''), 0, 0, 'C');
            $pdf->Cell(15, 6, iconv('UTF-8', 'cp874', ''), 0, 0, 'C');
            $pdf->Cell(120, 6, iconv('UTF-8', 'cp874', '3. ค่าคู่มือนักศึกษา '.number_format($manual).' บาท'), 0, 0, 'L');
            $pdf->Cell(0, 6, iconv('UTF-8', 'cp874', ''), 0, 0, 'C');
            if($eng!=0){
                $pdf->Ln();
                $pdf->Cell(10, 6, iconv('UTF-8', 'cp874', ''), 0, 0, 'C');
                $pdf->Cell(15, 6, iconv('UTF-8', 'cp874', ''), 0, 0, 'C');
                $pdf->Cell(120, 6, iconv('UTF-8', 'cp874', '4. ค่าธรรมเนียมการศึกษาจากนักศึกษาที่ไม่ถือสัญชาติไทย '.number_format($eng).' บาท'), 0, 0, 'L');
                $pdf->Cell(0, 6, iconv('UTF-8', 'cp874', ''), 0, 0, 'C');
                $pdf->Ln(12);
                $pdf->Ln(-22);
            } else {
                $pdf->Ln();
                $pdf->Ln(12);
                $pdf->Ln(-22);
            }
        } else {
            $pdf->Cell(10, 4, iconv('UTF-8', 'cp874', ''), 0, 0, 'C');
            $pdf->Cell(15, 4, iconv('UTF-8', 'cp874', ''), 0, 0, 'C');
            $pdf->Cell(120, 4, iconv('UTF-8', 'cp874', '1. ค่าธรรมเนียมขึ้นทะเบียนเป็นนักศึกษาใหม่ '.number_format($p1).' บาท'), 0, 0, 'L');
            $pdf->Cell(0, 4, iconv('UTF-8', 'cp874', ''), 0, 0, 'C');
            $pdf->Ln();
            $pdf->Cell(10, 4, iconv('UTF-8', 'cp874', ''), 0, 0, 'C');
            $pdf->Cell(15, 4, iconv('UTF-8', 'cp874', ''), 0, 0, 'C');
            $pdf->Cell(120, 4, iconv('UTF-8', 'cp874', '2. ค่าออกบัตรประจำตัวนักศึกษา '.number_format($p2).' บาท'), 0, 0, 'L');
            $pdf->Cell(0, 4, iconv('UTF-8', 'cp874', ''), 0, 0, 'C');
            $pdf->Ln();
            $pdf->Cell(10, 4, iconv('UTF-8', 'cp874', ''), 0, 0, 'C');
            $pdf->Cell(15, 4, iconv('UTF-8', 'cp874', ''), 0, 0, 'C');
            $pdf->Cell(120, 4, iconv('UTF-8', 'cp874', '3. ค่าประกันของเสียหาย '.number_format($GUARANTEE).' บาท'), 0, 0, 'L');
            $pdf->Cell(0, 4, iconv('UTF-8', 'cp874', ''), 0, 0, 'C');
            $pdf->Ln();
            $pdf->Cell(10, 4, iconv('UTF-8', 'cp874', ''), 0, 0, 'C');
            $pdf->Cell(15, 4, iconv('UTF-8', 'cp874', ''), 0, 0, 'C');
            if($creck_alarmni_gys>=1 && $_GET['typebill'] == "1"){
                $pdf->Cell(120, 4, iconv('UTF-8', 'cp874', '4. ค่าธรรมเนียมการศึกษาเหมาจ่าย จำนวนหน่วยกิต '.$kedit.' หน่วยกิต  เป็นเงิน '.number_format($rate).' บาท ไม่ต้องชำระเงินค่าธรรมเนียมการศึกษา'), 0, 0, 'L');
            } else {
                $pdf->Cell(120, 4, iconv('UTF-8', 'cp874', '4. ค่าธรรมเนียมการศึกษาเหมาจ่าย จำนวนหน่วยกิต '.$kedit.' หน่วยกิต  เป็นเงิน '.number_format($rate).' บาท'), 0, 0, 'L');
            }
            
            $pdf->Cell(0, 4, iconv('UTF-8', 'cp874', ''), 0, 0, 'C');
            $pdf->Ln();
            $pdf->Cell(10, 4, iconv('UTF-8', 'cp874', ''), 0, 0, 'C');
            $pdf->Cell(15, 4, iconv('UTF-8', 'cp874', ''), 0, 0, 'C');
            $pdf->Cell(120, 4, iconv('UTF-8', 'cp874', '5. ค่าคู่มือนักศึกษา '.number_format($manual).' บาท'), 0, 0, 'L');
            $pdf->Cell(0, 4, iconv('UTF-8', 'cp874', ''), 0, 0, 'C');
            if($eng!=0){
                $pdf->Ln();
                $pdf->Cell(10, 4, iconv('UTF-8', 'cp874', ''), 0, 0, 'C');
                $pdf->Cell(15, 4, iconv('UTF-8', 'cp874', ''), 0, 0, 'C');
                $pdf->Cell(120, 4, iconv('UTF-8', 'cp874', '6. ค่าธรรมเนียมการศึกษาจากนักศึกษาที่ไม่ถือสัญชาติไทย '.number_format($eng).' บาท'), 0, 0, 'L');
                $pdf->Cell(0, 4, iconv('UTF-8', 'cp874', ''), 0, 0, 'C');
                $pdf->Ln(-22);
            } else {
                if($ROB_ID==72 || $ROB_ID==82 || $ROB_ID==85){ 
                    $rate_covid19 = ($rate*10)/100;
                    $sum = $sum - $rate_covid19;
                    $pdf->Ln();
                    $pdf->SetFont('angsana','I',11);
                    $pdf->Cell(10,4,iconv( 'UTF-8','cp874',''),0,0,'C');
                    $pdf->Cell(15,4,iconv( 'UTF-8','cp874',''),0,0,'C');
                    $pdf->Cell(100,4,iconv( 'UTF-8','cp874','ส่วนลด 10 % ในสถานการณ์โรคระบาดของโรคติดต่อเชื้อไวรัสโคโรนา 2019 เป็นเงิน '.number_format($rate_covid19,2).' บาท'),0,0,'L');
                    $pdf->Cell(20,4,iconv( 'UTF-8','cp874',''),0,0,'C');	
                    $pdf->Cell(0,4,iconv( 'UTF-8','cp874',''),0,0,'L');
                    $pdf->Ln();
                    $pdf->Ln(-12);
                } else {
                  $pdf->Ln();
                $pdf->Ln(-22);  
                $pdf->Ln();
                $pdf->Ln();
                $pdf->Ln(6);
                
                }
                
            }

            
        }
        
        
    } else if($_GET['typebill']=='4'){ // จ่ายค่าส่วนต่าง กยศ 800 บาท 
        if(empty($_GET['money'])){
            $pdf->Cell(10, 4, iconv('UTF-8', 'cp874', ''), 0, 0, 'C');
            $pdf->Cell(15, 4, iconv('UTF-8', 'cp874', ''), 0, 0, 'C');
            $pdf->Cell(120, 4, iconv('UTF-8', 'cp874', '1. ค่าธรรมเนียมขึ้นทะเบียนเป็นนักศึกษาใหม่ '.number_format($p1).' บาท'), 0, 0, 'L');
            $pdf->Cell(0, 4, iconv('UTF-8', 'cp874', ''), 0, 0, 'C');
            $pdf->Ln();
            $pdf->Cell(10, 4, iconv('UTF-8', 'cp874', ''), 0, 0, 'C');
            $pdf->Cell(15, 4, iconv('UTF-8', 'cp874', ''), 0, 0, 'C');
            $pdf->Cell(120, 4, iconv('UTF-8', 'cp874', '2. ค่าออกบัตรประจำตัวนักศึกษา '.number_format($p2).' บาท'), 0, 0, 'L');
            $pdf->Cell(0, 4, iconv('UTF-8', 'cp874', ''), 0, 0, 'C');
            $pdf->Ln();
            $pdf->Cell(10, 4, iconv('UTF-8', 'cp874', ''), 0, 0, 'C');
            $pdf->Cell(15, 4, iconv('UTF-8', 'cp874', ''), 0, 0, 'C');
            $pdf->Cell(120, 4, iconv('UTF-8', 'cp874', '3. ค่าประกันของเสียหาย '.number_format($GUARANTEE).' บาท'), 0, 0, 'L');
            $pdf->Cell(0, 4, iconv('UTF-8', 'cp874', ''), 0, 0, 'C');
            $pdf->Ln();
            $pdf->Cell(10, 4, iconv('UTF-8', 'cp874', ''), 0, 0, 'C');
            $pdf->Cell(15, 4, iconv('UTF-8', 'cp874', ''), 0, 0, 'C');
            $pdf->Cell(120, 4, iconv('UTF-8', 'cp874', '4. ค่าธรรมเนียมการศึกษาเหมาจ่าย จำนวนหน่วยกิต '.$kedit.' หน่วยกิต  เป็นเงิน '.number_format($rate).' บาท (ไม่ต้องชำระเงินค่าธรรมเนียมทางการศึกษา)' ), 0, 0, 'L');
            $pdf->Cell(0, 4, iconv('UTF-8', 'cp874', ''), 0, 0, 'C');
            $pdf->Ln();
            $pdf->Cell(10, 4, iconv('UTF-8', 'cp874', ''), 0, 0, 'C');
            $pdf->Cell(15, 4, iconv('UTF-8', 'cp874', ''), 0, 0, 'C');
            $pdf->Cell(120, 4, iconv('UTF-8', 'cp874', '5. ค่าคู่มือนักศึกษา '.number_format($manual).' บาท'), 0, 0, 'L');
            $pdf->Cell(0, 4, iconv('UTF-8', 'cp874', ''), 0, 0, 'C');
            $pdf->Ln();
            $pdf->Ln(14);
            $pdf->Ln(-22);
        } else {
            $pdf->Cell(10, 4, iconv('UTF-8', 'cp874', ''), 0, 0, 'C');
            $pdf->Cell(15, 4, iconv('UTF-8', 'cp874', ''), 0, 0, 'C');
            $pdf->Cell(120, 4, iconv('UTF-8', 'cp874', '1. ชำระเงินค่าส่วนต่าง '.number_format($_GET['money']).' บาท'), 0, 0, 'L');
            $pdf->Cell(0, 4, iconv('UTF-8', 'cp874', ''), 0, 0, 'C');
            $pdf->Ln(16);
            $pdf->Ln(14);
            $pdf->Ln(-22);
        }
            
    }
} else if((substr($group_no,2,1) == 6)){
    $pdf->SetFont('angsana', 'I', 11);
    if($_GET['typebill']=='1'){ // จ่ายงวดที่ 1
        $pdf->Cell(10, 6, iconv('UTF-8', 'cp874', ''), 0, 0, 'C');
        $pdf->Cell(15, 6, iconv('UTF-8', 'cp874', ''), 0, 0, 'C');
        $pdf->Cell(120, 6, iconv('UTF-8', 'cp874', '1. ค่าธรรมเนียมการศึกษา หลักสูตรประกาศนียบัตรบัณฑิตวิชาชีพครู (ชำระงวดที่ 1 จำนวน '.number_format($sum).' บาท)'), 0, 0, 'L');
        $pdf->Cell(0, 6, iconv('UTF-8', 'cp874', ''), 0, 0, 'C');
        $pdf->Ln(30);
        $pdf->Ln(-22);
    } else if($_GET['typebill']=='2'){ // จ่ายงวดที่ 2
        $pdf->Cell(10, 6, iconv('UTF-8', 'cp874', ''), 0, 0, 'C');
        $pdf->Cell(15, 6, iconv('UTF-8', 'cp874', ''), 0, 0, 'C');
        $pdf->Cell(120, 6, iconv('UTF-8', 'cp874', '1. ค่าธรรมเนียมการศึกษา หลักสูตรประกาศนียบัตรบัณฑิตวิชาชีพครู (ชำระงวดที่ 2 จำนวน '.number_format($sum).' บาท)'), 0, 0, 'L');
        $pdf->Cell(0, 6, iconv('UTF-8', 'cp874', ''), 0, 0, 'C');
        $pdf->Ln(30);
        $pdf->Ln(-22);
    } else if($_GET['typebill']=='3'){ // จ่ายงวดที่ 3
        $pdf->Cell(10, 6, iconv('UTF-8', 'cp874', ''), 0, 0, 'C');
        $pdf->Cell(15, 6, iconv('UTF-8', 'cp874', ''), 0, 0, 'C');
        $pdf->Cell(120, 6, iconv('UTF-8', 'cp874', '1. ค่าธรรมเนียมการศึกษา หลักสูตรประกาศนียบัตรบัณฑิตวิชาชีพครู (ชำระงวดที่ 3 จำนวน '.number_format($sum).' บาท)'), 0, 0, 'L');
        $pdf->Cell(0, 6, iconv('UTF-8', 'cp874', ''), 0, 0, 'C');
        $pdf->Ln(30);
        $pdf->Ln(-22);
    } else if($_GET['typebill']=='4'){ // จ่ายเต็มจำนวน
        $pdf->Cell(10, 6, iconv('UTF-8', 'cp874', ''), 0, 0, 'C');
        $pdf->Cell(15, 6, iconv('UTF-8', 'cp874', ''), 0, 0, 'C');
        $pdf->Cell(120, 6, iconv('UTF-8', 'cp874', '1. ค่าธรรมเนียมการศึกษา หลักสูตรประกาศนียบัตรบัณฑิตวิชาชีพครู (ชำระเต็มจำนวน จำนวน '.number_format($sum).' บาท)'), 0, 0, 'L');
        $pdf->Cell(0, 6, iconv('UTF-8', 'cp874', ''), 0, 0, 'C');
        $pdf->Ln(30);
        $pdf->Ln(-22);
    }
}

    if($TypesStd1[0]['REGISTER_STATUS_BILL_ROB1_BOOK']!="" && $_GET['typebill']=='1'){
        $pdf->SetFont('angsana', 'I', 16);
        $pdf->Cell(10, 6, iconv('UTF-8', 'cp874', ''), 0, 0, 'C');
        $pdf->Cell(15, 6, iconv('UTF-8', 'cp874', ''), 0, 0, 'C');
        $pdf->Cell(100,4,iconv( 'UTF-8','cp874','เล่มที่ใบเสร็จ '.$TypesStd1[0]['REGISTER_STATUS_BILL_ROB1_BOOK'].' เลขที่ใบเสร็จ '.$TypesStd1[0]['REGISTER_STATUS_BILL_ROB1_NO']),0,0,'L');
        $pdf->Ln();
    }
    if($TypesStd1[0]['REGISTER_STATUS_BILL_ROB2_BOOK']!="" && $_GET['typebill']=='2'){
        $pdf->SetFont('angsana', 'I', 16);
        $pdf->Cell(10, 6, iconv('UTF-8', 'cp874', ''), 0, 0, 'C');
        $pdf->Cell(15, 6, iconv('UTF-8', 'cp874', ''), 0, 0, 'C');
        $pdf->Cell(100,4,iconv( 'UTF-8','cp874','เล่มที่ใบเสร็จ '.$TypesStd1[0]['REGISTER_STATUS_BILL_ROB2_BOOK'].' เลขที่ใบเสร็จ '.$TypesStd1[0]['REGISTER_STATUS_BILL_ROB2_NO']),0,0,'L');
        $pdf->Ln();
    }
    if($TypesStd1[0]['REGISTER_STATUS_BILL_ROB3_BOOK']!="" && $_GET['typebill']=='3'){
        $pdf->SetFont('angsana', 'I', 16);
        $pdf->Cell(10, 6, iconv('UTF-8', 'cp874', ''), 0, 0, 'C');
        $pdf->Cell(15, 6, iconv('UTF-8', 'cp874', ''), 0, 0, 'C');
        $pdf->Cell(100,4,iconv( 'UTF-8','cp874','เล่มที่ใบเสร็จ '.$TypesStd1[0]['REGISTER_STATUS_BILL_ROB3_BOOK'].' เลขที่ใบเสร็จ '.$TypesStd1[0]['REGISTER_STATUS_BILL_ROB3_NO']),0,0,'L');
        $pdf->Ln();
    }
    if($TypesStd1[0]['REGISTER_STATUS_BILL_ROBFULL_BOOK']!="" && $_GET['typebill']=='4'){
        $pdf->SetFont('angsana', 'I', 16);
        $pdf->Cell(10, 6, iconv('UTF-8', 'cp874', ''), 0, 0, 'C');
        $pdf->Cell(15, 6, iconv('UTF-8', 'cp874', ''), 0, 0, 'C');
        $pdf->Cell(100,4,iconv( 'UTF-8','cp874','เล่มที่ใบเสร็จ '.$TypesStd1[0]['REGISTER_STATUS_BILL_ROBFULL_BOOK'].' เลขที่ใบเสร็จ '.$TypesStd1[0]['REGISTER_STATUS_BILL_ROBFULL_NO']),0,0,'L');
        $pdf->Ln();
    }
        
    // $pdf->Ln();
    
        $pdf->Ln();
        $pdf->Ln();
        $pdf->Ln(4);

        if((substr($group_no,2,1) != 6)) {
            $pdf->SetFont('angsana', 'I', 12);
            $pdf->Cell(10, 6, iconv('UTF-8', 'cp874', ''), 0, 0, 'C');
            $pdf->Cell(15, 6, iconv('UTF-8', 'cp874', ''), 0, 0, 'C');
            $pdf->Cell(120, 35, iconv('UTF-8', 'cp874', '*หมายเหตุ : ค่าธรรมเนียมการศึกษาจำนวน ' . number_format($sum, 2) . ' บาท ผู้ที่รายงานตัวเข้าเป็นนักศึกษา'), 0, 0, 'L');
            $pdf->Cell(0, 6, iconv('UTF-8', 'cp874', ''), 0, 0, 'C');
            $pdf->Ln(5);
            $pdf->Cell(10, 6, iconv('UTF-8', 'cp874', ''), 0, 0, 'C');
            $pdf->Cell(15, 6, iconv('UTF-8', 'cp874', ''), 0, 0, 'C');
            $pdf->Cell(120, 35, iconv('UTF-8', 'cp874', 'มหาวิทยาลัยราชภัฏกำแพงเพชร และชำระเงินค่าธรรมเนียมการศึกษาดังกล่าวแล้ว มหาวิทยาลัยฯ จะไม่คืนเงิน'), 0, 0, 'L');
            $pdf->Cell(0, 6, iconv('UTF-8', 'cp874', ''), 0, 0, 'C');
            $pdf->Ln(5);
            $pdf->Cell(10, 6, iconv('UTF-8', 'cp874', ''), 0, 0, 'C');
            $pdf->Cell(15, 6, iconv('UTF-8', 'cp874', ''), 0, 0, 'C');
            $pdf->Cell(120, 35, iconv('UTF-8', 'cp874', 'ค่าธรรมเนียมการศึกษาให้แก่นักศึกษา ตามระเบียบมหาวิทยาลัยราชภัฏกำแพงเพชร ว่าด้วยการเก็บเงิน'), 0, 0, 'L');
            $pdf->Cell(0, 6, iconv('UTF-8', 'cp874', ''), 0, 0, 'C');
            $pdf->Ln(5);
            $pdf->Cell(10, 6, iconv('UTF-8', 'cp874', ''), 0, 0, 'C');
            $pdf->Cell(15, 6, iconv('UTF-8', 'cp874', ''), 0, 0, 'C');
            $pdf->Cell(120, 35, iconv('UTF-8', 'cp874', 'ค่าธรรมเนียมการศึกษาระดับปริญญาตรี ภาคปกติ พ.ศ. 2553 ข้อ 12'), 0, 0, 'L');
            $pdf->Cell(0, 6, iconv('UTF-8', 'cp874', ''), 0, 0, 'C');
            $pdf->Ln(5);
        } else {
            $pdf->SetFont('angsana', 'I', 12);
            $pdf->Cell(10, 6, iconv('UTF-8', 'cp874', ''), 0, 0, 'C');
            $pdf->Cell(15, 6, iconv('UTF-8', 'cp874', ''), 0, 0, 'C');
            $pdf->Cell(120, 35, iconv('UTF-8', 'cp874', '*หมายเหตุ : ค่าธรรมเนียมการศึกษาจำนวน ' . number_format($sum, 2) . ' บาท ผู้ที่รายงานตัวเข้าเป็นนักศึกษา'), 0, 0, 'L');
            $pdf->Cell(0, 6, iconv('UTF-8', 'cp874', ''), 0, 0, 'C');
            $pdf->Ln(5);
            $pdf->Cell(10, 6, iconv('UTF-8', 'cp874', ''), 0, 0, 'C');
            $pdf->Cell(15, 6, iconv('UTF-8', 'cp874', ''), 0, 0, 'C');
            $pdf->Cell(120, 35, iconv('UTF-8', 'cp874', 'มหาวิทยาลัยราชภัฏกำแพงเพชร และชำระเงินค่าธรรมเนียมการศึกษาดังกล่าวแล้ว มหาวิทยาลัยฯ จะไม่คืนเงิน'), 0, 0, 'L');
            $pdf->Cell(0, 6, iconv('UTF-8', 'cp874', ''), 0, 0, 'C');
            $pdf->Ln(5);
            $pdf->Cell(10, 6, iconv('UTF-8', 'cp874', ''), 0, 0, 'C');
            $pdf->Cell(15, 6, iconv('UTF-8', 'cp874', ''), 0, 0, 'C');
            $pdf->Cell(120, 35, iconv('UTF-8', 'cp874', 'ค่าธรรมเนียมการศึกษาให้แก่นักศึกษา ตามระเบียบมหาวิทยาลัยราชภัฏกำแพงเพชร ว่าด้วยการเก็บเงิน'), 0, 0, 'L');
            $pdf->Cell(0, 6, iconv('UTF-8', 'cp874', ''), 0, 0, 'C');
            $pdf->Ln(5);
            $pdf->Cell(10, 6, iconv('UTF-8', 'cp874', ''), 0, 0, 'C');
            $pdf->Cell(15, 6, iconv('UTF-8', 'cp874', ''), 0, 0, 'C');
            $pdf->Cell(120, 35, iconv('UTF-8', 'cp874', 'ค่าธรรมเนียมการศึกษา'), 0, 0, 'L');
            $pdf->Cell(0, 6, iconv('UTF-8', 'cp874', ''), 0, 0, 'C');
            $pdf->Ln(5);
        }
        

         $pdf->Ln(-8);


$pdf->Ln();
$pdf->SetFont('angsana', '', 11);
$bbbb = 48;
// $bbbb = 34;
$pdf->Ln(-$bbbb);


$pdf->Cell(10, 66, '', 1, 0, 'C');
$pdf->Cell(15, 66, '', 1, 0, 'R');
$pdf->Cell(120, 66, '', 1, 0, 'R');
//$pdf->Cell(20,78,'',1,0,'R');
$pdf->Cell(0, 66, '', 1, 0, 'R');
$pdf->Ln();
$pdf->SetFont('angsana', '', 11);
$pdf->SetFillColor(139, 137, 137);
$pdf->Cell(10, 6, '', 1, 0, 'C', true);
$pdf->Cell(15, 6, iconv('UTF-8', 'cp874', ''), 1, 0, 'R', true);
$pdf->Cell(120, 6, iconv('UTF-8', 'cp874', 'จำนวนทั้งหมด'), 1, 0, 'R', true);
//$pdf->Cell(20,6,iconv( 'UTF-8','cp874' , ''),1,0,'C',true);
if($creck_alarmni_gys>=1 && $_GET['typebill'] == "1"){
    $pdf->Cell(0, 6, iconv('UTF-8', 'cp874', number_format($rate-$sum, 2)), 1, 0, 'C', true);
} else {
    $pdf->Cell(0, 6, iconv('UTF-8', 'cp874', number_format($sum, 2)), 1, 0, 'C', true);
}

$pdf->Ln();
$pdf->SetFont('angsana', '', 12);

// $thaiformat_number = "สองร้อยบาทถ้วน";
if($creck_alarmni_gys>=1 && $_GET['typebill'] == "1"){
    $thaiformat_number = convert($rate-$sum);
    $pdf->Cell(140, 6, iconv('UTF-8', 'cp874', 'รวมยอดชำระเป็นเงิน ' . number_format($rate-$sum, 2) . ' บาท (' . $thaiformat_number . ')'), 0, 0, 'L');
} else {
    $thaiformat_number = convert($sum);
    $pdf->Cell(140, 6, iconv('UTF-8', 'cp874', 'รวมยอดชำระเป็นเงิน ' . number_format($sum, 2) . ' บาท (' . $thaiformat_number . ')'), 0, 0, 'L');
}

$pdf->Ln();
$pdf->SetFont('angsana', '', 10);
$pdf->Cell(140, 3, iconv('UTF-8', 'cp874', '*หมายเหตุ ค่าธรรมเนียมการศึกษาดังกล่าวยังไม่รวมค่าธรรมเนียมธนาคารหรือค่าธรรมเนียมเคาน์เตอร์เซอร์วิสหรือจุดบริการแคชเชียร์ บิ๊กซี'), 0, 0, 'L');
$pdf->Ln();
$pdf->Cell(140, 2, '', 0, 0, 'L');
$pdf->Ln();
$pdf->Cell(140, -11, '', 1, 0, 'L');
$pdf->Ln(0);
$pdf->SetFont('angsana', '', 11);
$pdf->Cell(140, 4, iconv('UTF-8', 'cp874', 'สำนักส่งเสริมวิชาการและงานทะเบียน มหาวิทยาลัยราชภัฏกำแพงเพชร'), 0, 0, 'L');
$pdf->Ln();
if($ROB_ID==44){ //พยาบาล
    $pdf->Cell(140, 4, iconv('UTF-8', 'cp874', '69 ม.1 ต.นครชุม อ.เมือง จ.กำแพงเพชร 62000 โทรศัพท์  055-706547'), 0, 0, 'L');
} else {
    $pdf->Cell(140, 4, iconv('UTF-8', 'cp874', '69 ม.1 ต.นครชุม อ.เมือง จ.กำแพงเพชร 62000 โทรศัพท์ 0-5570-6547 ต่อ 1022, 1023'), 0, 0, 'L');
}

$pdf->Ln();
// $pdf->Cell(140, 4, iconv('UTF-8', 'cp874', 'โทรศัพท์ 0-5570-6547, 0-5570-6555 ต่อ 1477,1478,1479,1022,1023 โทรสาร 0-5570-6552'), 0, 0, 'L');
$pdf->Cell(140, 4, iconv('UTF-8', 'cp874', 'Email: admintb@kpru.ac.th website:  https://www.kpru.ac.th หรือ https://tabian.kpru.ac.th'), 0, 0, 'L');
$pdf->Ln();
$pdf->Cell(140, -12, '', 1, 0, 'L');
$pdf->SetFont('angsana', '', 12);
$pdf->Cell(0, -18, iconv('UTF-8', 'cp874', 'สำหรับเจ้าหน้าที่ผู้รับเงิน'), 0, 1, 'L');
$pdf->Ln(12);
$pdf->Cell(140, 0, '', 0, 0, 'L');
$pdf->SetFont('angsana', '', 10);
$pdf->Cell(0, 6, iconv('UTF-8', 'cp874', 'ผู้รับเงิน........................................................'), 0, 1, 'L');
$pdf->Cell(0, -23, '', 1, 1, 'L');
$pdf->Ln(25);
$pdf->SetFont('angsana', '', 11);
/*$pdf->Cell(0,2,iconv( 'UTF-8','cp874' , 'การสมัครสอบคัดเลือกจะสมบูรณ์เมื่อมหาวิทยาลัยได้รับชำระครบถ้วนตามจำนวนเงินที่ระบุ'),0,1,'L');
$pdf->Ln();
$pdf->Cell(0,2,iconv( 'UTF-8','cp874' , 'โปรดตรวจสอบรายการให้ถูกต้อง พร้อมทั้งนำเงินสดไปติดต่อชำระเงินที่ธนาคารกรุงไทยทุกสาขาทั่วประเทศ'),0,1,'L');
$pdf->Ln();
$pdf->Cell(0,2,iconv( 'UTF-8','cp874' , 'ต้องชำระเงินภายในวันที่ระบุไว้เท่านั้น หากพ้นกำหนดแล้ว การสมัครสอบคัดเลือกถือเป็นโมฆะ'),0,1,'L');
$pdf->Ln();*/
$pdf->Cell(0, 2, iconv('UTF-8', 'cp874', '         ข้าพเจ้าขอรับรองว่า ข้อความทั้งหมดเป็นความจริงทุกประการ หากตรวจสอบแล้วพบว่าข้าพเจ้าขาดคุณสมบัติอย่างใดอย่างหนึ่งหรือฝ่าฝืนประกาศการสมัครที่มหาวิทยาลัยฯ'), 0, 1, 'L');
$pdf->Ln();
$pdf->Cell(0, 2, iconv('UTF-8', 'cp874', 'กำหนด หรือตรวจสอบพบว่ามีการปลอมแปลงเอกสาร ข้าพเจ้ายินยอมให้ตัดสิทธิ์จากการศึกษาในมหาวิทยาลัยราชภัฏกำแพงเพชรทุกกรณี และการสมัครสอบคัดเลือกจะสมบูรณ์'), 0, 1, 'L');
$pdf->Ln();
$pdf->Cell(0, 2, iconv('UTF-8', 'cp874', 'เมื่อมหาวิทยาลัยฯได้รับจำนวนเงินที่ชำระครบถ้วนตามที่ระบุ โปรดตรวจสอบรายการให้ถูกต้อง พร้อมทั้งนำเงินสดไปติดต่อชำระเงินที่ธนาคารกรุงไทย หรือที่เคาน์เตอร์เซอร์วิสทั่ว'), 0, 1, 'L');
$pdf->Ln();
$pdf->Cell(0, 2, iconv('UTF-8', 'cp874', 'ประเทศ ต้องชำระเงินภายในวันที่ระบุไว้เท่านั้น หากพ้นกำหนดแล้ว การสมัครสอบคัดเลือกถือเป็นโมฆะ'), 0, 1, 'L');
$pdf->Ln(3);
$pdf->Cell(0, 2, iconv('UTF-8', 'cp874', ''), 0, 1, 'R');

$pdf->Ln(-2);

$pdf->Cell(3, 3, ' ', 0, 0, 'R');
$pdf->SetFont('angsana', '', 8);
$pdf->Cell(0, 2, iconv('UTF-8', 'cp874', 'กรุณาตัดตามรอบปรุ/Please cut along the dotted line'), 0, 1, 'L');
$pdf->SetFont('angsana', '', 10);
$pdf->Cell(0, 1, '--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------', 0, 1, 'L');
$pdf->Image('public/images/cutico.jpg', 6, 173, 5, 0, 'JPG');
$pdf->Ln(2);
$pdf->SetFont('angsana', '', 8);
$pdf->SetFillColor(200, 220, 255);
$pdf->Cell(160, 6, '', 0, 0, 'C');
$pdf->Cell(0, 6, iconv('UTF-8', 'cp874', 'สำหรับธนาคาร'), 1, 0, 'C', true);
$pdf->Ln();
$pdf->Ln(3);
//$pdf->Image('public/images/kpru-logo-line2.png',8,207,10,0,'PNG');
$pdf->Image('public/images/kpru-logo-line2.png', 13, 185, 10, 0, 'PNG');
//$pdf->Image('https://e-student.kpru.ac.th/images/ktb.jpg',18,197,50,0,'JPG');
//$pdf->Image('logo3.jpg',6,169);

$robClose = explode('-', $ROB_CLOSE, 3);
$robCloseY = $robClose[0] + 543;
$robCloseM = $robClose[1];
$robCloseD = $robClose[2];

$robOpen = explode('-', $ROB_OPEN, 3);
$robOpenY = $robOpen[0] + 543;
$robOpenM = $robOpen[1];
$robOpenD = $robOpen[2];

//    echo $ROB_CLOSE."<br>";
//	echo $robCloseD." ".$robCloseM." ".substr($robCloseY,2);

$setTaxId = "0994000494246"; // 13 digit
$setServiceCode = "00";
$ref1 = $_GET['code_test'];
$ref2 = $setYear . substr($_GET['code_test'], 2, 1) . $robCloseD . $robCloseM . substr($robCloseY, 2);
if($creck_alarmni_gys>=1 && $_GET['typebill'] == "1"){
    
    if($ROB_ID!=93){
        $money = $rate-$sum;
        $moneyss = $rate-$sum;
   } else {
        $money = $sum;
        $moneyss = $sum;
   }
} else {
    $money = $sum;
    $moneyss = $sum;
}

$checkSumCode = 0;
//// Start Creckdigit ////////
if(strlen($moneyss)==3){
    $creckdigit = $ref1 . $ref2 . "00" . $moneyss;
} else if(strlen($moneyss)==4){
    $creckdigit = $ref1 . $ref2 . "0" . $moneyss;
} else if(strlen($moneyss)==5){
    $creckdigit = $ref1 . $ref2 . $moneyss;
}



for($i=0;$i<strlen($creckdigit);$i+=3){
    $countdigit1[$i] = $creckdigit[$i]*5;
    
    $countdigit1[$i+1] = $creckdigit[$i+1]*5;
    $countdigit1[$i+2] = $creckdigit[$i+2]*4;
    //  echo $countdigit1[$i]." ".$countdigit1[$i+1]." ".$countdigit1[$i+2]." ";
}
for ($j=0;$j<$i;$j++)
{
    $checkSumCode += $countdigit1[$j];
}
// echo $checkSumCode;
$checkSumCode = ($checkSumCode*79) % 100;
if ($checkSumCode < 10)
{
    $checkSumCode = "0".$checkSumCode;	
}



// for ($i = 0; $i < strlen($creckdigit); $i += 3) {
//     $countdigit1[$i] = $creckdigit[$i] * 6;
//     $countdigit1[$i + 1] = $creckdigit[$i + 1] * 2;
//     $countdigit1[$i + 2] = $creckdigit[$i + 2] * 9;
// }
// for ($j = 0; $j < $i; $j++) {
//     $checkSumCode += $countdigit1[$j];
// }
// $checkSumCode = ($checkSumCode * 3) % 100;
// if ($checkSumCode < 10) {
//     $checkSumCode = "0" . $checkSumCode;
// }

$ref2 = $ref2 . $checkSumCode;

//// End Creckdigit ////////
$barcode = "|" . $setTaxId . $setServiceCode . Chr(13) . $ref1 . Chr(13) . $ref2 . Chr(13) . $money . "00";
//$barcode = "|".$setTaxId.$setServiceCode."%20".$ref1."%20".$ref2."%20".$money."00";
//$barcode = "|".$setTaxId.$setServiceCode."%0D".$ref1."%0D".$ref2."%0D".$money."00";
//ชุดรหัสที่ใช้สร้างบาร์โค้ด
$barcodeP = "|" . $setTaxId . $setServiceCode . " " . $ref1 . " " . $ref2 . " " . $money . "00"; //ชุดรหัสที่นำมาแสดงด้านล่างบาร์โค้ด


$pdf->Cell(15, 4, '', 0, 0, 'C');
$pdf->SetFont('angsana', '', 16);
$pdf->Cell(70, 4, iconv('UTF-8', 'cp874', 'มหาวิทยาลัยราชภัฏกำแพงเพชร'), 0, 0, 'L');
$pdf->Cell(30, 4, iconv('UTF-8', 'cp874', ''), 0, 0, 'C');
$pdf->Cell(63, 4, iconv('UTF-8', 'cp874', 'ใบแจ้งการชำระเงิน'), 0, 0, 'R');

$pdf->Ln();
$pdf->Cell(15, 3, '', 0, 0, 'C');
$pdf->SetFont('angsana', '', 13);
$pdf->Cell(70, 4, iconv('UTF-8', 'cp874', 'Kampheang Phet Rajabhat University'), 0, 0, 'L');
$pdf->Cell(30, 4, iconv('UTF-8', 'cp874', ''), 0, 0, 'C');
$pdf->Cell(63, 3, iconv('UTF-8', 'cp874', 'Payment of Student Account'), 0, 0, 'R');
$pdf->Ln();
$pdf->Cell(15, 3, '', 0, 0, 'C');
$pdf->SetFont('angsana', '', 10);
$pdf->Cell(70, 4, iconv('UTF-8', 'cp874', ''), 0, 0, 'L');
$pdf->Cell(0, 3, iconv('UTF-8', 'cp874', ''), 0, 0, 'R');
$pdf->Ln();
$pdf->Cell(0, 2, '', 0, 0, 'L');
$pdf->Ln();
$pdf->Cell(0, -15, '', 1, 0, 'R');  //ใส่กรอบ
$pdf->Ln(0);
$j = date("d");
$m = date("M");
$y = date("Y");

$allyesr = $j . $m . $y;
/*$tes = "ReportGrade/".$allyesr;

if(!@mkdir($tes,0,true))
{
    mkdir($tes);
} */

/////////////////////////// /////////////////
//ใส่โลโก้ธนาคาร
$pdf->Image('public/images/ktb_bank.jpg',18,201,5,0,'JPG');
// $pdf->Image(URL.'public/images/unnamed.jpg',13,209,5,0,'JPG');
// $pdf->Image(URL.'public/images/aomsin.jpg',14,218,4,0,'JPG');
$pdf->Image('public/images/counter_bank.jpg',17,212,8,0,'JPG');
// $pdf->Image(URL.'public/images/BBK.jpg',14,224,4,0,'JPG');
// $pdf->Image('public/images/bigc.jpg',19,207,3,0,'JPG');

$pdf->Ln(3);
$pdf->Cell(3,3,' ',0,0,'R');
$pdf->Cell(3,3,' ',1,0,'R');
$pdf->Cell(9,3,' ',0,0,'R');
$pdf->SetFont('angsana','',12);
$pdf->Cell(80,3,iconv( 'UTF-8','cp874' , 'ธ.กรุงไทย COMP CODE: 80427'),0,0,'L');
//$pdf->Cell(80,3,iconv( 'UTF-8','cp874' , ''),0,0,'L');
$pdf->SetFont('angsana','',12);
$pdf->SetFont('angsana','',10);
$pdf->Cell(35,3,iconv( 'UTF-8','cp874' , 'ชื่อ-สกุล :'),0,0,'R');
$pdf->SetFont('angsana','',18);
$pdf->Cell(58,5,iconv( 'UTF-8','cp874' , $TypesStd1[0]['FULLNAME'] . $TypesStd1[0]['FRISTNAME_LOGIN'] . '  ' . $TypesStd1[0]['LASTNAME_LOGIN']),0,0,'L', true);
$pdf->Ln(5);
$pdf->Cell(3,3,' ',0,0,'R');
$pdf->Cell(3,3,' ',0,0,'R');
$pdf->Cell(9,3,' ',0,0,'R');
$pdf->SetFont('angsana','',12);
// $pdf->Cell(80,3,iconv( 'UTF-8','cp874' , 'จุดบริการแคชเชียร์ บิ๊กซี ทั่วประเทศ'),0,0,'L');
$pdf->Cell(80,3,iconv( 'UTF-8','cp874' , ''),0,0,'L');
$pdf->SetFont('angsana','',12);
$pdf->SetFont('angsana','',10);
$pdf->Cell(35,-1,iconv( 'UTF-8','cp874' , 'Name :'),0,0,'R');
$pdf->SetFont('angsana','',14);
$pdf->Cell(49,5,iconv( 'UTF-8','cp874' , ''),0,0,'L');

$pdf->Ln(5);
$pdf->Cell(3,3,' ',0,0,'R');
$pdf->Cell(3,3,' ',1,0,'R'); 
//$pdf->Cell(3,3,' ',0,0,'R');
$pdf->Cell(9,3,' ',0,0,'R');
$pdf->SetFont('angsana','',12);
//$pdf->Cell(80,3,iconv( 'UTF-8','cp874' , '** ฟรีค่าธรรมเนียมธนาคารธนชาต ปีการศึกษา 1/2558'),0,0,'L');  // ใส่ธนาคาร
$pdf->Cell(25,3,iconv( 'UTF-8','cp874' , 'บ.เคาน์เตอร์เซอร์วิส'),0,0,'L');  // ใส่ธนาคาร
//$pdf->Cell(25,3,iconv( 'UTF-8','cp874' , ''),0,0,'L');  // ใส่ธนาคาร
$pdf->SetFont('angsana','',8);
// $pdf->Cell(55,3,iconv( 'UTF-8','cp874' , '(*รับชำระไม่เกิน 30,000 บาท/รายการ * เฉพาะจุดบริการ 7-ELEVEN)'),0,0,'L');  // ใส่ธนาคาร
$pdf->Cell(55,3,iconv( 'UTF-8','cp874' , '(*เฉพาะจุดบริการ 7-ELEVEN)'),0,0,'L');  // ใส่ธนาคาร
//$pdf->Cell(55,3,iconv( 'UTF-8','cp874' , ''),0,0,'L');  // ใส่ธนาคาร
$pdf->SetFont('angsana','',12);
$pdf->SetFont('angsana','',10);
$pdf->Cell(35,3,iconv( 'UTF-8','cp874' , 'เลขประจำตัวผู้สมัคร :'),0,0,'R');
$pdf->SetFont('angsana','',18);
$pdf->Cell(58,5,iconv( 'UTF-8','cp874' , $_GET['code_test']),0,0,'L', true);
$pdf->Ln(5);
$pdf->Cell(3,3,' ',0,0,'R');
$pdf->Cell(3,3,' ',0,0,'R'); 
//$pdf->Cell(3,3,' ',0,0,'R');
$pdf->Cell(9,3,' ',0,0,'R');
$pdf->SetFont('angsana','',12);
$pdf->Cell(25,3,iconv( 'UTF-8','cp874' , ''),0,0,'L');  // ใส่ธนาคาร
$pdf->SetFont('angsana','',8);
$pdf->Cell(55,3,iconv( 'UTF-8','cp874' , ''),0,0,'L');  // ใส่ธนาคาร
$pdf->SetFont('angsana','',12);
$pdf->SetFont('angsana','',10);
$pdf->Cell(35,-1,iconv( 'UTF-8','cp874' , 'Student ID. / Ref. No. 1 :'),0,0,'R');
$pdf->SetFont('angsana','',14);
$pdf->Cell(49,5,iconv( 'UTF-8','cp874' , ''),0,0,'L');

 

$pdf->Ln(5);
$pdf->Cell(3,3,' ',0,0,'R');
$pdf->Cell(3,3,' ',0,0,'R');
//$pdf->Cell(3,3,' ',0,0,'R');
$pdf->Cell(9,3,' ',0,0,'R');
$pdf->SetFont('angsana','',12);
$pdf->Cell(80,3,iconv( 'UTF-8','cp874' , ''),0,0,'L');  // ใส่ธนาคาร
//$pdf->Cell(80,3,iconv( 'UTF-8','cp874' , ''),0,0,'L');  // ใส่ธนาคาร
$pdf->SetFont('angsana','',12);
$pdf->SetFont('angsana','',10);
$pdf->Cell(35,3,iconv( 'UTF-8','cp874' , 'อ้างอิงเอกสาร :'),0,0,'R');
$pdf->SetFont('angsana','',18);
$pdf->Cell(58,5,iconv( 'UTF-8','cp874' , $ref2),0,0,'L', true);
$pdf->Ln(5);
$pdf->Cell(3,3,' ',0,0,'R');
$pdf->Cell(3,3,' ',0,0,'R');
//$pdf->Cell(3,3,' ',0,0,'R');
$pdf->Cell(9,3,' ',0,0,'R');
$pdf->SetFont('angsana','',12);
$pdf->Cell(80,3,iconv( 'UTF-8','cp874' , ''),0,0,'L');  // ใส่ธนาคาร
$pdf->SetFont('angsana','',12);
$pdf->SetFont('angsana','',10);
$pdf->Cell(35,-1,iconv( 'UTF-8','cp874' , 'Reference no. / Ref. No.2 :'),0,0,'R');
$pdf->SetFont('angsana','',14);
$pdf->Cell(49,5,iconv( 'UTF-8','cp874' , ''),0,0,'L');	

$pdf->Ln(5);
$pdf->Cell(3,3,' ',0,0,'R');
$pdf->Cell(3,3,' ',0,0,'R');
//$pdf->Cell(3,3,' ',0,0,'R');
$pdf->Cell(9,3,' ',0,0,'R');
$pdf->SetFont('angsana','',12);
$pdf->Cell(80,3,iconv( 'UTF-8','cp874' , ''),0,0,'L');  // ใส่ธนาคาร
$pdf->SetFont('angsana','',14);
$pdf->Cell(35,3,iconv( 'UTF-8','cp874' , 'จำนวนเงิน'),0,0,'R');
$pdf->SetFont('angsana','',18);
if($creck_alarmni_gys>=1 && $_GET['typebill'] == "1"){
    
    if($ROB_ID!=93){
        $pdf->Cell(49,5,iconv( 'UTF-8','cp874' , number_format($rate-$sum, 2)),0,0,'C');
   } else {
    $pdf->Cell(49,5,iconv( 'UTF-8','cp874' , number_format($sum, 2)),0,0,'C');
   }
} else {
    $pdf->Cell(49,5,iconv( 'UTF-8','cp874' , number_format($sum, 2)),0,0,'C');
}

$pdf->SetFont('angsana','',14);
$pdf->Cell(0,5,iconv( 'UTF-8','cp874' , 'บาท    '),0,0,'R');
$pdf->Ln(5);
$pdf->Cell(3,3,' ',0,0,'R');
$pdf->Cell(3,3,' ',0,0,'R');
$pdf->Cell(9,3,' ',0,0,'R');
$pdf->SetFont('angsana','',12);
$pdf->Cell(25,3,iconv( 'UTF-8','cp874' , ''),0,0,'L');  // ใส่ธนาคาร
$pdf->SetFont('angsana','',8);
$pdf->Cell(55,3,iconv( 'UTF-8','cp874' , ''),0,0,'L');  // ใส่ธนาคาร
$pdf->SetFont('angsana','',10);
$pdf->Cell(35,-1,iconv( 'UTF-8','cp874' , 'Amount (Bath)'),0,0,'R');
$pdf->SetFont('angsana','',14);
$pdf->Cell(49,5,iconv( 'UTF-8','cp874' , ''),0,0,'L');	

$pdf->Ln(5);
$pdf->Cell(15,3,' ',0,0,'R');
$pdf->SetFont('angsana','',10);
$pdf->Cell(80,3,iconv( 'UTF-8','cp874' , ''),0,0,'L');
$pdf->SetFont('angsana','',14);
$pdf->Cell(35,3,iconv( 'UTF-8','cp874' , ''),0,0,'R');
$pdf->SetFont('angsana','',18);
$pdf->Cell(49,-2,iconv( 'UTF-8','cp874' , '('.$thaiformat_number).')',0,0,'C');
$pdf->SetFont('angsana','',14);
$pdf->Cell(0,5,iconv( 'UTF-8','cp874' , ''),0,0,'R');


$pdf->Ln(5);
$pdf->SetFont('angsana','',14);

$pdf->Cell(25,3,iconv( 'UTF-8','cp874' , ''),0,0,'L');
$pdf->Cell(70,3,iconv( 'UTF-8','cp874' , 'ช่องทางการชำระะเงินผ่านทาง'),0,0,'L');

$pdf->SetFont('angsana','',10);
$pdf->Cell(5,3,'',0,0,'R');	
$pdf->Cell(15,3,iconv( 'UTF-8','cp874' , ' * หมายเหตุ '),0,0,'L');
$pdf->Cell(0,3,iconv( 'UTF-8','cp874' , 'ค่าลงทะเบียนนี้ยังไม่รวมค่าธรรมเนียมธนาคาร 10.00 บาท หรือค่าธรรมเนียม'),0,0,'L');
$pdf->Ln(5);
$pdf->SetFont('angsana','',14);
$pdf->Cell(25,3,iconv( 'UTF-8','cp874' , ''),0,0,'L');
$pdf->Cell(65,3,iconv( 'UTF-8','cp874' , 'Internet Banking, Mobile Banking'),0,0,'L');
$pdf->Cell(10,3,'',0,0,'R');
$pdf->Cell(15,3,' ',0,0,'R');

$pdf->SetFont('angsana','',10);
// $pdf->Cell(0,3,iconv( 'UTF-8','cp874' , 'เคาน์เตอร์เซอร์วิส 15.00 บาท หรือจุดบริการแคชเชียร์ บิ๊กซี 10 บาท'),0,0,'L');
$pdf->Cell(0,3,iconv( 'UTF-8','cp874' , 'เคาน์เตอร์เซอร์วิส 15.00 บาท'),0,0,'L');
$pdf->Ln(4);
$pdf->Cell(100,-25,'',0,0,'R'); //ใส่กรอบ
$pdf->Cell(89,-25,'',1,0,'R'); //ใส่กรอบ
$pdf->Ln(0);
$pdf->Cell(100,-56,'',0,0,'R'); //ใส่กรอบ
$pdf->Cell(89,-56,'',1,0,'R'); //ใส่กรอบ

$pdf->Ln(1);
$pdf->Cell(0,-58,'',1,0,'R'); //ใส่กรอบ
$pdf->Ln(9);


$pdf->Cell(94,4,iconv( 'UTF-8','cp874' , ''),0,0,'C');
$pdf->SetFont('angsana','',12);
$pdf->Ln(2);
$pdf->SetFont('angsana','',10);

$pdf->Cell(90,3,iconv( 'UTF-8','cp874' , 'พิมพ์รายการเมื่อ : ['.DateThai($strDate).'  '.$strTime.']'),0,0,'L',true);
$pdf->Ln(4);
$pdf->Cell(40,4,iconv( 'UTF-8','cp874' , 'ผู้นำฝาก / โทร. .......................................'),0,0,'L');
$pdf->Cell(40,4,iconv( 'UTF-8','cp874' , 'สำหรับเจ้าหน้าที่ .........................................                       '),0,0,'L');
$pdf->SetFont('angsana','',12);
$pdf->Cell(84,4,iconv( 'UTF-8','cp874' , $barcodeP),0,0,'R');  // ใส่บาร์โค้ด
$pdf->Ln(1);
$pdf->SetFont('angsana','',10);
$pdf->SetFillColor(200,220,255);

$pdf->Cell(5,3,'',0,0,'L');
$pdf->SetFillColor(000,000,000);
$pdf->Code128(103, 261, $barcode,97,10);


$pdf->QR($barcode, 15, 237, 15);

$pdf->Ln(8);



$pdf->Ln(-24);
$pdf->SetFont('angsana','B',14);
$pdf->SetFillColor(200,220,255);
//$pdf->Cell(0,6,iconv( 'UTF-8','cp874' , 'รับชำระระหว่างวันที่ '.$open_term[$std_start].' ถึง '.$open_term[$std_stop].' เท่านั้น    '),0,0,'L');
$pdf->Cell(0,6,iconv( 'UTF-8','cp874' , 'รับชำระระหว่างวันที่ ' . $robOpenD . '/' . $robOpenM . '/' . $robOpenY . ' ถึง ' . $robCloseD . '/' . $robCloseM . '/' . $robCloseY ),0,0,'L');
$pdf->Ln(4);
$pdf->Cell(0,6,iconv( 'UTF-8','cp874' , 'หากพ้นกำหนดกรุณาติดต่อมหาวิทยาลัยราชภัฏกำแพงเพชร'),0,0,'L');
/////////////////////////// /////////////////
// echo $ROB_ID;

            // $pdf->AddPage();

            // $pdf->Image('public/images/kpru0001.jpg', 0, 0, 210, 0, 'JPG');
            
            // $pdf->AddPage();
            //     $pdf->Image('public/images/kpru2563_show01.jpg', 0, 0, 210, 0, 'JPG');

            //     $pdf->AddPage();
            //     $pdf->Image('public/images/kpru2563_show02.jpg', 0, 0, 210, 0, 'JPG');

            //     $pdf->AddPage();
            //     $pdf->Image('public/images/kpru2563_show03.jpg', 0, 0, 210, 0, 'JPG');
        
            if($QUOTA_ID==4){ 
                if($ROB_ID==117){
                    $pdf->AddPage();
                    $pdf->Image('public/images/2566/kpru-nurse2-show1.jpg', 0, 0, 210, 0, 'JPG');

                    $pdf->AddPage();
                    $pdf->Image('public/images/2566/kpru-nurse2-show2.jpg', 0, 0, 210, 0, 'JPG');

                    $pdf->AddPage();
                    $pdf->Image('public/images/2566/kpru-nurse2-show3.jpg', 0, 0, 210, 0, 'JPG');
                } else {
                    $pdf->AddPage();
                    $pdf->Image('public/images/2566/kpru-rob2-show1.jpg', 0, 0, 210, 0, 'JPG');

                    $pdf->AddPage();
                    $pdf->Image('public/images/2566/kpru-rob2-show2.jpg', 0, 0, 210, 0, 'JPG');

                    $pdf->AddPage();
                    $pdf->Image('public/images/2566/kpru-rob2-show3.jpg', 0, 0, 210, 0, 'JPG');

                    $pdf->AddPage();
                    $pdf->Image('public/images/2566/kpru-rob2-show4.jpg', 0, 0, 210, 0, 'JPG');
                }
                
            }

            


$regbill_FILE = $_GET["code_test"] . ".pdf";
$regbill = "public/Files_All/" . $regbill_FILE;
$pdf->Output($regbill, "F");


echo "<script language=javascript>";
echo "window.location='" . $regbill . "';";
echo "</script>";

?>