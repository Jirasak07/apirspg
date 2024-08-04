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

        $json = json_decode(file_get_contents("php://input"));
        $pv_id = $json->pv_id;
        $sql = $this->db->prepare("
            SELECT code AS AMPHUR_ID,name_th AS AMPHUR_NAME FROM amphures WHERE province_id = '$pv_id'
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
            SELECT id AS DISTRICT_ID,name_th AS DISTRICT_NAME,zip_code  FROM districts AS A  WHERE amphure_id = '$amphur'
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
        $locate_x = $json->x;
        $locate_y = $json->y;
        $distinctive = $json->distinctive;
        $qty = $json->qty;
        $girth = $json->radius;
        $status = $json->status;
        $tambol = $json->tambon_id;
        $zipcode = $json->zipcode;
        $province = '62';
        $plant_character = $json->plant_character;
        $amphure = $json->amphur_id;
        $benefit_appliances = $json->benefit_appliances;
        $benefit_foot = $json->benefit_foot;
        $benefit_medicine_animal = $json->benefit_medicine_animal;
        $benefit_medicine_human = $json->benefit_medicine_human;
        $benefit_pesticide = $json->benefit_pesticide;
        $height = $json->height;
        $name_adder = $json->name_adder;
        $other = $json->other;
        $age_adder = $json->age_adder;
        $address_adder = $json->address_adder;
        $about_tradition = $json->about_tradition;
        $about_religion = $json->about_religion;
        $age = $json->age;
        $user_id = $json->user_id;
        $currentTime = new DateTime();
        $createdAt = $currentTime->format('Y-m-d H:i:s');
        $sql = $this->db->prepare("
            SELECT COUNT(*) as total FROM tb_plant
            ");
        $sql->execute(array());
        $total = $sql->fetchAll(PDO::FETCH_ASSOC);
        $total = $total[0]['total'];
        $pid = 0;
        if (intval($total) <= 0) {
            $pid = 1;
        } else {
            $pid = intval($total) + 1;
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
                values(
                '$pid',
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
                '$zipcode',
                '$age',
                '$girth',
                '$height',
                '$status',
                '$benefit_foot',
                '$benefit_medicine_human',
                '$benefit_medicine_animal',
                '$benefit_appliances',
                '$benefit_pesticide',
                '$about_tradition',
                '$about_religion',
                '$other',
                '$name_adder',
                '$age_adder',
                '$address_adder',
                '$createdAt',
                '$user_id','$qty')
            ");

        //  $data  = $sqladd->fetchAll(PDO::FETCH_ASSOC);
        if ($sqladd->execute(array())) {
            $array = [
                "mes" => "success",
                "val" => $pid,
            ];
            echo json_encode($array, JSON_PRETTY_PRINT);
        } else {
            $array = [
                "mes" => "error",
                "val" => $pid,
            ];
            echo json_encode($array, JSON_PRETTY_PRINT);
        }
    }
    public function uploadImage()
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
    // public function uploadOtherImage()
    // {
    //     $name = $_REQUEST['name'];
    //     $plantid = $_REQUEST['plant_id'];
    //     $user_id = $_REQUEST['user_id'];
    //     // $file_name = $_FILES['file']['name'];
    //     // $file_size =$_FILES['file']['size'];
    //     // $file_tmp =$_FILES['file']['tmp_name'];
    //     $file_type = $_FILES['file']['type'];
    //     $currentTime = new DateTime();
    //     $createdAt = $currentTime->format('Y-m-d H:i:s');
    //     $filename = "public/uploadimg/";
    //     if (!file_exists($filename)) {
    //         mkdir("public/uploadimg/", 0777);
    //     }
    //     if ($file_type === "image/png" || $file_type === "image/jpg" || $file_type === "image/jpeg") {
    //         $files_upload = basename($_FILES["file"]["name"]);
    //         $imageFileType = strtolower(pathinfo($files_upload, PATHINFO_EXTENSION));
    //         $delete = $filename . "/$name.png";
    //         $delete1 = $filename . "/$name.jpeg";
    //         $delete2 = $filename . "/$name.jpg";
    //         if (file_exists($delete)) {
    //             unlink($delete);
    //         }
    //         if (file_exists($delete1)) {
    //             unlink($delete1);
    //         }
    //         if (file_exists($delete2)) {
    //             unlink($delete2);
    //         }
    //         if (move_uploaded_file($_FILES["file"]["tmp_name"], $filename . "$name." . $imageFileType)) {
    //             $img = strval($filename . "$name." . $imageFileType);
    //             // $imgc = strval($filename . "$name");
    //             $sqll = $this->db->prepare("
    //             SELECT COUNT(*) AS t FROM tb_plant_img WHERE image_name LIKE '%$name%'
    //             ");
    //             $sqll->execute(array());
    //             $total = $sqll->fetchAll(PDO::FETCH_ASSOC);
    //             $total = $total[0]['t'];
    //             if (intval($total) !== 0) {
    //                 $sqld = $this->db->prepare("
    //                 UPDATE tb_plnant_img SET user_id = '$user_id',image_date = CURRENT_TIMESTAMP() WHERE image_name = '$img'
    //                 ");
    //                 if ($sqld->execute(array())) {
    //                     echo json_encode("success", JSON_PRETTY_PRINT);
    //                 } else {
    //                     echo json_encode("error", JSON_PRETTY_PRINT);
    //                 }
    //             } else {
    //                 $sqlmaxid = $this->db->prepare("
    //                 SELECT COUNT(*) total FROM tb_plant_img
    //                 ");
    //                 $sqlmaxid->execute(array());
    //                 $total = $sqlmaxid->fetchAll(PDO::FETCH_ASSOC);
    //                 $total = $total[0]['total'];
    //                 $mid = 0;
    //                 if (intval($total) <= 0) {
    //                     $mid = 1;
    //                 } else {
    //                     $mid = intval($total) + 1;
    //                 }
    //                 $sqlimg = $this->db->prepare("
    //                 INSERT INTO tb_plant_img VALUES('$mid','$img','$plantid','$user_id',CURRENT_TIMESTAMP())
    //                 ");
    //                 if ($sqlimg->execute(array())) {
    //                     echo json_encode("success", JSON_PRETTY_PRINT);
    //                 } else {
    //                     echo json_encode("error", JSON_PRETTY_PRINT);
    //                 }
    //             }
    //         }
    //     } else {
    //         echo json_encode("error file type", JSON_PRETTY_PRINT);
    //     }
    // }
    public function getPlant()
    {
        $json = json_decode(file_get_contents("php://input"));
        $sql = $this->db->prepare("
        SELECT *,(SELECT C.image_name FROM tb_plant_img AS C WHERE C.plant_id = A.plant_id ORDER BY C.img_id DESC LIMIT 1) AS img FROM `tb_plant` AS A
        ");
        $sql->execute(array());
        $data = $sql->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($data, JSON_PRETTY_PRINT);
    }
    public function detailPlant()
    {
        $json = json_decode(file_get_contents("php://input"));
        $id = $json->id;
        $sql = $this->db->prepare("
        SELECT *,(SELECT name_th FROM provinces WHERE id = A.province) AS province,
        (SELECT name_th FROM amphures WHERE id = A.amphure) AS amphur,
        (SELECT name_th FROM districts WHERE id = A.tumbol) AS tumbol,
        (SELECT C.image_name FROM tb_plant_img AS C WHERE C.plant_id = A.plant_id ORDER BY C.img_id DESC LIMIT 1) AS img,(SELECT name FROM tb_user WHERE user_id = A.user_id) AS useredit FROM `tb_plant` AS A WHERE plant_id = '$id';
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
        $plant_name = $json->plant_name;
        $plant_code = $json->plant_code;
        $plant_character = $json->plant_character;
        $distinctive = $json->distinctive;
        $area = $json->area;
        $lacate_x = $json->lacate_x;
        $locate_y = $json->locate_y;
        // $tumbol = $json->tumbol;
        // $amphure = $json->amphure;
        // $province = $json->province;
        $age = $json->age;
        $girth = $json->girth;
        $height = $json->height;
        $statuss = $json->statuss;
        $benefit_foot = $json->benefit_foot;
        $benefit_medicine_human = $json->benefit_medicine_human;
        $benefit_medicine_animal = $json->benefit_medicine_animal;
        $benefit_appliances = $json->benefit_appliances;
        $benefit_pesticide = $json->benefit_pesticide;
        $about_tradition = $json->about_tradition;
        $about_religion = $json->about_religion;
        $other = $json->other;
        $name_adder = $json->name_adder;
        $age_adder = $json->age_adder;
        $address_adder = $json->address_adder;
        $user_id = $json->user_id;
        $qty = $json->qty;
        $currentTime = new DateTime();
        $createdAt = $currentTime->format('Y-m-d H:i:s');
        $sql = $this->db->prepare("
        UPDATE tb_plant SET plant_name = '$plant_name',plant_code = '$plant_code',plant_character='$plant_character',
        distinctive='$distinctive',area='$area',lacate_x='$lacate_x',locate_y='$locate_y',
      age='$age',girth='$girth',height='$height',statuss='$statuss',benefit_foot='$benefit_foot',
        benefit_medicine_human='$benefit_medicine_human',benefit_medicine_animal='$benefit_medicine_animal',benefit_appliances='$benefit_appliances',
        benefit_pesticide='$benefit_pesticide',about_tradition='$about_tradition',about_religion='$about_religion',other='$other',name_adder='$name_adder',
        age_adder='$age_adder',address_adder='$address_adder',date_add='$createdAt',user_id='$user_id',qty='$qty' WHERE plant_id = '$plant_id'
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
        $id = $json->id;
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
}
