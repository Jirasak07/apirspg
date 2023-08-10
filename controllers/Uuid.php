<?php
	class Uuid extends Controller {
	    function __construct(){
	    		parent::__construct();
			}
			
	    function GenarateUuid(){
			$headers = apache_request_headers();
			if(isset($headers['Authorization'])){
				$namestd = explode(" ", $headers['Authorization']);
				$token = CheckToken($namestd[1]);
				$REQUEST_METHOD = $_SERVER["REQUEST_METHOD"];
				switch($REQUEST_METHOD){
					case 'POST' :
									$this->model->SelectUuid();
									break;
					case 'PUT' :
									$this->model->GenarateUuid();
									break;
					case 'DELETE' :
									$this->model->DeleteUuid();
									break;
					default:
									$this->model->error();
									break;
				}
			} else{
				$this->model->errorAuthorization();
			}
	    }
			
	}
?>