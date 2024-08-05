<?php
header("Content-Type: application/json; charset=UTF-8");
class Event_Model extends Model
{
    public function __construct()
    {
        parent::__construct();
    }
    public function GetEvent()
    {
        $sql = $this->db->prepare("
			SELECT * FROM tb_activty 
			");
        $sql->execute(array());
        $data = $sql->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($data, JSON_PRETTY_PRINT);
    }
    public function GetEventForMain()
    {
        $sql = $this->db->prepare("
	SELECT tb_activty.*,(SELECT img_path FROM tb_activty_img WHERE ac_id = tb_activty.ac_id ORDER BY tb_activty_img.ac_img_id ASC  LIMIT 1) as IMG_PATH  FROM tb_activty  ORDER BY tb_activty.ac_date DESC
			");
        $sql->execute(array());
        $data = $sql->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($data, JSON_PRETTY_PRINT);
    }
    public function GetEventForMainDetail()
    {
        $json = json_decode(file_get_contents("php://input"));
        $ac_id = $json->ac_id;
        $sql = $this->db->prepare("
	SELECT *  FROM tb_activty  WHERE tb_activty.ac_id = '$ac_id'
			");

        $sql->execute(array());
        $data = $sql->fetchAll(PDO::FETCH_ASSOC);
        $use = $data;
        $i = 0;
        foreach ($data as $d) {
            $sql = $this->db->prepare("
            SELECT * FROM tb_activty_img WHERE ac_id = '" . $d["ac_id"] . "'
            ");
            $sql->execute(array());
            $img = $sql->fetchAll(PDO::FETCH_ASSOC);
            $use[$i]["DATA_IMG"] = $img;
            $i++;
        }
        echo json_encode($use, JSON_PRETTY_PRINT);
    }

    public function upload()
    {
        $id = $_POST["ac_id"];
        $uploadDir = 'public/uploads/event/EVENT' . $id . '/';
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
                $filePath = 'public/uploads/event/EVENT' . $id . '/' . $newFileName;
                $sqlinset = $this->db->prepare("
                INSERT INTO tb_activty_img(img_path,ac_id) VALUES('$filePath','$id')
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

    public function InsertEvent()
    {
        $json = json_decode(file_get_contents("php://input"));
        $ac_date = $json->ac_date;
        $ac_title = $json->ac_title;
        $ac_detail = $json->ac_detail;
        $user_id = $json->user_id;
        $sqlinsert = $this->db->prepare("
        INSERT INTO tb_activty(ac_title,ac_detail,ac_date,user_id)
        VALUES('$ac_title',:DETAIL,'$ac_date','$user_id')
        ");
        if ($sqlinsert->execute(array(":DETAIL" => $ac_detail)) === true) {
            $sqlgetid = $this->db->prepare("
            SELECT ac_id FROM tb_activty ORDER BY ac_id DESC LIMIT 1
            ");
            $sqlgetid->execute(array());
            $datas = $sqlgetid->fetchAll(PDO::FETCH_ASSOC);
            $id = 0;
            if (count($datas) > 0) {
                $id = intval($datas[0]["ac_id"]);
                $id = $id;
            } else {
                $id = 1;
            }
            $data = array();
            $data[0]["id"] = $id;
            echo json_encode($data, JSON_PRETTY_PRINT);
        } else {
            $error = $sqlinsert->errorInfo();
            echo json_encode($error[2], JSON_PRETTY_PRINT);
        }
    }

    public function GetEditEvent()
    {
        $json = json_decode(file_get_contents("php://input"));
        $ac_id = $json->ac_id;
        $sql = $this->db->prepare("
			SELECT * FROM tb_activty WHERE ac_id = '$ac_id'
			");
        $sql->execute(array());
        $data = $sql->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($data, JSON_PRETTY_PRINT);
    }

    function SaveEditEvent()
    {
        $json = json_decode(file_get_contents("php://input"));
        $ac_title = $json->ac_title;
        $ac_date = $json->ac_date;
        $ac_detail = $json->ac_detail;
        $ac_id = $json->ac_id;
        $sqlupdate = $this->db->prepare("
    UPDATE tb_activty SET ac_title ='$ac_title', ac_detail = :DETAIL,ac_date = '$ac_date' WHERE ac_id = '$ac_id'
    ");
        if ($sqlupdate->execute(array(":DETAIL" => $ac_detail)) === true) {
            echo json_encode("success", JSON_PRETTY_PRINT);
        } else {
            echo json_encode("error", JSON_PRETTY_PRINT);
        }
    }

    public function DeleteEvent()
    {
        $json = json_decode(file_get_contents("php://input"));
        $ac_id = $json->ac_id;
        $sql = $this->db->prepare("
        SELECT * FROM tb_activty_img WHERE ac_id = '$ac_id'
        ");
        $sql->execute(array());
        $data = $sql->fetchAll(PDO::FETCH_ASSOC);
        // $path = $data[0]["EVENT_IMG"];
        foreach ($data as $img) {
            if (file_exists($img["img_path"])) {
                if (unlink($img["img_path"])) {
                }
            }
        }

        $sqlinsert = $this->db->prepare("
        DELETE FROM tb_activty WHERE ac_id = '$ac_id'
        ");
        if ($sqlinsert->execute() === true) {
            $sqlinserts = $this->db->prepare("
            DELETE FROM tb_activty_img WHERE ac_id = '$ac_id'
            ");
            $sqlinserts->execute();
            echo json_encode("success", JSON_PRETTY_PRINT);
        } else {
            echo json_encode("error", JSON_PRETTY_PRINT);
        }
    }
    function GetIMG()
    {
        $json = json_decode(file_get_contents("php://input"));
        $ac_id = $json->ac_id;
        $sql = $this->db->prepare("
        SELECT * FROM tb_activty_img WHERE ac_id = '$ac_id'
        ");
        $sql->execute(array());
        $data = $sql->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($data, JSON_PRETTY_PRINT);
    }
}
