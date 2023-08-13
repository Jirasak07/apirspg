<?php
header("Access-Control-Allow-Origin: *");
require('./fpdf.php');
require_once("fpdi/fpdi.php");
$pdf = new FPDF();
$pdf->SetMargins(20, 0, 20); 
$pdf->AddPage();
$pdf->AddFont('THSarabunNew','','THSarabunNew.php');
$pdf->AddFont('THSarabunNew','B','THSarabunNew_b.php');
$utf = 'UTF-8';
$cp = 'cp874';
$pdf->SetFont('THSarabunNew','B',16);
$pdf->Cell(0, 15, '', 0, 1);
$pdf->Cell(0,10,iconv('UTF-8', 'cp874', 'ใบงานที่ 5'),0,1,'C');
$pdf->Cell(0, 5, '', 0, 1);
$pdf->Cell(0, 0, iconv('UTF-8', 'cp874', 'เรื่อง การเก็บข้อมูลการใช้ประโยชน์ของพืชในท้องถิ่น'), 0, 1, 'C');
$pdf->Cell(0, 5, '', 0, 1);
$pdf->Ln(); // เพิ่มการเว้นวรรค
$pdf->Cell(0,2,iconv('UTF-8','cp874','20. ข้อมูลพืช'),0,1,'L');
$pdf->SetFont('THSarabunNew','',16);
$pdf->SetX(32);
$pdf->Cell(0,10,iconv('UTF-8','cp874','พืชที่มีความสำคัญ หรือมีลักษณะพิเศษ เช่น พืชที่เป็นไม้ผล ผักพื้นเมือง พืชสมุนไพร พืชใช้เนื้อไม้'),0,1,'L');
$pdf->Cell(0,5,iconv($utf,$cp,'พืชเศรษฐกิจ ฯลฯ'),0,1,'L');
$pdf->SetFont('THSarabunNew','B',16);
$pdf->Cell(0,5,iconv($utf,$cp,'ชื่อพืช.....................................................................รหัสพรรณไม้..................................................'),0,1,'C');
$pdf->Cell(0,7,iconv($utf,$cp,'ลักษณะวิสัย...................................................................................................................................'),0,1,'C');
$pdf->Cell(0,7,iconv($utf,$cp,'ลักษณะเด่นของพืช........................................................................................................................'),0,1,'C');
$pdf->Cell(0,7,iconv($utf,$cp,'......................................................................................................................................................'),0,1,'C');
$pdf->Cell(0,7,iconv($utf,$cp,'......................................................................................................................................................'),0,1,'C');
$pdf->Cell(0,7,iconv($utf,$cp,'......................................................................................................................................................'),0,1,'C');
$pdf->Cell(0,7,iconv($utf,$cp,'บริเวณที่พบ..................................................................................................................................'),0,1,'C');
$pdf->Cell(0,7,iconv($utf,$cp,'แสดงพิกัดตำแหน่งพรรณไม้ (GIS) X: .............................................Y: ........................................'),0,1,'C');
$pdf->Cell(0,7,iconv($utf,$cp,'ตำบล/แขวง......................................อำเภอ/เขต...............................จังหวัด................................'),0,1,'C');
$pdf->Cell(0,7,iconv($utf,$cp,'อายุประมาณ..........................ปี เส้นรอบวงลำต้น.....................เมตร ความสูง.......................เมตร'),0,1,'C');
$pdf->Cell(0,7,iconv($utf,$cp,'สถานภาพ...................................................................................จำนวนที่พบ...........................ต้น'),0,1,'C');
$pdf->Cell(0,2,'',0,1,'C');
$pdf->Cell(0,5,iconv($utf,$cp,'การใช้ประโยชน์ในท้องถิ่น (ระบุส่วนที่ใช้และวิธีการใช้)'),0,1,'L');
$pdf->SetX(30);
$pdf->Cell(0,6,iconv($utf,$cp,'อาหาร'),0,1,'L');
$pdf->Cell(0,7,iconv($utf,$cp,'......................................................................................................................................................'),0,1,'C');
$pdf->Cell(0,7,iconv($utf,$cp,'......................................................................................................................................................'),0,1,'C');
$pdf->Cell(0,7,iconv($utf,$cp,'......................................................................................................................................................'),0,1,'C');
$pdf->SetX(30);
$pdf->Cell(0,6,iconv($utf,$cp,'ยารักษาโรค ใช้กับคน'),0,1,'L');
$pdf->Cell(0,7,iconv($utf,$cp,'......................................................................................................................................................'),0,1,'C');
$pdf->Cell(0,7,iconv($utf,$cp,'......................................................................................................................................................'),0,1,'C');
$pdf->Cell(0,7,iconv($utf,$cp,'......................................................................................................................................................'),0,1,'C');
$pdf->SetX(30);
$pdf->Cell(0,6,iconv($utf,$cp,'ยารักษาโรค ใช้กับสัตว'),0,1,'L');
$pdf->Cell(0,7,iconv($utf,$cp,'......................................................................................................................................................'),0,1,'C');
$pdf->Cell(0,7,iconv($utf,$cp,'......................................................................................................................................................'),0,1,'C');
$pdf->Cell(0,7,iconv($utf,$cp,'......................................................................................................................................................'),0,1,'C');
$pdf->SetX(30);
$pdf->Cell(0,6,iconv($utf,$cp,'เครื่องเรือน เครื่องใช้อื่นๆ'),0,1,'L');
$pdf->Cell(0,7,iconv($utf,$cp,'......................................................................................................................................................'),0,1,'C');
$pdf->Cell(0,7,iconv($utf,$cp,'......................................................................................................................................................'),0,1,'C');
$pdf->Cell(0,7,iconv($utf,$cp,'......................................................................................................................................................'),0,1,'C');
$pdf->SetX(30);
$pdf->Cell(0,6,iconv($utf,$cp,'ยาฆ่าแมลง ยาปราบศัตรูพืช'),0,1,'L');
$pdf->Cell(0,7,iconv($utf,$cp,'......................................................................................................................................................'),0,1,'C');
$pdf->Cell(0,7,iconv($utf,$cp,'......................................................................................................................................................'),0,1,'C');
$pdf->Cell(0,7,iconv($utf,$cp,'......................................................................................................................................................'),0,1,'C');
$pdf->AddPage();
$pdf->Cell(0, 15, '', 0, 1);
$pdf->Cell(0,0,'',0,1);
$pdf->SetX(30);
$pdf->Cell(0,6,iconv($utf,$cp,'ความเกี่ยวข้องกับประเพณี วัฒนธรรม'),0,1,'L');
$pdf->Cell(0,7,iconv($utf,$cp,'......................................................................................................................................................'),0,1,'C');
$pdf->Cell(0,7,iconv($utf,$cp,'......................................................................................................................................................'),0,1,'C');
$pdf->Cell(0,7,iconv($utf,$cp,'......................................................................................................................................................'),0,1,'C');
$pdf->SetX(30);
$pdf->Cell(0,6,iconv($utf,$cp,'ความเกี่ยวข้องกับความเชื่อทางศาสนา'),0,1,'L');
$pdf->Cell(0,7,iconv($utf,$cp,'......................................................................................................................................................'),0,1,'C');
$pdf->Cell(0,7,iconv($utf,$cp,'......................................................................................................................................................'),0,1,'C');
$pdf->Cell(0,7,iconv($utf,$cp,'......................................................................................................................................................'),0,1,'C');

$pdf->SetX(30);
$pdf->Cell(0,6,iconv($utf,$cp,'อื่นๆ (เช่นการเป็นพิษ อันตราย)'),0,1,'L');
$pdf->Cell(0,7,iconv($utf,$cp,'......................................................................................................................................................'),0,1,'C');
$pdf->Cell(0,7,iconv($utf,$cp,'......................................................................................................................................................'),0,1,'C');
$pdf->Cell(0,7,iconv($utf,$cp,'......................................................................................................................................................'),0,1,'C');
$pdf->Ln();
$pdf->SetX(20);
$pdf->Cell(0,6,iconv($utf,$cp,'ผู้ให้ข้อมูล ชื่อ-สกุล....................................................................................................อายุ.......................ปี'),0,1,'L');
$pdf->SetX(20);
$pdf->Cell(0,7,iconv($utf,$cp,'ที่อยู่ .........................................................................................................................................................'),0,1,'L');
$pdf->SetX(20);
$pdf->Cell(0,7,iconv($utf,$cp,'...................................................................................................................................................................'),0,1,'L');
$pdf->SetX(60);
$pdf->Cell(10,7,iconv($utf,$cp,'หมายเหตุ :'),0,0,'L');
$pdf->SetFont('THSarabunNew','',16);
$pdf->SetX(80);
$pdf->Cell(10,7,iconv($utf,$cp,' กรณีมีข้อมูลพืชที่สำคัญมากกว่า 1 ชนิด ขอให้เพิ่มเติมแบบฟอร์ม'),0,1,'L');
$pdf->Ln();
$pdf->Cell(0,130,'',1,0,'C');
$pdf->SetFont('THSarabunNew','B',16);
$pdf->SetX(30);
$pdf->Cell(0,10,iconv($utf,$cp,'ผังแสดงตำแหน่งพรรณไม้'),0,1,'C');
$apiData = file_get_contents('https://rspg-kpppao.com/backend/Plant/SelectProvince');
$dataArray = json_decode($apiData, true);
foreach ($dataArray as $data) {
    $pdf->Cell(40, 10, 'Name: ' . $data['val']);
    $pdf->Ln();
    $pdf->Cell(40, 10, 'Email: ' . $data['label']);
    $pdf->Ln();
    // Add more data as needed
    $pdf->Ln();
}
$pdf->Output();
?>


