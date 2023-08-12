<?php
	class Plant extends Controller {
	    function __construct(){
	    		parent::__construct();
			}
			
	    function index(){
			header("Content-Type: application/json; charset=UTF-8");
				$arr = array(
					'status' => 200,
					'status_name' => header_status(200),
					'data' => array(
						'success' => "ไม่อนุญาตให้เข้าใช้งานส่วนใดของระบบ"
					)
				);
				echo json_encode($arr);
				http_response_code(200);
	    }
        function getUser(){
            $this->model->getUser();
        }
        function SelectProvince(){
            $this->model->SelectProvince();
        }
        function SelectAmphur(){
            $this->model->SelectAmphur();
        }
        function SelectTambon(){
            $this->model->SelectTambon();
        }
        function ZipCode(){
            $this->model->ZipCode();
        }
        function AddPlant(){
            $this->model->AddPlant();
        }
        function uploadImage(){
            $this->model->uploadImage();
        }

	}
?>