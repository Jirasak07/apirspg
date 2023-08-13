<?php
	class User extends Controller {
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
       function AddUser(){
        $this->model->AddUser();
       }
       function Login(){
        $this->model->Login();
       }
       function checkLogin(){
        $this->model->checkLogin();
       }
       function getUser(){
        $this->model->getUser();
       }
       function EditRole(){
        $this->model->EditRole();
       }
       function EditAuth(){
        $this->model->EditAuth();
       }
       function ShowProfile(){
        $this->model->ShowProfile();
       }
       function EditProfile(){
        $this->model->EditProfile();
       }
       function ChangePass(){
        $this->model->ChangePass();
       }
	}
?>