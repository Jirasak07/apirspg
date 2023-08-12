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
            SELECT PROVINCE_ID AS value,PROVINCE_NAME AS label FROM tb_province WHERE PROVINCE_ID = '49'
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
        function ZipCode(){
            $json = json_decode(file_get_contents("php://input"));
            $amphur = $json->amphur;
            $sql = $this->db->prepare("
            SELECT POST_CODE AS ZIPCODE FROM tb_amphur_postcode WHERE AMPHUR_id = '$amphur'
            ");
            $sql->execute(array());
            $data = $sql->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode($data,JSON_PRETTY_PRINT);
        }
        function AddPlant(){
            $json = json_decode(file_get_contents("php://input"));
            $plant_id = $json->plant_code;
            $plant_name = $json->plant_name;
            $area = $json->area;
            $locate_x = $json->x;
            $locate_y = $json->y;
            $distinctive = $json->distinctive;
            $qty = $json->qty;
            $girth = $json->radius;
            $status = $json->status;
            $tambol = $json->tambon_id;
            $zipcode = $json->zipcode;
            $province = '49';
            $plant_character = $json->plant_character;
            $amphure = $json->amphur_id;
            $benefit_appliances =$json->benefit_appliances;
            $benefit_foot = $json->benefit_foot;
            $benefit_medicine_animal = $json->benefit_medicine_animal;
            $benefit_medicine_human = $json->benefit_medicine_human;
            $benefit_pesticide = $json->benefit_pesticide;
            $height = $json->height;
            $name_adder = $json->name_adder;
            $other=$json->other;
            $age_adder = $json->age_adder;
            $address_adder =$json->address_adder;
            $about_tradition = $json->about_tradition;
            $about_religion = $json->about_religion;
            $age = $json->age;
            $user_id = $json->user_id;
            $currentTime = new DateTime();
            $createdAt = $currentTime->format('Y-m-d H:i:s');
            $sql= $this->db->prepare("
            SELECT COUNT(*) as total FROM tb_plant
            ");
            $sql->execute(array());
            $total = $sql->fetchAll(PDO::FETCH_ASSOC);
            $total = $total[0]['total'];
            $pid = 0;
            if(intval($total) <= 0 ){
                $pid = 1;
            }else{
                $pid = intval($total) +1;
            }
            $sqladd = $this->db->prepare("
            insert into tb_plant (
                plant_id,
                plant_name,
                plant_code,
                plant_character,
                distinctive,
                area,
                lacate_x,
                locate_y,
                tumbol,
                amphure,
                province,
                zipcode,
                age,
                girth,
                height,
                statuss,
                benefit_foot,
                benefit_medicine_human,
                benefit_medicine_animal,
                benefit_appliances,
                benefit_pesticide,
                about_tradition,
                about_religion,
                other,
                name_adder,
                age_adder,
                address_adder,
                date_add,
                user_id,
                qty
                )
                values('$pid','$plant_name','$plant_id',
                '$plant_character','$distinctive','$area',
                '$locate_x','$locate_y','$tambol','$amphure',
                '$province','$zipcode','$age','$girth','$height',
                '$status','$benefit_foot','$benefit_medicine_human',
                '$benefit_medicine_animal','$benefit_appliances',
                '$benefit_pesticide','$about_tradition','$about_religion',
                '$other','$name_adder','$age_adder','$address_adder',
                '$createdAt','$user_id','$qty')
            ");
             $sqladd->execute(array());
            //  $data  = $sqladd->fetchAll(PDO::FETCH_ASSOC);
             if($sqladd === true){
                echo json_encode('success');
             }
        }
		
	}
?>