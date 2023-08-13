<?php
 	header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Origin: http://localhost:3000");
	class News_model extends Model{
		function __construct(){
				parent::__construct();
		}
		function AddNews(){
            $name = $_REQUEST['title'];
            $enddate = $_REQUEST['enddate'];
            $file_type=$_FILES['file']['type'];   
            $currentTime = new DateTime();
            $createdAt = $currentTime->format('Y-m-d H:i:s');
            $filename = "public/newsupload/";
            if (!file_exists($filename)) {
                    mkdir("public/newsupload/", 0777);
            } 
            if( $file_type === "image/png" || $file_type === "image/jpg" || $file_type === "image/jpeg"){
                $files_upload = basename($_FILES["file"]["name"]);
                $imageFileType = strtolower(pathinfo($files_upload,PATHINFO_EXTENSION));
                $delete = $filename."/$name.".$imageFileType;
                if(file_exists($delete)){
                     unlink($delete); 
                } 
                if(move_uploaded_file($_FILES["file"]["tmp_name"], $filename."$name.".$imageFileType)){
                    $img = strval($filename."$name.".$imageFileType);
                    $sqlmaxid = $this->db->prepare("
                    SELECT COUNT(*) total FROM tb_news
                    ");
                    $sqlmaxid->execute(array());
                    $total = $sqlmaxid->fetchAll(PDO::FETCH_ASSOC);
                    $total = $total[0]['total'];
                    $mid = 0;
                    if(intval($total) <= 0){
                        $mid = 1;
                    }else{
                        $mid = intval($total) +1;
                    }
                    $sqlimg = $this->db->prepare("
                    INSERT INTO tb_news VALUES('$mid','$name','-','$img',CURRENT_TIMESTAMP(),'$enddate')
                    ");
                    
                    if($sqlimg->execute(array())){
                        echo json_encode("success",JSON_PRETTY_PRINT);
                    }else{
                        echo json_encode("error",JSON_PRETTY_PRINT);
                    }
            }
        }else{
            echo json_encode("error file type",JSON_PRETTY_PRINT);
        }
        }
        function getNew(){
            $sql = $this->db->prepare("
            SELECT * FROM tb_news WHERE CURDATE() < news_end;
            ");
            $sql->execute(array());
            $data = $sql->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode($data,JSON_PRETTY_PRINT);
        }
        function getEditNews(){
            $json = json_decode(file_get_contents("php://input"));
            $news_id = $json->news_id;
            $sql = $this->db->prepare("
            SELECT * FROM tb_news WHERE news_id = '$news_id';
            ");
            $sql->execute(array());
            $data = $sql->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode($data,JSON_PRETTY_PRINT);
        }
        function EditNews(){
            $json = json_decode(file_get_contents("php://input"));
            $news_id = $json->news_id;
            $news_title = $json->news_title;
            $url_news = $json->url_news;
            $news_end = $json->news_end;
            $sqlUpdate = $this->db->prepare("
            UPDATE tb_news SET news_title = '$news_title' ,url_news = '$url_news',news_end = '$news_end' WHERE  news_id = '$news_id'
            ");
            $sqlUpdate->execute(array());
            echo json_encode("success",JSON_PRETTY_PRINT);
        }
}            
                     
?>