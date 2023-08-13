<?php
require_once("tcpdf.php");
require_once("fpdi/fpdi.php");

header("Access-Control-Allow-Origin: *");

class MergePdf{
	const DESTINATION__INLINE = "I";
	const DESTINATION__DOWNLOAD = "D";
	const DESTINATION__DISK = "F";
	const DESTINATION__DISK_INLINE = "FI";
	const DESTINATION__DISK_DOWNLOAD = "FD";
	const DESTINATION__BASE64_RFC2045 = "E";
	
	const DEFAULT_DESTINATION = self::DESTINATION__INLINE;
	const DEFAULT_MERGED_FILE_NAME = __DIR__ . "/mergedfiles.pdf";
	
	public static function merge($files, $destination = null, $outputPath = null){
		
		$token = $_GET['token'];
		$term = $_GET['term'];
		$id_no = $_GET['id_no'];

		$url = "https://mua.kpru.ac.th/apipostpone/Report/ReportTeacher?TERM=".$term."&ID_NO=".$id_no;
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

		if(empty($destination)){
			$destination = self::DEFAULT_DESTINATION;
		}
		
		if(empty($outputPath)){
			$outputPath = self::DEFAULT_MERGED_FILE_NAME;
		}
		
		$pdf = new FPDI();
		$pdf->setPrintHeader(false);
		$pdf->setPrintFooter(false);
		$pdf->SetFont('thsarabun', '', 16);
        if ($respone[0]['status_Vicerector'] == '1') {
            $htmlcontent='
				<table>
					<tr>
						<th align="center" width="50%"><b>ความเห็นอาจารย์ที่ปรึกษา</b><br />'.$respone[0]['reason_Teacher'].'</th>
						<th align="center" width="50%"><b>ความเห็นนายทะเบียน</b><br />'.$respone[0]['reason_Tabian'].'</th>
					</tr>
					<tr>
						<th align="center" width="50%"><img src="'.$respone[0]['setDataImages_Teacher'].'" height="30"></th>
						<th align="center" width="50%"><img src="'.$respone[0]['setDataImages_Tabian'].'" height="30"></th>
					</tr>
					<tr>
						<th align="center" width="50%">( '.$respone[0]['teacher_prenm'].$respone[0]['teacher_poname'].' )</th>
						<th align="center" width="50%">( '.$respone[0]['tabian_prenm'].$respone[0]['tabian_poname'].' )</th>
					</tr>
					<tr>
						<th align="center" width="50%">'.$respone[0]['setDateSignature_Teacher'].'</th>
						<th align="center" width="50%">'.$respone[0]['setDateSignature_Tabian'].'</th>
					</tr>
					<tr>
						<th align="center" width="50%">'.$respone[0]['setCodeSignature_Teacher'].'</th>
						<th align="center" width="50%">'.$respone[0]['setCodeSignature_Tabian'].'</th>
					</tr>
					<br>
					<tr>
						<th align="center" width="50%"><b>ความเห็นผู้อำนวยการสำนักส่งเสริมวิชาการฯ</b><br />'.$respone[0]['reason_Director_Tabian'].'</th>
						<th align="center" width="50%"><b>ความเห็นรองอธิการบดีฝ่ายวิชาการ</b><br />'.$respone[0]['reason_Vicerector'].'</th>
					</tr>
					<tr>
						<th align="center" width="50%"><img src="'.$respone[0]['setDataImages_Director_Tabian'].'" height="30"></th>
						<th align="center" width="50%"><img src="'.$respone[0]['setDataImages_Vicerector'].'" height="30"></th>
					</tr>
					<tr>
						<th align="center" width="50%">( '.$respone[0]['director_tabian_prenm'].$respone[0]['director_tabian_poname'].' )</th>
						<th align="center" width="50%">( '.$respone[0]['vicerector_prenm'].$respone[0]['vicerector_poname'].' )</th>
					</tr>
					<tr>
						<th align="center" width="50%">'.$respone[0]['setDateSignature_Director_Tabian'].'</th>
						<th align="center" width="50%">'.$respone[0]['setDateSignature_Vicerector'].'</th>
					</tr>
					<tr>
						<th align="center" width="50%">'.$respone[0]['setCodeSignature_Director_Tabian'].'</th>
						<th align="center" width="50%">'.$respone[0]['setCodeSignature_Vicerector'].'</th>
					</tr>
				</table>
			';
        }else if ($respone[0]['status_Director_Tabian'] == '1') {
            $htmlcontent='
			<table>
				<tr>
					<th align="center" width="50%"><b>ความเห็นอาจารย์ที่ปรึกษา</b><br />'.$respone[0]['reason_Teacher'].'</th>
					<th align="center" width="50%"><b>ความเห็นนายทะเบียน</b><br />'.$respone[0]['reason_Tabian'].'</th>
				</tr>
				<tr>
					<th align="center" width="50%"><img src="'.$respone[0]['setDataImages_Teacher'].'" height="30"></th>
					<th align="center" width="50%"><img src="'.$respone[0]['setDataImages_Tabian'].'" height="30"></th>
				</tr>
				<tr>
					<th align="center" width="50%">( '.$respone[0]['teacher_prenm'].$respone[0]['teacher_poname'].' )</th>
					<th align="center" width="50%">( '.$respone[0]['tabian_prenm'].$respone[0]['tabian_poname'].' )</th>
				</tr>
				<tr>
					<th align="center" width="50%">'.$respone[0]['setDateSignature_Teacher'].'</th>
					<th align="center" width="50%">'.$respone[0]['setDateSignature_Tabian'].'</th>
				</tr>
				<tr>
					<th align="center" width="50%">'.$respone[0]['setCodeSignature_Teacher'].'</th>
					<th align="center" width="50%">'.$respone[0]['setCodeSignature_Tabian'].'</th>
				</tr>
				<br>
				<tr>
					<th align="center" width="50%"><b>ความเห็นผู้อำนวยการสำนักส่งเสริมวิชาการฯ</b><br />'.$respone[0]['reason_Director_Tabian'].'</th>
				</tr>
				<tr>
					<th align="center" width="50%"><img src="'.$respone[0]['setDataImages_Director_Tabian'].'" height="30"></th>
				</tr>
				<tr>
					<th align="center" width="50%">( '.$respone[0]['director_tabian_prenm'].$respone[0]['director_tabian_poname'].' )</th>
				</tr>
				<tr>
					<th align="center" width="50%">'.$respone[0]['setDateSignature_Director_Tabian'].'</th>
				</tr>
				<tr>
					<th align="center" width="50%">'.$respone[0]['setCodeSignature_Director_Tabian'].'</th>
				</tr>
			</table>
			';
        }else if ($respone[0]['status_Tabian'] == '1') {
            $htmlcontent='
				<table>
					<tr>
						<th align="center" width="50%"><b>ความเห็นอาจารย์ที่ปรึกษา</b><br />'.$respone[0]['reason_Teacher'].'</th>
						<th align="center" width="50%"><b>ความเห็นนายทะเบียน</b><br />'.$respone[0]['reason_Tabian'].'</th>
					</tr>
					<tr>
						<th align="center" width="50%"><img src="'.$respone[0]['setDataImages_Teacher'].'" height="30"></th>
						<th align="center" width="50%"><img src="'.$respone[0]['setDataImages_Tabian'].'" height="30"></th>
					</tr>
					<tr>
						<th align="center" width="50%">( '.$respone[0]['teacher_prenm'].$respone[0]['teacher_poname'].' )</th>
						<th align="center" width="50%">( '.$respone[0]['tabian_prenm'].$respone[0]['tabian_poname'].' )</th>
					</tr>
					<tr>
						<th align="center" width="50%">'.$respone[0]['setDateSignature_Teacher'].'</th>
						<th align="center" width="50%">'.$respone[0]['setDateSignature_Tabian'].'</th>
					</tr>
					<tr>
						<th align="center" width="50%">'.$respone[0]['setCodeSignature_Teacher'].'</th>
						<th align="center" width="50%">'.$respone[0]['setCodeSignature_Tabian'].'</th>
					</tr>
				</table>
			';
        }else if ($respone[0]['status_Teacher'] == '1') {
            $htmlcontent='
				<table>
					<tr>
						<th align="center" width="50%"><b>ความเห็นอาจารย์ที่ปรึกษา</b><br />'.$respone[0]['reason_Teacher'].'</th>
					</tr>
					<tr>
						<th align="center" width="50%"><img src="'.$respone[0]['setDataImages_Teacher'].'" height="30"></th>
					</tr>
					<tr>
						<th align="center" width="50%">( '.$respone[0]['teacher_prenm'].$respone[0]['teacher_poname'].' )</th>
					</tr>
					<tr>
						<th align="center" width="50%">'.$respone[0]['setDateSignature_Teacher'].'</th>
					</tr>
					<tr>
						<th align="center" width="50%">'.$respone[0]['setCodeSignature_Teacher'].'</th>
					</tr>
				</table>
			';
        }else{
			$htmlcontent='';
		}
		echo $files;
		self::join($pdf, $files);
		if($htmlcontent!=''){
			$pdf->AddPage();
			$pdf->writeHTML($htmlcontent, false, 0, true, 0);
		}
		$timestamp = time();
		$timestamp = 'POSTPONE'.$timestamp.'.pdf';
		$pdf->Output($timestamp, 'D');
	}
	
	private static function join($pdf, $fileList){
		if(empty($fileList) || !is_array($fileList)){
			die("invalid file list");
		}
		
		foreach($fileList as $file){
			self::addFile($pdf, $file);
		}
	}
	
	private static function addFile($pdf, $file){
		$numPages = $pdf->setSourceFile($file);
		
		if(empty($numPages) || $numPages < 1){
			return;
		}
		
		for($x = 1; $x <= $numPages; $x++){
			$pdf->AddPage();
			$pdf->useTemplate($pdf->importPage($x), null, null, 0, 0, true);
			$pdf->endPage();
		}
	}
}

$term = $_GET['term'];
$id_no = $_GET['id_no'];
$explodeterm = explode("/", $term);

MergePdf::merge(
	Array(
		"../public/uploads/".$id_no."/".$explodeterm[1]."_".$explodeterm[0]."/".$id_no."_registration.pdf",
		"../public/uploads/".$id_no."/".$explodeterm[1]."_".$explodeterm[0]."/".$id_no."_certifiledparent.pdf",
		"../public/uploads/".$id_no."/".$explodeterm[1]."_".$explodeterm[0]."/".$id_no."_certifiledwitness1.pdf",
		"../public/uploads/".$id_no."/".$explodeterm[1]."_".$explodeterm[0]."/".$id_no."_certifiledwitness2.pdf",
	),
	
	// MergePdf::DESTINATION__DISK_INLINE
);
