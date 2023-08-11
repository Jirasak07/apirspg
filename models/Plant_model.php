<?php
 	header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Origin: http://localhost:3000");
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
            SELECT PROVINCE_ID AS value,PROVINCE_NAME AS label FROM tb_province
            ");
            $sql->execute(array());
            $data = $sql->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode($data,JSON_PRETTY_PRINT);
        }
        function SelectAmphur(){

            $json = json_decode(file_get_contents("php://input"));
            $pv_id = $json->pv_id;
            $sql = $this->db->prepare("
            SELECT AMPHUR_ID,AMPHUR_NAME FROM tb_amphur WHERE PROVINCE_ID = '$pv_id'
            ");
            $sql->execute(array());
            $data = $sql->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode($data,JSON_PRETTY_PRINT);
        }
        function SelectTambon(){
            $json = json_decode(file_get_contents("php://input"));
            $amphur = $json->amphur;
            $sql = $this->db->prepare("
            SELECT DISTRICT_ID,DISTRICT_NAME ,(SELECT POST_CODE FROM tb_amphur_postcode WHERE AMPHUR_id = '$amphur') AS ZIPCODE FROM tb_district AS A  WHERE AMPHUR_ID = '$amphur'
            ");
            $sql->execute(array());
            $data = $sql->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode($data,JSON_PRETTY_PRINT);
        }
		
	}
?>