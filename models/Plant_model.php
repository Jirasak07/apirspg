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
            SELECT id AS value,name_th AS label FROM provinces WHERE id = '49'
            ");
            $sql->execute(array());
            $data = $sql->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode($data,JSON_PRETTY_PRINT);
        }
        function SelectAmphur(){

            $json = json_decode(file_get_contents("php://input"));
            $pv_id = $json->pv_id;
            $sql = $this->db->prepare("
            SELECT id AS AMPHUR_ID,name_th AS AMPHUR_NAME FROM amphures WHERE province_id = '$pv_id'
            ");
            $sql->execute(array());
            $data = $sql->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode($data,JSON_PRETTY_PRINT);
        }
        function SelectTambon(){
            $json = json_decode(file_get_contents("php://input"));
            $amphur = $json->amphur;
            $sql = $this->db->prepare("
            SELECT id AS DISTRICT_ID,name_th AS DISTRICT_NAME  FROM districts AS A  WHERE amphure_id = '$amphur'
            ");
            $sql->execute(array());
            $data = $sql->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode($data,JSON_PRETTY_PRINT);
        }
        function ZipCode(){
            $json = json_decode(file_get_contents("php://input"));
            $amphur = $json->amphur;
            $sql = $this->db->prepare("
            SELECT zip_code AS ZIPCODE FROM districts WHERE id = '$amphur'
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
             
            //  $data  = $sqladd->fetchAll(PDO::FETCH_ASSOC);
             if($sqladd->execute(array())){
                $array = [
                    "mes"=>"success",
                    "val"=>$pid
                ];
                echo json_encode($array,JSON_PRETTY_PRINT);
             }else{
                $array = [
                    "mes"=>"error",
                    "val"=>$pid
                ];
                echo json_encode($array,JSON_PRETTY_PRINT);
             }
        }
        function uploadImage(){
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                if (isset($_FILES['file'])) {
                    $uploadDir = 'public/uploadimg/';
                    $filename = basename($_FILES['file']['name']);
                    $name = $_POST['name'];  // รับค่าชื่อไฟล์ใหม่จากตัวแปร $_POST['name']
                    $targetPath = $uploadDir . $name;
            
                    // Check if the file exists and delete it
                    if (file_exists($targetPath)) {
                        unlink($targetPath);
                    }
            
                    if (move_uploaded_file($_FILES['file']['tmp_name'], $targetPath)) {
                        echo json_encode(['message' => 'Upload successful']);
                    } else {
                        echo json_encode(['error' => 'Upload failed']);
                    }
                } else {
                    echo json_encode(['error' => 'No file uploaded']);
                }
            } else {
                echo json_encode(['error' => 'Invalid request']);
            }
	}
?>