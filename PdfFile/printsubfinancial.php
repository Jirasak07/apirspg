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

		$url = "https://mua.kpru.ac.th/apipostpone/Report/ReportSignature?TERM=".$term."&ID_NO=".$id_no;
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
		$pdf->SetFont('thsarabun', 'B', 16);
		$homepicinside1 = $respone[0]['homepicinside1'];
		if(substr($homepicinside1, 0, 10) == 'data:image'){
			$homepicinside1 = "@' ".preg_replace('#^data:image/[^;]+;base64,#', '', $homepicinside1)."'";
		}else{
			$homepicinside1 = null;
		}
		$homepicoutside1 = $respone[0]['homepicoutside1'];
		if(substr($homepicoutside1, 0, 10) == 'data:image'){
			$homepicoutside1 = "@' ".preg_replace('#^data:image/[^;]+;base64,#', '', $homepicoutside1)."'";
		}else{
			$homepicoutside1 = null;
		}

		$htmlcontent='
			<table>
				<tr>
				<br />
					<th align="center" width="100%">ภาพถ่ายบ้านพักอาศัยหรือที่พักอาศัย พร้อมตัวบ้านทั้งภายในบริเวณบ้านและ<br />ภายนอกบริเวณบ้านพร้อมสภาพแวดล้อม</th>
				</tr>
				<tr>
					<th align="left" width="100%">
						<br />
						<br />
						ภายในบริเวณบ้าน
					</th>
				</tr>
				<tr>
					<th align="center" width="100%">
						<img src="'.$homepicinside1.'" width="350">
					</th>
				</tr>
				<tr>
					<th align="left" width="100%">
						ภายนอกบริเวณบ้าน<br />
					</th>
				</tr>
				<tr>
					<th align="center" width="100%">
						<img src="'.$homepicoutside1.'" width="350">
					</th>
				</tr>
			</table>
		';
		self::join($pdf, $files);
		
		$pdf->AddPage();
		$pdf->writeHTML($htmlcontent, false, 0, true, 0);

		$timestamp = time();
		$timestamp = 'Subfinancial'.$timestamp.'.pdf';
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
		"../public/uploads/".$id_no."/".$explodeterm[1]."_".$explodeterm[0]."/".$id_no."_subfinancial.pdf",
		"../public/uploads/".$id_no."/".$explodeterm[1]."_".$explodeterm[0]."/".$id_no."_certifiledincome.pdf",
		"../public/uploads/".$id_no."/".$explodeterm[1]."_".$explodeterm[0]."/".$id_no."_rubrongteacher.pdf",
	),
	
	// MergePdf::DESTINATION__DISK_INLINE
);
