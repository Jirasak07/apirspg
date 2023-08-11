<?php
 	header("Content-Type: application/json; charset=UTF-8");
	class Plant_model extends Model{
		function __construct(){
				parent::__construct();
		}
		function getUser(){
            $sql = $this->db->prepare("
            SELECT * FROM tb_user
            ");
            $sql->execute(array());
            $data = $sql->fetchAll(PDO::FETCH_ASSOC);
            $token =  GenarateToken($data[0]['username']);
            echo json_encode($token,JSON_PRETTY_PRINT);
        }	
        function SelectProvince(){
            $sql = $this->db->prepare("
            SELECT PROVINCE_ID,PROVINCE_NAME FROM tb_province
            ");
            $sql->execute(array());
            $data = $sql->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode($data,JSON_PRETTY_PRINT);
        }
		
	}
?>