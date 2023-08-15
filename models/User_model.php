<?php
 	header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Origin: http://localhost:3000");
	class User_model extends Model{
		function __construct(){
				parent::__construct();
		}
		function AddUser(){
            $json = json_decode(file_get_contents("php://input"));
            $password = $json->password;
            $name = $json->name;
            $username = $json->username;
            $organize = $json->organize;
            $tell = $json->tell;
            $citizen = $json->citizen;
            $hashed_password = password_hash($password, PASSWORD_BCRYPT);
            $sqlchkusername = $this->db->prepare("
            SELECT COUNT(*) AS row FROM tb_user WHERE username = '$username'
             ");
             $sqlchkusername->execute(array());
             $datachk = $sqlchkusername->fetchAll(PDO::FETCH_ASSOC);
             $datachk = intval($datachk[0]['row']);
             if($datachk ===0){
            $sql = $this->db->prepare("
            SELECT MAX(user_id) AS total FROM tb_user
            ");
            $sql->execute(array());
            $data = $sql->fetchAll(PDO::FETCH_ASSOC);
            $id = 0;
            $data = intval($data[0]['total']);
            if($data <= 0 ){
                $id = 1;
            }else{
                $id = $data +1;
            }
            $sqlAdd = $this->db->prepare("
            INSERT INTO tb_user VALUES('$id','$username','$hashed_password','$name','$organize','2','$tell','$citizen','1')
            ");
            if($sqlAdd->execute()){
                echo json_encode("success",JSON_PRETTY_PRINT);
            }else{
                echo json_encode("error",JSON_PRETTY_PRINT);
            }
             }else{
                echo json_encode("error",JSON_PRETTY_PRINT);
             }
          
            // $token =  GenarateToken($data[0]['username']);
            // echo json_encode($token,JSON_PRETTY_PRINT);
        }	
        function Login(){
            $json = json_decode(file_get_contents("php://input"));
            $username = $json->username;
            $password = $json->password;
            $sql = $this->db->prepare("
            SELECT * FROM tb_user WHERE username = '$username' AND status = '1'
            ");
            $sql->execute(array());
            $row = $sql->fetchAll(PDO::FETCH_ASSOC);
            if(COUNT($row)=== 1){
                $stored_password = $row[0]['password']; 
                   // ทำการเปรียบเทียบรหัสผ่านที่ผู้ใช้ป้อนกับรหัสผ่านที่เก็บในฐานข้อมูล
             if (password_verify($password, $stored_password)) {
                $token = GenarateToken($username);
                $arr = [
                    "message"=>"success",
                    "data"=> $token,
                    "user_id"=>$row
                ];
        // รหัสผ่านถูกต้อง
                 echo json_encode($arr,JSON_PRETTY_PRINT);
                } else {
                    // รหัสผ่านไม่ถูกต้อง
                    echo json_encode("error",JSON_PRETTY_PRINT);
                        }
                                    }else{
                                        //ไม่พบผู้ใช้
                                        echo json_encode("info",JSON_PRETTY_PRINT);
                                    }
        }
        function checkLogin(){
            $json = json_decode(file_get_contents("php://input"));
            $token = $json->token;
            $res = CheckToken($token);
                echo json_encode($res,JSON_PRETTY_PRINT);
           
        }
        function getUser(){
            $sql= $this->db->prepare("
            SELECT * FROM tb_user
            ");
            $sql->execute(array());
            $data = $sql->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode($data,JSON_PRETTY_PRINT);
        }
        function EditRole(){
            $json = json_decode(file_get_contents("php://input"));
            $id = $json->id;
            $status = $json->status;
            $sql = $this->db->prepare("
            UPDATE tb_user SET status = $status WHERE user_id = $id
            ");
            $sql->execute(array());
            echo json_encode("success",JSON_PRETTY_PRINT);
        }
        function EditAuth(){
            $json = json_decode(file_get_contents("php://input"));
            $id = $json->id;
            $user_role = $json->user_role;
            $sql = $this->db->prepare("
            UPDATE tb_user SET user_role = $user_role WHERE user_id = $id
            ");
            $sql->execute(array());
           echo json_encode("success",JSON_PRETTY_PRINT);
        }
        function ShowProfile(){
            $json = json_decode(file_get_contents("php://input"));
            $id = $json->id;
            $sqls = $this->db->prepare("
            SELECT * FROM tb_user  WHERE user_id = $id
            ");
            $sqls->execute(array());
            $data = $sqls->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode($data,JSON_PRETTY_PRINT);
        }
        function EditProfile(){
            $json = json_decode(file_get_contents("php://input"));
            $username = $json->username;
            $name = $json->name;
            $organize = $json->organize;
            $tell_number = $json->tell_number;
            $citizen = $json->citizen;
            $id = $json->id;
            $sql = $this->db->prepare("
            UPDATE tb_user SET username = '$username',name = '$name',organization = '$organize',tell_number='$tell_number',citizen_id='$citizen' WHERE user_id = '$id'
            ");
            $sql->execute(array());
            echo json_encode('success',JSON_PRETTY_PRINT);
        }
        function ChangePass(){
            $json = json_decode(file_get_contents("php://input"));
            $newname = $json->username;
            $id=$json->id;
            $newpass=$json->newpass;
            $oldpass =$json->oldpass;
            // $stored_password = $oldpass;
            // ทำการเปรียบเทียบรหัสผ่านที่ผู้ใช้ป้อนกับรหัสผ่านที่เก็บในฐานข้อมูล
            $sql = $this->db->prepare("
            SELECT * FROM tb_user WHERE user_id = '$id'
            ");
            $sql->execute(array());
            $row = $sql->fetchAll(PDO::FETCH_ASSOC);
            if(COUNT($row)=== 1){
                $stored_password = $row[0]['password']; 
                   // ทำการเปรียบเทียบรหัสผ่านที่ผู้ใช้ป้อนกับรหัสผ่านที่เก็บในฐานข้อมูล
             if (password_verify($oldpass, $stored_password)) {
                $hashed_password = password_hash($newpass, PASSWORD_BCRYPT);
                $sql = $this->db->prepare("
                UPDATE tb_user SET username = '$newname', password = '$hashed_password' WHERE user_id = '$id'
                ");
                $sql->execute(array());
                echo json_encode("success",JSON_PRETTY_PRINT);
                } else {
                    // รหัสผ่านไม่ถูกต้อง
                    echo json_encode("error",JSON_PRETTY_PRINT);
                        }
                                    }else{
                                        //ไม่พบผู้ใช้
                                        echo json_encode("info",JSON_PRETTY_PRINT);
                                    }
        }
}            
                     
?>