<?php
header("Content-Type: application/json; charset=UTF-8");
class Plant_model extends Model
{
    public function __construct()
    {
        parent::__construct();
    }
    public function getUser()
    {
        $sql = $this->db->prepare("
            SELECT * FROM tb_user
            ");
        $sql->execute(array());
        $data = $sql->fetchAll(PDO::FETCH_ASSOC);
        $token = GenarateToken($data[0]['username']);
        echo json_encode($token, JSON_PRETTY_PRINT);
    }
    public function SelectProvince()
    {
        $sql = $this->db->prepare("
            SELECT code AS value,name_th AS label FROM provinces WHERE code = '62'
            ");
        $sql->execute(array());
        $data = $sql->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($data, JSON_PRETTY_PRINT);
    }
    public function SelectAmphur()
    {
        $sql = $this->db->prepare("
            SELECT code AS value,name_th AS label FROM amphures WHERE province_id = '62'
            ");
        $sql->execute(array());
        $data = $sql->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($data, JSON_PRETTY_PRINT);
    }
    public function SelectTambon()
    {
        $json = json_decode(file_get_contents("php://input"));
        $amphur = $json->amphur;
        $sql = $this->db->prepare("
            SELECT id AS value,name_th AS label  FROM districts AS A  WHERE amphure_id = '$amphur'
            ");
        $sql->execute(array());
        $data = $sql->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($data, JSON_PRETTY_PRINT);
    }
    public function ZipCode()
    {
        $json = json_decode(file_get_contents("php://input"));
        $amphur = $json->amphur;
        $sql = $this->db->prepare("
            SELECT zip_code AS ZIPCODE FROM districts WHERE id = '$amphur'
            ");
        $sql->execute(array());
        $data = $sql->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($data, JSON_PRETTY_PRINT);
    }
    public function AddPlant()
    {
        $json = json_decode(file_get_contents("php://input"));
        $plant_id = $json->plant_code;
        $plant_name = $json->plant_name;
        $area = $json->area;
        $locate_x = $json->locate_x;
        $locate_y = $json->locate_y;
        $distinctive = $json->distinctive;
        $qty = $json->qty;
        $girth = $json->girth;
        $status = $json->status;
        $tambol = $json->tambol;
        $province = '62';
        $plant_character = $json->plant_character;
        $amphure = $json->amphure;
        $benefit_appliances = $json->benefit_appliances;
        $benefit_food = $json->benefit_food;
        $benefit_medicine_animal = $json->benefit_medicine_animal;
        $benefit_medicine_human = $json->benefit_medicine_human;
        $benefit_pesticide = $json->benefit_pesticide;
        $height = $json->height;
        $name_adder = $json->name_adder;
        $other = $json->other;
        $age_adder = $json->age_adder;
        $about_tradition = $json->about_tradition;
        $about_religion = $json->about_religion;
        $age = $json->age;
        $user_id = $json->user_id;
        $currentTime = new DateTime();
        $createdAt = $currentTime->format('Y-m-d H:i:s');
        $sqladd = $this->db->prepare("
            insert into tb_plant (
                plant_name,
                plant_code,
                plant_character,
                distinctive,
                area,
                locate_x,
                locate_y,
                tumbol,
                amphure,
                province,
              
                age,
                girth,
                height,
                statuss,
                benefit_food,
                benefit_medicine_human,
                benefit_medicine_animal,
                benefit_appliances,
                benefit_pesticide,
                about_tradition,
                about_religion,
                other,
                name_adder,
                age_adder,
             
                date_add,
                user_id,
                qty
                )
                values(
  
                '$plant_name',
                '$plant_id',
                '$plant_character',
                '$distinctive',
                '$area',
                '$locate_x',
                '$locate_y',
                '$tambol',
                '$amphure',
                '$province',
              
                '$age',
                '$girth',
                '$height',
                '$status',
                '$benefit_food',
                '$benefit_medicine_human',
                '$benefit_medicine_animal',
                '$benefit_appliances',
                '$benefit_pesticide',
                '$about_tradition',
                '$about_religion',
                '$other',
                '$name_adder',
                '$age_adder',

                '$createdAt',
                '$user_id','$qty')
            ");

        //  $data  = $sqladd->fetchAll(PDO::FETCH_ASSOC);
        if ($sqladd->execute(array())) {
            $ss = $this->db->prepare("
            SELECT MAX(plant_id) as plant_id from tb_plant 
            ");
            $ss->execute(array());
            $plant_ids = $ss->fetchAll(PDO::FETCH_ASSOC);
            $plant_ids = $plant_ids[0]["plant_id"];
            $array = [
                "status" => "success",
                "plant_id" => $plant_ids,
            ];
            echo json_encode($array, JSON_PRETTY_PRINT);
        } else {
            $error = $sqladd->errorInfo();
            echo json_encode($error[2], JSON_PRETTY_PRINT);
        }
    }
    public function uploadImage()
    {
        $name = $_REQUEST['name'];
        $plantid = $_REQUEST['plant_id'];
        $user_id = $_REQUEST['user_id'];
        $file_type = $_FILES['file']['type'];
        $currentTime = new DateTime();
        $createdAt = $currentTime->format('Y-m-d H:i:s');
        $filename = "public/uploadimg/";
        if (!file_exists($filename)) {
            mkdir("public/uploadimg/", 0777);
        }
        if ($file_type === "image/png" || $file_type === "image/jpg" || $file_type === "image/jpeg") {
            $files_upload = basename($_FILES["file"]["name"]);
            $imageFileType = strtolower(pathinfo($files_upload, PATHINFO_EXTENSION));
            $delete = $filename . "/$name.png";
            $delete1 = $filename . "/$name.jpeg";
            $delete2 = $filename . "/$name.jpg";
            if (file_exists($delete)) {
                unlink($delete);
            }
            if (file_exists($delete1)) {
                unlink($delete1);
            }
            if (file_exists($delete2)) {
                unlink($delete2);
            }
            if (move_uploaded_file($_FILES["file"]["tmp_name"], $filename . "$name." . $imageFileType)) {
                $img = strval($filename . "$name." . $imageFileType);
                $sqlmaxid = $this->db->prepare("
                    SELECT COUNT(*) total FROM tb_plant_img
                    ");
                $sqlmaxid->execute(array());
                $total = $sqlmaxid->fetchAll(PDO::FETCH_ASSOC);
                $total = $total[0]['total'];
                $mid = 0;
                if (intval($total) <= 0) {
                    $mid = 1;
                } else {
                    $mid = intval($total) + 1;
                }
                $sqlimg = $this->db->prepare("
                    INSERT INTO tb_plant_img VALUES('$mid','$img','$plantid','$user_id',CURRENT_TIMESTAMP())
                    ");

                if ($sqlimg->execute(array())) {
                    echo json_encode("success", JSON_PRETTY_PRINT);
                } else {
                    echo json_encode("error", JSON_PRETTY_PRINT);
                }
            }
        } else {
            echo json_encode("error file type", JSON_PRETTY_PRINT);
        }
    }
    public function updateImage()
    {
        $name = $_REQUEST['name'];
        $plantid = $_REQUEST['plant_id'];
        $user_id = $_REQUEST['user_id'];
        // $file_name = $_FILES['file']['name'];
        // $file_size =$_FILES['file']['size'];
        // $file_tmp =$_FILES['file']['tmp_name'];
        $file_type = $_FILES['file']['type'];
        $currentTime = new DateTime();
        $createdAt = $currentTime->format('Y-m-d H:i:s');
        $filename = "public/uploadimg/";
        if (!file_exists($filename)) {
            mkdir("public/uploadimg/", 0777);
        }
        if ($file_type === "image/png" || $file_type === "image/jpg" || $file_type === "image/jpeg") {
            $files_upload = basename($_FILES["file"]["name"]);
            $imageFileType = strtolower(pathinfo($files_upload, PATHINFO_EXTENSION));
            $delete = $filename . "/$name.png";
            $delete1 = $filename . "/$name.jpeg";
            $delete2 = $filename . "/$name.jpg";
            if (file_exists($delete)) {
                unlink($delete);
            }
            if (file_exists($delete1)) {
                unlink($delete1);
            }
            if (file_exists($delete2)) {
                unlink($delete2);
            }
            if (move_uploaded_file($_FILES["file"]["tmp_name"], $filename . "$name." . $imageFileType)) {
                $img = strval($filename . "$name." . $imageFileType);
                // $imgc = strval($filename . "$name");
                $sqll = $this->db->prepare("
                SELECT COUNT(*) AS t FROM tb_plant_img WHERE image_name LIKE '%$name%'
                ");
                $sqll->execute(array());
                $total = $sqll->fetchAll(PDO::FETCH_ASSOC);
                $total = $total[0]['t'];
                if (intval($total) !== 0) {
                    $sqld = $this->db->prepare("
                    UPDATE tb_plnant_img SET user_id = '$user_id',image_date = CURRENT_TIMESTAMP() WHERE image_name = '$img'
                    ");
                    if ($sqld->execute(array())) {
                        echo json_encode("success", JSON_PRETTY_PRINT);
                    } else {
                        echo json_encode("error", JSON_PRETTY_PRINT);
                    }
                } else {
                    $sqlmaxid = $this->db->prepare("
                    SELECT COUNT(*) total FROM tb_plant_img
                    ");
                    $sqlmaxid->execute(array());
                    $total = $sqlmaxid->fetchAll(PDO::FETCH_ASSOC);
                    $total = $total[0]['total'];
                    $mid = 0;
                    if (intval($total) <= 0) {
                        $mid = 1;
                    } else {
                        $mid = intval($total) + 1;
                    }
                    $sqlimg = $this->db->prepare("
                    INSERT INTO tb_plant_img VALUES('$mid','$img','$plantid','$user_id',CURRENT_TIMESTAMP())
                    ");
                    if ($sqlimg->execute(array())) {
                        echo json_encode("success", JSON_PRETTY_PRINT);
                    } else {
                        echo json_encode("error", JSON_PRETTY_PRINT);
                    }
                }
            }
        } else {
            echo json_encode("error file type", JSON_PRETTY_PRINT);
        }
    }
    //         $imageFileType = strtolower(pathinfo($files_upload, PATHINFO_EXTENSION));
    public function getPlant()
    {
        $json = json_decode(file_get_contents("php://input"));
        $sql = $this->db->prepare("
        SELECT *,(SELECT C.image_name FROM tb_plant_img AS C WHERE C.plant_id = A.plant_id ORDER BY C.img_id ASC LIMIT 1) AS img FROM `tb_plant` AS A
        ");
        $sql->execute(array());
        $data = $sql->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($data, JSON_PRETTY_PRINT);
    }
    public function getPlants()
    {
        $json = json_decode(file_get_contents("php://input"));
        $sql = $this->db->prepare("
        SELECT *,(SELECT C.image_name FROM tb_plant_img AS C WHERE C.plant_id = A.plant_id ORDER BY C.img_id ASC LIMIT 1) AS img,
         (SELECT name_th FROM amphures WHERE code = A.amphure) AS amphure,
        (SELECT name_th FROM districts WHERE id = A.tumbol) AS tumbol
        FROM `tb_plant` AS A
        ");
        $sql->execute(array());
        $data = $sql->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($data, JSON_PRETTY_PRINT);
    }
    public function detailPlant()
    {
        $json = json_decode(file_get_contents("php://input"));
        $plant_id = $json->plant_id;
        $sql = $this->db->prepare("
        SELECT * FROM `tb_plant` AS A WHERE plant_id = '$plant_id'
        ");
        $sql->execute(array());
        $data = $sql->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($data, JSON_PRETTY_PRINT);
    }
    public function detailPlants()
    {
        $json = json_decode(file_get_contents("php://input"));
        $plant_id = $json->plant_id;
        $sql = $this->db->prepare("
        SELECT *, (SELECT name_th FROM amphures WHERE code = A.amphure) AS amphure,
        (SELECT name_th FROM districts WHERE id = A.tumbol) AS tumbol,
        (SELECT name_th FROM provinces WHERE code = A.province) AS province FROM `tb_plant` AS A WHERE plant_id = '$plant_id'
        ");
        $sql->execute(array());
        $data = $sql->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($data, JSON_PRETTY_PRINT);
    }
    public function print()
    {
        $id = $_GET['id'];
        $sql = $this->db->prepare("
        SELECT *,(SELECT name_th FROM provinces WHERE id = A.province) AS province,
        (SELECT name_th FROM amphures WHERE id = A.amphure) AS amphur,
        (SELECT name_th FROM districts WHERE id = A.tumbol) AS tumbol
        ,(SELECT C.image_name FROM tb_plant_img AS C WHERE C.plant_id = A.plant_id ORDER BY C.img_id DESC LIMIT 1) AS img,(SELECT name FROM tb_user WHERE user_id = A.user_id) AS useredit FROM `tb_plant` AS A WHERE plant_id = '$id';
        ");
        $sql->execute(array());
        $data = $sql->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($data, JSON_PRETTY_PRINT);
    }
    public function Search()
    {
        if (isset($_POST['name'])) {
            $name = $_POST['name'];
            $sql = $this->db->prepare("
        SELECT *,(SELECT C.image_name FROM tb_plant_img AS C WHERE C.plant_id = A.plant_id ORDER BY C.img_id DESC LIMIT 1) AS img FROM `tb_plant` AS A WHERE plant_name LIKE '%$name%'
        ");
            $sql->execute(array());
            $data = $sql->fetchAll(PDO::FETCH_ASSOC);
            if ($sql->execute(array())) {
                echo json_encode($data, JSON_PRETTY_PRINT);
            } else {
            }
        } else {
            echo json_encode("error", JSON_PRETTY_PRINT);
        }
    }
    public function PlantEdit()
    {
        $json = json_decode(file_get_contents("php://input"));
        $plant_id = $json->plant_id;
        $plant_code = $json->plant_code;
        $plant_name = $json->plant_name;
        $area = $json->area;
        $locate_x = $json->locate_x;
        $locate_y = $json->locate_y;
        $distinctive = $json->distinctive;
        $qty = $json->qty;
        $girth = $json->girth;
        $status = $json->status;
        $tambol = $json->tambol;
        $province = '62';
        $plant_character = $json->plant_character;
        $amphure = $json->amphure;
        $benefit_appliances = $json->benefit_appliances;
        $benefit_food = $json->benefit_food;
        $benefit_medicine_animal = $json->benefit_medicine_animal;
        $benefit_medicine_human = $json->benefit_medicine_human;
        $benefit_pesticide = $json->benefit_pesticide;
        $height = $json->height;
        $name_adder = $json->name_adder;
        $other = $json->other;
        $about_tradition = $json->about_tradition;
        $about_religion = $json->about_religion;
        $age = $json->age;
        $user_id = $json->user_id;
        $currentTime = new DateTime();
        $createdAt = $currentTime->format('Y-m-d H:i:s');
        $sql = $this->db->prepare("
        UPDATE tb_plant 
        SET plant_name = '$plant_name',
        plant_code = '$plant_code',
        plant_character='$plant_character',
        distinctive='$distinctive',
        area='$area',
        locate_x='$locate_x',
        locate_y='$locate_y',
        age='$age',
        girth='$girth',
        height='$height',
        status='$status',
        amphure = '$amphure',
        tumbol = '$tambol',
        benefit_food='$benefit_food',
        benefit_medicine_human='$benefit_medicine_human',
        benefit_medicine_animal='$benefit_medicine_animal',
        benefit_appliances='$benefit_appliances',
        benefit_pesticide='$benefit_pesticide',
        about_tradition='$about_tradition',
        about_religion='$about_religion',
        other='$other',
        name_adder='$name_adder',
        date_add='$createdAt',
        user_id='$user_id',
        qty='$qty' 
        WHERE plant_id = '$plant_id'
        ");
        $sql->execute(array());
        if ($sql->execute()) {
            echo json_encode("success", JSON_PRETTY_PRINT);
        } else {
            echo json_encode("error", JSON_PRETTY_PRINT);
        }
    }
    public function ShowImage()
    {
        $json = json_decode(file_get_contents("php://input"));
        $id = $json->plant_id;
        $sql = $this->db->prepare("
        SELECT * FROM tb_plant_img WHERE plant_id = '$id'
        ");
        $sql->execute(array());
        $data = $sql->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($data, JSON_PRETTY_PRINT);
    }
    public function ShowImagePdf()
    {
        $id = $_GET['id'];
        $sql = $this->db->prepare("
        SELECT * FROM tb_plant_img WHERE plant_id = '$id'
        ");
        $sql->execute(array());
        $data = $sql->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($data, JSON_PRETTY_PRINT);
    }
    public function upload()
    {
        $id = $_POST["plant_id"];
        $type = $_POST["type"];
        $user = $_POST["user"];
        $uploadDir = 'public/uploads/plant/plant' . $id . '/';
        if (!file_exists($uploadDir)) {
            if (!mkdir($uploadDir, 0777, true)) {
                echo json_encode(['status' => 'error', 'message' => 'Failed to create upload directory.']);
                exit;
            }
        }
        // เช็คว่ามีไฟล์ที่อัพโหลดมาหรือไม่
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['file'])) {
            $file = $_FILES['file'];
            // $fileName = basename($file['name']);
            $originalFileName = basename($file['name']);
            // $targetFilePath = $uploadDir . $fileName;
            // สร้างชื่อไฟล์ใหม่
            $newFileName = uniqid() . '_' . $originalFileName;
            $targetFilePath = $uploadDir . $newFileName;
            // ตรวจสอบการอัพโหลดไฟล์
            if ($file['error'] !== UPLOAD_ERR_OK) {
                echo json_encode(['status' => 'error', 'message' => 'File upload error: ' . $file['error']]);
                exit;
            }
            // เช็คว่าไฟล์ถูกย้ายไปยังโฟลเดอร์ uploads สำเร็จหรือไม่
            if (move_uploaded_file($file['tmp_name'], $targetFilePath)) {
                // Return path ของไฟล์
                $filePath = 'public/uploads/plant/plant' . $id . '/' . $newFileName;
                $sqlinset = $this->db->prepare("
                INSERT INTO `tb_plant_img`
                (
                `image_name`,
                `plant_id`,
                `user_id`,
                `image_date`,
                `type_img`) 
                VALUES (
                '$filePath',
                '$id',
                '$user',
                CURRENT_DATE(),
                '$type')
                ");
                $sqlinset->execute();
                echo json_encode("success", JSON_PRETTY_PRINT);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Failed to move uploaded file.']);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'No file uploaded.']);
        }
    }
    public function uploads()
    {
        $type_img = $_POST["type_img"];
        $id = $_POST["plant_id"];
        $imgold = $_POST["image_name"];
        $user = $_POST["user"];
        $uploadDir = 'public/uploads/plant/plant' . $id . '/';
        if (!file_exists($uploadDir)) {
            if (!mkdir($uploadDir, 0777, true)) {
                echo json_encode(['status' => 'error', 'message' => 'Failed to create upload directory.']);
                exit;
            }
        }
        // เช็คว่ามีไฟล์ที่อัพโหลดมาหรือไม่
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['file'])) {
            $file = $_FILES['file'];
            // $fileName = basename($file['name']);
            $originalFileName = basename($file['name']);
            // $targetFilePath = $uploadDir . $fileName;
            // สร้างชื่อไฟล์ใหม่
            $newFileName = uniqid() . '_' . $originalFileName;
            $targetFilePath = $uploadDir . $newFileName;
            // ตรวจสอบการอัพโหลดไฟล์
            if ($file['error'] !== UPLOAD_ERR_OK) {
                echo json_encode(['status' => 'error', 'message' => 'File upload error: ' . $file['error']]);
                exit;
            }
            // เช็คว่าไฟล์ถูกย้ายไปยังโฟลเดอร์ uploads สำเร็จหรือไม่
            if (move_uploaded_file($file['tmp_name'], $targetFilePath)) {
                // Return path ของไฟล์
                $filePath = 'public/uploads/plant/plant' . $id . '/' . $newFileName;
                $sqlinset = $this->db->prepare("
                UPDATE tb_plant_img SET image_name = '$filePath',user_id = '$user' WHERE type_img = '$type_img' and plant_id = '$id'
                ");
                $sqlinset->execute();
                $paths = $imgold;
                if (file_exists($paths)) {
                    if (unlink($paths)) {
                    }
                }
                echo json_encode(['path' => $filePath], JSON_PRETTY_PRINT);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Failed to move uploaded file.']);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'No file uploaded.']);
        }
    }
    public function GetPlantLocate()
    {
        $json = json_decode(file_get_contents("php://input"));
        $API = $json->API;
        $conn = new Database;
        $sql = $conn->prepare("
			SELECT * FROM tb_plant  WHERE locate_x <> '-' AND locate_x IS NOT NULL 
			");
        $sql->execute(array());
        $data = $sql->fetchAll(PDO::FETCH_ASSOC);
        $use = array();
        $i = 0;
        foreach ($data as $d) {
            $sqlimg = $this->db->prepare("
            SELECT image_name FROM tb_plant_img WHERE plant_id = '" . $d["plant_id"] . "' LIMIT 1
            ");
            $sqlimg->execute(array());
            $dataimg = $sqlimg->fetchAll(PDO::FETCH_ASSOC);
            if (count($dataimg) > 0) {
                $dataimg = $dataimg[0]["image_name"];
                $use[$i] = [
                    "id" => $i,
                    "position" => [
                        floatval($d["locate_x"]), floatval($d["locate_y"])
                    ],
                    "popup" => $d["plant_name"],
                    "imageUrl" => $API . '/' . $dataimg
                ];
            } else {
                $use[$i] = [
                    "id" => $i,
                    "position" => [
                        floatval($d["locate_x"]), floatval($d["locate_y"])
                    ],
                    "popup" => $d["plant_name"],
                    "imageUrl" => $API . '/public/images/logobtm.png'
                ];
            }

            $i++;
        }
        echo json_encode($use, JSON_PRETTY_PRINT);
    }
}
