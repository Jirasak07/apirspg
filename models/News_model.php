<?php
header("Content-Type: application/json; charset=UTF-8");
class news_Model extends Model
{
    public function __construct()
    {
        parent::__construct();
    }
    public function GetNews()
    {
        $sql = $this->db->prepare("
			SELECT * FROM tb_news
			");
        $sql->execute(array());
        $data = $sql->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($data, JSON_PRETTY_PRINT);
    }

    public function upload()
    {
        $uploadDir = 'public/uploads/news/';
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
                $filePath = 'public/uploads/news/' . $newFileName;
                echo json_encode(['status' => 'success', 'path' => $filePath]);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Failed to move uploaded file.']);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'No file uploaded.']);
        }
    }

    public function InsertNews()
    {
        $json = json_decode(file_get_contents("php://input"));
        $url_news = $json->url_news;
        $news_title = $json->news_title;
        $news_detail = $json->news_detail;
        $image_news = $json->image_news;
        $sqlinsert = $this->db->prepare("
        INSERT INTO tb_news(news_title,news_detail,image_news,url_news)
        VALUES('$news_title',:DETAIL,'$image_news','$url_news')
        ");
        if ($sqlinsert->execute(array(":DETAIL" => $news_detail)) === true) {
            echo json_encode("success", JSON_PRETTY_PRINT);
        } else {
            $error = $sqlinsert->errorInfo();
            echo json_encode($error[2], JSON_PRETTY_PRINT);
        }
    }

    public function GetEditNews()
    {
        $json = json_decode(file_get_contents("php://input"));
        $news_id = $json->news_id;
        $sql = $this->db->prepare("
			SELECT * FROM tb_news WHERE news_id = '$news_id'
			");
        $sql->execute(array());
        $data = $sql->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($data, JSON_PRETTY_PRINT);
    }

    function SaveEditNews()
    {
        $json = json_decode(file_get_contents("php://input"));
        $url_news = $json->url_news;
        $news_title = $json->news_title;
        $news_detail = $json->news_detail;
        $news_id = $json->news_id;
        $sqlupdate = $this->db->prepare("
    UPDATE tb_news SET news_title ='$news_title', news_detail = :DETAIL,url_news = '$url_news' WHERE news_id = '$news_id'
    ");
        if ($sqlupdate->execute(array(":DETAIL" => $news_detail)) === true) {
            echo json_encode("success", JSON_PRETTY_PRINT);
        } else {
            echo json_encode("error", JSON_PRETTY_PRINT);
        }
    }

    public function DeleteNews()
    {
        $json = json_decode(file_get_contents("php://input"));
        $news_id = $json->news_id;
        $sql = $this->db->prepare("
        SELECT * FROM tb_news WHERE news_id = '$news_id'
        ");
        $sql->execute(array());
        $data = $sql->fetchAll(PDO::FETCH_ASSOC);
        $path = $data[0]["image_news"];
        if (file_exists($path)) {
            if (unlink($path)) {
            }
        }
        $sqlinsert = $this->db->prepare("
        DELETE FROM tb_news WHERE news_id = '$news_id'
        ");
        if ($sqlinsert->execute() === true) {
            echo json_encode("success", JSON_PRETTY_PRINT);
        } else {
            echo json_encode("error", JSON_PRETTY_PRINT);
        }
    }
}
