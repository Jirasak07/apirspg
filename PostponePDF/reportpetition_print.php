<?php
  date_default_timezone_set('Asia/Bangkok');

  $idrpt = $_GET['idrpt'];
  $ID_NO = $_GET['ID_NO'];
  
  $scope=[];
  $scope['IDRPT'] = $idrpt;
  $scope['ID_NO'] = $ID_NO;
  
  $url = "https://mua.kpru.ac.th/frontend_tabian/petition/showstudent/".$ID_NO;
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
  $scope['OLDID'] = $respone[0]['OLDID'];
  $scope['GROUP_NO'] = $respone[0]['GROUP_NO'];
  $scope['PNAME'] = $respone[0]['PNAME'];
  $scope['NAME'] = $respone[0]['NAME'];
  $scope['ImgUrl'] = $respone[0]['ImgUrl'];
  $scope['t_mjname'] = $respone[0]['t_mjname'];
  $scope['BDATE'] = $respone[0]['BDATE'];
  $scope['Type_name'] = $respone[0]['Type_name'];
  $scope['level_name'] = $respone[0]['level_name'];
  
  $url = "https://mua.kpru.ac.th/frontend_tabian/petition/ShowReportPetitionID/".$ID_NO."/".$idrpt;
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
  $res = curl_exec($curl);
  curl_close($curl);
  $res = json_decode($res, true);
  $scope['DATE_INSERT'] = $res[0]['DATE_INSERT'];
  $scope['TIME_INERT'] = $res[0]['TIME_INERT'];
  $scope['NUM_HOME'] = $res[0]['NUM_HOME'];
  $scope['NUM_MOO'] = $res[0]['NUM_MOO'];
  $scope['SOI'] = $res[0]['SOI'];
  $scope['ZIPCODE'] = $res[0]['ZIPCODE'];
  $scope['PHONE'] = $res[0]['PHONE'];
  $scope['STREET'] = $res[0]['STREET'];
  $scope['TUMBOL'] = $res[0]['TUMBOL'];
  $scope['AMPHUR'] = $res[0]['AMPHUR'];
  $scope['PRIVANCY'] = $res[0]['PRIVANCY'];
  $scope['SETCODESIGNATURE_INSERT'] = $res[0]['SETCODESIGNATURE_INSERT'];
  $scope['NUM_RUBRONGTH'] = $res[0]['NUM_RUBRONGTH'];
  $scope['NUM_RUBRONGEN'] = $res[0]['NUM_RUBRONGEN'];
  $scope['NAME_ENG'] = $res[0]['NAME_ENG'];
  $scope['DETAIL'] = $res[0]['DETAIL'];
  $scope['STATUS'] = $res[0]['STATUS'];
  $scope['TYPEPITITION_NAME'] = $res[0]['TYPEPITITION_NAME'];
  $scope['TYPEPITITION_MONEY'] = $res[0]['TYPEPITITION_MONEY'];
  $scope['NUM_RUBRONG'] = $res[0]['NUM_RUBRONGTH'] + $res[0]['NUM_RUBRONGEN'];
  $scope['MONEY_RUBRONG'] = ($res[0]['NUM_RUBRONGTH'] + $res[0]['NUM_RUBRONGEN'])*$res[0]['TYPEPITITION_MONEY'];
  $scope['CITIZENT_TABIAN'] = $res[0]['CITIZENT_TABIAN'];
  $scope['REASON_TABIAN'] = $res[0]['REASON_TABIAN'];
  $scope['SETDATESIGNATURE_TABIAN'] = $res[0]['SETDATESIGNATURE_TABIAN'];
  $scope['SETCODESIGNATURE_TABIAN'] = $res[0]['SETCODESIGNATURE_TABIAN'];
  $scope['STATUS_NOTDETAIL'] = $res[0]['STATUS_NOTDETAIL'];
  $scope['BILL'] = $res[0]['BILL'];
  $scope['BILL_NO'] = $res[0]['BILL_NO'];
  $scope['BILL_DATE'] = $res[0]['BILL_DATE'];
  $scope['BILL_MONEY'] = $res[0]['BILL_MONEY'];
  $scope['CITIZENT_HEADTABIAN'] = $res[0]['CITIZENT_HEADTABIAN'];
  $scope['REASON_HEADTABIAN'] = $res[0]['REASON_HEADTABIAN'];
  $scope['SETDATESIGNATURE_HEADTABIAN'] = $res[0]['SETDATESIGNATURE_HEADTABIAN'];
  $scope['SETCODESIGNATURE_HEADTABIAN'] = $res[0]['SETCODESIGNATURE_HEADTABIAN'];
  $scope['PRINT_STATUS'] = $res[0]['PRINT_STATUS'];
  $scope['SETDATESIGNATURE_PRINTSTATUS'] = $res[0]['SETDATESIGNATURE_PRINTSTATUS'];
  $scope['SETCODESIGNATURE_PRINTSTATUS'] = $res[0]['SETCODESIGNATURE_PRINTSTATUS'];
  
  $url = "https://mua.kpru.ac.th/frontend_tabian/graduate/ShowProvancySel/".$scope['PRIVANCY'];
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
  $PROVINCE = curl_exec($curl);
  curl_close($curl);
  $PROVINCE = json_decode($PROVINCE, true);
  $scope['PROVINCE_NAME'] = $PROVINCE['DetailListLoad'][0]['PROVINCE_NAME_TH'];
  
  $url = "https://mua.kpru.ac.th/frontend_tabian/graduate/ShowDriticSel/".$scope['AMPHUR'];
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
  $Dritic = curl_exec($curl);
  curl_close($curl);
  $Dritic = json_decode($Dritic, true);
  $scope['DISTRICT_NAME'] = $Dritic['DetailListLoad'][0]['DISTRICT_NAME_TH'];
  
  $url = "https://mua.kpru.ac.th/frontend_tabian/graduate/ShowSubDriticSel/".$scope['TUMBOL'];
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
  $SUB_DISTRICT = curl_exec($curl);
  curl_close($curl);
  $SUB_DISTRICT = json_decode($SUB_DISTRICT, true);
  $scope['SUBDISTRICT_NAME'] = $SUB_DISTRICT['DetailListLoad'][0]['SUB_DISTRICT_NAME_TH'];
  
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

  function toMonth($month){
    $longmonth = array(null,'มกราคม','กุมภาพันธ์','มีนาคม','เมษายน','พฤษภาคม','มิถุนายน','กรกฎาคม','สิงหาคม','กันยายน','ตุลาคม','พฤศจิกายน','ธันวาคม');
    $shortmonth = array(null,'ม.ค.','ก.พ.','มี.ค.','เม.ย.','พ.ค.','มิ.ย.','ก.ค.','ส.ค.','ก.ย.','ต.ค.','พ.ย.','ธ.ค.');
    $str = str_replace($shortmonth, $longmonth, $month);
    return $str;
  }

	function GenarateSignatureTH(){
		function randomStringTH($length = 5) {
			$str = "";
			$characters = array_merge(range('A','Z'), range('a','z'));
			$max = count($characters) - 1;
			for ($i = 0; $i < $length; $i++) {
				$rand = mt_rand(0, $max);
				$str .= $characters[$rand];
			}
			return $str;
		}

		$strDate = date("Y-m-d"); 
		$strTime = date("H:i:s");		
		$strYear = date("Y",strtotime($strDate))+543;
		$strMonth= date("n",strtotime($strDate));
		$strDay= date("j",strtotime($strDate));
		$strMonthCut = Array("","ม.ค.","ก.พ.","มี.ค.","เม.ย.","พ.ค.","มิ.ย.","ก.ค.","ส.ค.","ก.ย.","ต.ค.","พ.ย.","ธ.ค.");
		$strMonthThai=$strMonthCut[$strMonth];
		$setDateSignature = "$strDay $strMonthThai $strYear";

		$setCodeSignature = sprintf('%05s-%05s-%05s-%05s',randomStringTH(),randomStringTH(),randomStringTH(),randomStringTH(),randomStringTH());
		$a = array(
			'setDateSignature' => $setDateSignature.", Non-PKI Server Sign,",
			'setCodeSignature' => "Signature Code : ".$setCodeSignature
		);
		return $a;
  }
  
  if((substr($scope['BDATE'], 6, 2))>=80){
    $scope['DayDateBdate'] = (substr($scope['BDATE'], 0, 2)+0). " " .$monthTH[(substr($scope['BDATE'], 3, 2))+0]. " 24" . (substr($scope['BDATE'], 6, 2));
    $scope['DayDateBdate'] = toThaiNumber($scope['DayDateBdate']);
  }else{
    $scope['DayDateBdate'] = (substr($scope['BDATE'], 0, 2)+0). " " .$monthTH[(substr($scope['BDATE'], 3, 2))+0]. " 25" . (substr($scope['BDATE'], 6, 2));
    $scope['DayDateBdate'] = toThaiNumber($scope['DayDateBdate']);
  }

  $currentdatetime = $scope['DATE_INSERT'];
  $currentdatetime = explode("/",$currentdatetime);
  $currentdatetime = ($currentdatetime[2]-543)."-".$currentdatetime[1]."-".$currentdatetime[0];
  $currentdatetime = strtotime($currentdatetime); 
  $currentdatetime = thai_date_and_time($currentdatetime);
  $currentdatetime = toThaiNumber($currentdatetime);
  $scope['currentdatetime'] = $currentdatetime;

  $scope['ID_NO'] = toThaiNumber($scope['ID_NO']);
  $scope['OLDID'] = toThaiNumber($scope['OLDID']);
  $scope['GROUP_NO'] = toThaiNumber($scope['GROUP_NO']);
  $scope['level_name'] = toThaiNumber($scope['level_name']);
  $scope['NUM_HOME'] = toThaiNumber($scope['NUM_HOME']);
  $scope['NUM_MOO'] = toThaiNumber($scope['NUM_MOO']);
  $scope['SOI'] = toThaiNumber($scope['SOI']);
  $scope['ZIPCODE'] = toThaiNumber($scope['ZIPCODE']);
  $scope['PHONE'] = toThaiNumber($scope['PHONE']);
  $scope['STREET'] = toThaiNumber($scope['STREET']);
  $scope['NUM_RUBRONGTH'] = toThaiNumber($scope['NUM_RUBRONGTH']);
  $scope['NUM_RUBRONGEN'] = toThaiNumber($scope['NUM_RUBRONGEN']);
  $scope['TYPEPITITION_MONEY'] = toThaiNumber($scope['TYPEPITITION_MONEY']);
  $scope['NUM_RUBRONG'] = toThaiNumber($scope['NUM_RUBRONG']);
  $scope['MONEY_RUBRONG'] = toThaiNumber($scope['MONEY_RUBRONG']);
  if($scope['SETDATESIGNATURE_TABIAN']){
    $scope['SETDATESIGNATURE_TABIAN'] = toThaiNumber($scope['SETDATESIGNATURE_TABIAN']);
    $explodesetdate = explode(",", $scope['SETDATESIGNATURE_TABIAN']);
    $explodesetdate = explode(" ", $explodesetdate[0]);
    $scope['SETDATESIGNATURE_TABIAN'] = $explodesetdate[0]." ".toMonth($explodesetdate[1])." ".$explodesetdate[2];
  }
  
  if($scope['SETDATESIGNATURE_HEADTABIAN']){
    $scope['SETDATESIGNATURE_HEADTABIAN'] = toThaiNumber($scope['SETDATESIGNATURE_HEADTABIAN']);
    $explodesetdateheadtabian = explode(",", $scope['SETDATESIGNATURE_HEADTABIAN']);
    $explodesetdateheadtabian = explode(" ", $explodesetdateheadtabian[0]);
    $scope['SETDATESIGNATURE_HEADTABIAN'] = $explodesetdateheadtabian[0]." ".toMonth($explodesetdateheadtabian[1])." ".$explodesetdateheadtabian[2];
    // $explodesetdateheadtabian = explode(",", $scope['SETDATESIGNATURE_HEADTABIAN']);
    // $scope['SETDATESIGNATURE_HEADTABIAN'] = $explodesetdateheadtabian[0];
  }
  $scope['SETDATESIGNATURE_HEADTABIAN'] = toThaiNumber($scope['SETDATESIGNATURE_HEADTABIAN']);

  $scope['reason'] = $scope['REASON_TABIAN'];

  if($scope['STATUS'] === "2" || $scope['STATUS'] === "3" || $scope['STATUS'] === "4"){
    $scope['SIGNAGURE_TABIAN'] = ' <img src="https://e-par.kpru.ac.th/timeKPRU/contents/signature/'.$scope['CITIZENT_TABIAN'].'.jpg" height="25" /> ';
    $scope['REASON_TABIAN'] = 'เห็นควร';
    $scope['reason'] = null;
    $scope['SETDATESIGNATURE_TABIAN'] = $scope['SETDATESIGNATURE_TABIAN'];
    $scope['SETCODESIGNATURE_TABIAN'] = '<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$scope['SETCODESIGNATURE_TABIAN'].'';
  }else if($scope['STATUS'] === "0"){
    if($scope['BILLNO']){
      $scope['SIGNAGURE_TABIAN'] = ' <img src="https://e-par.kpru.ac.th/timeKPRU/contents/signature/'.$scope['CITIZENT_TABIAN'].'.jpg" height="25" /> ';
      $scope['REASON_TABIAN'] = 'เห็นควร';
      $scope['reason'] = null;
      $scope['SETDATESIGNATURE_TABIAN'] = $scope['SETDATESIGNATURE_TABIAN'];
      $scope['SETCODESIGNATURE_TABIAN'] = '<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$scope['SETCODESIGNATURE_TABIAN'].'';
    }else{
      $scope['SIGNAGURE_TABIAN'] = ' <img src="https://e-par.kpru.ac.th/timeKPRU/contents/signature/'.$scope['CITIZENT_TABIAN'].'.jpg" height="25" /> ';
      $scope['REASON_TABIAN'] = 'ไม่เห็นควร';
      $scope['reason'] = "เนื่องจาก".$scope['reason'];
      $scope['SETDATESIGNATURE_TABIAN'] = $scope['SETDATESIGNATURE_TABIAN'];
      $scope['SETCODESIGNATURE_TABIAN'] = '<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$scope['SETCODESIGNATURE_TABIAN'].'';
    }
  }else if($scope['STATUS'] === "1"){
    $scope['SIGNAGURE_TABIAN'] = "....................................";
    $scope['REASON_TABIAN'] = 'เห็นควร';
    $scope['reason'] = null;
    $scope['SETDATESIGNATURE_TABIAN'] = '........../........./............';
    $scope['SETCODESIGNATURE_TABIAN'] = null;
  }

  if($scope['STATUS'] === "4"){
    $scope['checkbox_allow'] = '<img src="https://mua.kpru.ac.th/apipostpone/PostponePDF/images/checkbox.png" width="10" />';
    $scope['checkbox_notallow'] = '<img src="https://mua.kpru.ac.th/apipostpone/PostponePDF/images/blank-checkbox.png" width="10" />';
    $scope['SIGNAGURE_HEADTABIAN'] = ' <img src="https://e-par.kpru.ac.th/timeKPRU/contents/signature/'.$scope['CITIZENT_HEADTABIAN'].'.jpg" height="25" /> ';
    $scope['SETDATESIGNATURE_HEADTABIAN'] = $scope['SETDATESIGNATURE_HEADTABIAN'];
    $scope['SETCODESIGNATURE_HEADTABIAN'] = $scope['SETCODESIGNATURE_HEADTABIAN'];
  }else if ($scope['STATUS'] === "0"){
    if($scope['BILLNO']){
      $scope['checkbox_allow'] = '<img src="https://mua.kpru.ac.th/apipostpone/PostponePDF/images/blank-checkbox.png" width="10" />';
      $scope['checkbox_notallow'] = '<img src="https://mua.kpru.ac.th/apipostpone/PostponePDF/images/checkbox.png" width="10" />';
      $scope['SIGNAGURE_HEADTABIAN'] = ' <img src="https://e-par.kpru.ac.th/timeKPRU/contents/signature/'.$scope['CITIZENT_HEADTABIAN'].'.jpg" height="25" /> ';
      $scope['SETDATESIGNATURE_HEADTABIAN'] = $scope['SETDATESIGNATURE_HEADTABIAN'];
      $scope['SETCODESIGNATURE_HEADTABIAN'] = $scope['SETCODESIGNATURE_HEADTABIAN'];
    }else{
      $scope['checkbox_allow'] = '<img src="https://mua.kpru.ac.th/apipostpone/PostponePDF/images/blank-checkbox.png" width="10" />';
      $scope['checkbox_notallow'] = '<img src="https://mua.kpru.ac.th/apipostpone/PostponePDF/images/blank-checkbox.png" width="10" />';
      $scope['SIGNAGURE_HEADTABIAN'] = null;
      $scope['SETDATESIGNATURE_HEADTABIAN'] = null;
      $scope['SETCODESIGNATURE_HEADTABIAN'] = null;
    }
  }else{
    $scope['checkbox_allow'] = '<img src="https://mua.kpru.ac.th/apipostpone/PostponePDF/images/blank-checkbox.png" width="10" />';
    $scope['checkbox_notallow'] = '<img src="https://mua.kpru.ac.th/apipostpone/PostponePDF/images/blank-checkbox.png" width="10" />';
    $scope['SIGNAGURE_HEADTABIAN'] = null;
    $scope['SETDATESIGNATURE_HEADTABIAN'] = null;
    $scope['SETCODESIGNATURE_HEADTABIAN'] = null;
  }

  if($scope['BILL']){
    $scope['SIGNATURE_MONEY'] = '<img src="https://e-par.kpru.ac.th/timeKPRU/contents/signature/1620500086527.jpg" height="25" />' ;
    $scope['BILLNO'] = " ".toThaiNumber($scope['BILL'])." NO.".toThaiNumber($scope['BILL_NO']);

    $BILL_DATE = $scope['BILL_DATE'];
    $BILL_DATE = explode("/",$BILL_DATE);
    $BILL_DATE = ($BILL_DATE[2]-543)."-".$BILL_DATE[1]."-".$BILL_DATE[0];
    $BILL_DATE = strtotime($BILL_DATE); 
    $BILL_DATE = thai_date_and_time($BILL_DATE);
    $BILL_DATE = toThaiNumber($BILL_DATE);
    $scope['BILL_DATE'] = $BILL_DATE;

    // $scope['BILL_DATE'] = toThaiNumber($scope['BILL_DATE']);
    $scope['BILL_MONEY'] = "&nbsp;&nbsp;&nbsp;&nbsp;".toThaiNumber($scope['BILL_MONEY'])."&nbsp;&nbsp;&nbsp;&nbsp;";
    $scope['SETCODESIGNATURE_MONEY'] = GenarateSignatureTH();
    $scope['SETCODESIGNATURE_MONEY'] = "<br />".$scope['SETCODESIGNATURE_MONEY']['setCodeSignature'];
  }
  else{
    $scope['SIGNATURE_MONEY'] = "........................";
    $scope['BILLNO'] = ".....................";
    $scope['BILL'] = null;
    $scope['BILL_NO'] = null;
    $scope['BILL_DATE'] = "........../........./............";
    $scope['BILL_MONEY'] = "....................";
    $scope['SETCODESIGNATURE_MONEY'] = null;
  }

  if($scope['PRINT_STATUS'] == "1"){
    $NAME = ' '.$scope['NAME'];
    $FULLNAME = '<br />( '.$scope['PNAME'].$scope['NAME']. ' )';
    $scope['SETDATESIGNATURE_PRINTSTATUS'] = toThaiNumber($scope['SETDATESIGNATURE_PRINTSTATUS']);
    $explodesetdate = explode(",", $scope['SETDATESIGNATURE_PRINTSTATUS']);
    $explodesetdate = explode(" ", $explodesetdate[0]);
    $scope['SETDATESIGNATURE_PRINTSTATUS'] = "<br />".$explodesetdate[0]." ".toMonth($explodesetdate[1])." ".$explodesetdate[2];
    // $scope['SETDATESIGNATURE_PRINTSTATUS'] = '<br />'.$res[0]['SETDATESIGNATURE_PRINTSTATUS'];
    $scope['SETCODESIGNATURE_PRINTSTATUS'] = '<br />'.$res[0]['SETCODESIGNATURE_PRINTSTATUS'];
  }else{
    $NAME = '..............................................';
    $FULLNAME = null;
    $scope['SETDATESIGNATURE_PRINTSTATUS'] = null;
    $scope['SETCODESIGNATURE_PRINTSTATUS'] = null;
  }

  require_once('tcpdf.php');

  $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8');
  
  $pdf->SetCreator('คำร้องยกเลิกรายวิชาเรียน');
  $pdf->SetAuthor('คำร้องยกเลิกรายวิชาเรียน');
  $pdf->SetTitle('เอกสารประกอบ');
  $pdf->SetSubject('คำร้องยกเลิกรายวิชาเรียน');
  $pdf->SetKeywords('คำร้องยกเลิกรายวิชาเรียน, kpru, PDF, example, guide');
  
  $pdf->setHeaderFont(array('thsarabun', 'B', 12));
  
  $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, 'APPLICATION FORM FOR COURSE(S) WITHDRAWAL', 'คำร้องยกเลิกรายวิชาเรียน', array (0, 0, 255), array (0, 64, 128));
  $pdf->setFooterData(array (0, 64, 0), array (0, 64, 128));
  
  $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
  $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
  $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
  
  $pdf->setPrintHeader(false);//สำหรับปิด header
  $pdf->setPrintFooter(false);//สำหรับปิด header
  
  $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
  
  $pdf->AddPage();
  $pdf->Ln(10);
  $pdf->SetFont('thsarabun', 'B', 16);
  $htmlcontent='
    <table>
      <tr>
        <th align="center"><img src="https://mua.kpru.ac.th/apipostpone/PostponePDF/images/kpru-logo-line2.png" width="60"></th>
      </tr>
    </table>
  ';
  $pdf->writeHTML($htmlcontent, false, 0, true, 0);
  $pdf->Ln();
  $pdf->SetFont('thsarabun', 'B', 16);
  $pdf->Cell(170, 0, 'คำร้องขอใบรับรอง', 0, 1, 'C', 0, '', 0);
  $pdf->SetFont('thsarabun', '', 15);
  $pdf->Cell(170, 0, 'มหาวิทยาลัยราชภัฎกำแพงเพชร', 0, 1, 'C', 0, '', 0);
  $pdf->Ln();
  $htmlcontent='
    <table>
      <tr>
        <th width="55%"><br /></th>
        <th width="45%">'.$scope['currentdatetime'].'</th>
      </tr>
    </table>
  ';
  $pdf->writeHTML($htmlcontent, false, 0, true, 0);
  $pdf->Cell(170, 0, 'เรื่อง '.$scope['TYPEPITITION_NAME'], 0, 1, 'L', 0, '', 0);
  $pdf->Ln();
  $pdf->Cell(170, 0, 'เรียน รองอธิการบดี ฝ่ายวิชาการ', 0, 1, 'L', 0, '', 0);
  $pdf->Ln();

  $htmlcontent1='
    <table>
      <tr>
        <td style="text-align: justify; text-justify: inter-word;">
          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ข้าพเจ้า  '.$scope['PNAME'].$scope['NAME'].' เกิดวันที่ '.$scope['DayDateBdate'].' เป็นนักศึกษา มหาวิทยาลัยราชภัฎกำแพงเพชร สาขาวิชา
          '.$scope['t_mjname'].' หมู่เรียน '.$scope['GROUP_NO'].' รหัสนักศึกษา '.$scope['OLDID'].' บ้านเลขที่ '.$scope['NUM_HOME'].' หมู่ที่ '.$scope['NUM_MOO'].' ถนน '.$scope['STREET'].'
          ซอย '.$scope['SOI'].' ตำบล'.$scope['SUBDISTRICT_NAME'].' อำเภอ'.$scope['DISTRICT_NAME'].' จังหวัด'.$scope['PROVINCE_NAME'].' รหัสไปรษณีย์ '.$scope['ZIPCODE'].'
          โทรศัพท์มือถือ '.$scope['PHONE'].'
        </td>
      </tr>
      <tr>
        <td>
          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;มีความประสงค์ขอรับใบรับรองดังนี้
        </td>
      </tr>
      <tr>
        <td>
          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;๑. ภาษาไทย&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;จำนวน&nbsp;&nbsp;&nbsp;&nbsp;'.$scope['NUM_RUBRONGTH'].'&nbsp;&nbsp;&nbsp;&nbsp;ฉบับ
        </td>
      </tr>
      <tr>
        <td>
          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;๒. ภาษาอังกฤษ&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;จำนวน&nbsp;&nbsp;&nbsp;&nbsp;'.$scope['NUM_RUBRONGEN'].'&nbsp;&nbsp;&nbsp;&nbsp;ฉบับ
        </td>
      </tr>
      <tr>
        <td>
          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;คำร้องและเหตุผลประกอบ เพื่อนำไปใช้ในการ'.$scope['DETAIL'].'
        </td>
      </tr>
      <tr>
        <td>
          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;จึงเรียนมาเพื่อโปรดพิจารณา
        </td>
      </tr>
    </table>
  ';
  $pdf->writeHTML($htmlcontent1, false, 0, true, 0);
  $pdf->Ln();
  $pdf->Cell(170, 0, 'ขอแสดงความนับถือ', 0, 1, 'C', 0, '', 0);
  $pdf->Cell(170, 0, $scope['NAME'], 0, 1, 'C', 0, '', 0);
  $pdf->Cell(170, 0, '( '.$scope['PNAME'].$scope['NAME'].' )', 0, 1, 'C', 0, '', 0);
  $pdf->Cell(170, 0, $scope['NAME_ENG'], 0, 1, 'C', 0, '', 0);
  $pdf->Cell(170, 0, $scope['SETCODESIGNATURE_INSERT'], 0, 1, 'C', 0, '', 0);
  $pdf->Ln();
  $htmlcontent2='
    <table border="1">
      <thead>
        <tr>
          <th align="center" width="50%"><b>ความเห็นเจ้าหน้าที่</b></th>
          <th align="center" width="30%"><b>ฝ่ายการเงิน</b></th>
          <th align="center" width="23%"><b>คำสั่ง</b></th>
        </tr>
      </thead>
      <tbody>
        <tr style="text-align: justify; text-justify: inter-word;">
          <td width="50%"> 
            เรียนรองอธิการฝ่ายวิชาการ <br />&nbsp;&nbsp;&nbsp;&nbsp;ตามที่ '.$scope['PNAME'].$scope['NAME'].' มีความประสงค์จะขอ '.$scope['TYPEPITITION_NAME'].' ฝ่ายทะเบียนและประมวลผล '.$scope['REASON_TABIAN'].' ให้ชำระเงินธรรมเนียม จำนวน '.$scope['NUM_RUBRONG'].' ฉบับ ฉบับละ '.$scope['TYPEPITITION_MONEY'].' บาท เป็นจำนวนเงิน '.$scope['MONEY_RUBRONG'].' บาท '.$scope['reason'].'
            <br />ลงชื่อ'.$scope['SIGNAGURE_TABIAN'].'ฝ่ายทะเบียนและประมวลผล
            <br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$scope['SETDATESIGNATURE_TABIAN'].'
            '.$scope['SETCODESIGNATURE_TABIAN'].'
          </td>
          <td align="center" width="30%">
            ได้รับค่าธรรมเนียม<br />เล่มที่/เลขที่'.$scope['BILLNO'].'<br />จำนวน'.$scope['BILL_MONEY'].'บาท<br />ลงชื่อ'.$scope['SIGNATURE_MONEY'].'<br />ผู้รับเงิน<br />'.$scope['BILL_DATE'].$scope['SETCODESIGNATURE_MONEY'].'
          </td>
          <td width="23%">
            '.$scope['checkbox_allow'].' อนุญาต<br />&nbsp;'.$scope['checkbox_notallow'].' ไม่อนุญาต
            <br /><span align="center">'.$scope['SIGNAGURE_HEADTABIAN'].'<br />'.$scope['SETDATESIGNATURE_HEADTABIAN'].'<br />'.$scope['SETCODESIGNATURE_HEADTABIAN'].'</span>
          </td>
        </tr>
      </tbody>
    </table>
  ';
  $pdf->writeHTML($htmlcontent2, false, 0, true, 0);
  $pdf->Cell(170, 0, 'ข้าพเจ้าได้รับทราบเรียบร้อยแล้ว', 0, 1, 'L', 0, '', 0);
  $htmlcontent3='
    <table>
      <tr style="text-align: justify; text-justify: inter-word;">
        <td width="45%"></td>
        <td width="50%" align="center">
          ลงชื่อผู้รับ'.$NAME.$FULLNAME.$scope['SETDATESIGNATURE_PRINTSTATUS'].$scope['SETCODESIGNATURE_PRINTSTATUS'].'
        </td>
        <td width="5%"></td>
      </tr>
    </table>
  ';
  $pdf->writeHTML($htmlcontent3, false, 0, true, 0);
  $regbill_FILE = "test182222222.pdf";
	$pdf->Output($regbill_FILE,"I",true);
?>
