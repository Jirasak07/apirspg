<?php

header("Access-Control-Allow-Origin: *");

require_once('tcpdf.php');

$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8');

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
$pdf->Cell(170, 0, 'ที่.............................วันที่  ', 0, 1, 'L', 0, '', 0);
$pdf->Cell(170, 0, 'เรื่อง    ขอขยายเวลา ชำระค่าธรรมเนียมการศึกษา', 0, 1, 'L', 0, '', 0);
$pdf->Cell(170, 0, '--------------------------------------------------------------------------------------------------------------------------------------------------------------', 0, 1, 'L', 0, '', 0);
$pdf->Cell(170, 0, 'เรียน    อธิการบดีมหาวิทยาลัยราชภัฎกำแพงเพชร', 0, 1, 'L', 0, '', 0);
$pdf->Cell(170, 0, 'สิ่งที่ส่งมาด้วย    บัตรลงทะเบียน ภาคเรียน  ', 0, 1, 'L', 0, '', 0);
$pdf->SetFillColor(255, 255, 255);
$pdf->Ln(2);
$pdf->MultiCell(170, 0, '              ด้วยข้าพเจ้า    เป็นนักศึกษามหาวิทยาลัยราชภัฎกำแพงเพชร 
รหัสนักศึกษา    บาท โดยจะนำเงินมาชำระค่าลงทะเบียนภายในวันที่  ', 0, 'L', 1, 0, '', '', true);
$pdf->Ln(45);
$pdf->Cell(170, 0, '                    จึงเรียนมาเพื่อโปรดพิจารณา', 0, 1, 'L', 0, '', 0);
$pdf->Ln(10);

$pdf->lastPage();
$pdf->Output('postpone.pdf', 'I');