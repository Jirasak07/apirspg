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
            $url = $_REQUEST['url'];
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
                    INSERT INTO tb_news VALUES('$mid','$name','$url','$img',CURRENT_TIMESTAMP(),'$enddate')
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
        function getNewTable(){
            $sql = $this->db->prepare("
            SELECT * FROM tb_news ORDER BY news_end DESC;
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
        
        function AddActiv(){
            $ac_title = $_REQUEST['title'];
            $ac_detail = $_REQUEST['ac_detail'];
            $ac_file = $_FILES['ac_file'];
            $user_id = $_REQUEST['user_id'];
            $file_type=$_FILES['ac_file']['type'];  
            $path2save = 'public/uploadfileac/';
            if (!file_exists($path2save)) {
                mkdir("public/uploadfileac/", 0777);
        } 
            if($file_type === 'application/pdf'){
                    $files_upload = basename($_FILES["ac_file"]["name"]);
                    $imageFileType = strtolower(pathinfo($files_upload,PATHINFO_EXTENSION));
                if(move_uploaded_file($_FILES["ac_file"]["tmp_name"], $path2save."$ac_title.".$imageFileType)){
                    $pathfile = strval($path2save."$ac_title.".$imageFileType);
                    $sqlchkid = $this->db->prepare("
                        SELECT COUNT(*) AS TOTAL FROM tb_activty 
                    ");
                    $sqlchkid->execute(array());
                    $id = $sqlchkid->fetchAll(PDO::FETCH_ASSOC);
                    $id = intval($id[0]['TOTAL']);
                    if($id <=0){
                        $acid = 1;
                    }else{
                        $acid = $id+1;
                    }
                    $sqladd = $this->db->prepare("
                    INSERT INTO tb_activty VALUES('$acid','$ac_title','$ac_detail',CURRENT_TIMESTAMP(),'$pathfile','$user_id')
                    ");
                   if($sqladd->execute(array()) === true ){
                    $array=[
                        "message"=>"success",
                        "data"=>$acid
                    ];
                    echo json_encode($array,JSON_PRETTY_PRINT);
                   }else{
                    echo json_encode('error',JSON_PRETTY_PRINT);
                   }
            }else{
                echo json_encode("รองรับไฟล์เอกสารเฉพาะ pdf เท่านั้น",JSON_PRETTY_PRINT);
                }
                }
                }        
function AddImgNews(){
    $ac_id = $_REQUEST['ac_id'];
    $image = $_FILES['img'];
    $file_type=$_FILES['img']['type'];  
    $path2save = 'public/uploadimg/imgactiv/';
    if (!file_exists($path2save)) {
        mkdir("public/uploadfileac/imgactiv/", 0777);
} 
if( $file_type === "image/png" || $file_type === "image/jpg" || $file_type === "image/jpeg"){
    $files_upload = basename($_FILES["ac_file"]["name"]);
    $imageFileType = strtolower(pathinfo($files_upload,PATHINFO_EXTENSION));
    if(move_uploaded_file($_FILES["img"]["tmp_name"], $path2save."$ac_id.".$imageFileType)){
        $pathfile = strval($path2save."$ac_id.".$imageFileType);
        $sqlchkid = $this->db->prepare("
        SELECT COUNT(*) AS TT FROM tb_activty_img
        ");
        $sqlchkid->execute(array());
        $data = $sqlchkid->fetchAll(PDO::FETCH_ASSOC);
        $id = $data[0]['TT'];
        if(intval($id) <= 0){
            $imid = 1;
        }else{
            $imid = intval($id)+1;
        }
        $sqlad = $this->db->prepare("
        INSERT INTO tb_activty_img VALUES('$imid','$ac_id',$pathfile)
        ");
        $sqlad->execute(array());
        echo json_encode("success",JSON_PRETTY_PRINT);
    }

}else{
    echo json_encode("errorfile",JSON_PRETTY_PRINT);
}


}
}                  
?>