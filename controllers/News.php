<?php
	class News extends Controller {
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
        function AddNews(){
			$this->model->AddNews();
        }
        function getNew(){
			$this->model->getNew();
        }
        function getEditNews(){
			$this->model->getEditNews();
        }
        function EditNews(){
			$this->model->EditNews();
        }
        function getNewTable(){
			$this->model->getNewTable();
        }
	}
?>