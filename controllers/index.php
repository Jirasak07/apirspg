<?php
	class index extends Controller {
	    function __construct(){
	    		parent::__construct();
			}
			
	    function index(){
			header("Content-Type: application/json; charset=UTF-8");
				$arr = array(
					'status' => 200,
					'status_name' => header_status(200),
					'data' => array(
						'success' => "ไม่อนุญาตให้เข้าใช้งานระบบใดๆ"
					)
				);
				echo json_encode($arr);
				http_response_code(200);
	    }

	}
?>