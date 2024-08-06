<?php
header("Content-Type: application/json; charset=UTF-8");
class User_model extends Model
{
    public function __construct()
    {
        parent::__construct();
    }
    public function AddUser()
    {
        $json = json_decode(file_get_contents("php://input"));
        $password = $json->password;
        $name = $json->name;
        $organize = $json->organize;
        $tell = $json->tell;
        $citizen = $json->citizen;
        $email = $json->email;
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);
        $sqlchkusername = $this->db->prepare("
            SELECT COUNT(*) AS row FROM tb_user WHERE  citizen_id = '$citizen' OR email = '$email'
             ");
        $sqlchkusername->execute(array());
        $datachk = $sqlchkusername->fetchAll(PDO::FETCH_ASSOC);
        $datachk = intval($datachk[0]['row']);
        if ($datachk === 0) {
            $sqlAdd = $this->db->prepare("
            INSERT INTO tb_user(email,password,name,organization,user_role,tell_number,citizen_id,status,confirmed) VALUES('$email','$hashed_password','$name','$organize','2','$tell','$citizen','1','1')
            ");
            if ($sqlAdd->execute()) {
                echo json_encode("success", JSON_PRETTY_PRINT);
            } else {
                echo json_encode("error", JSON_PRETTY_PRINT);
            }
        } else {
            echo json_encode("error", JSON_PRETTY_PRINT);
        }
    }
    public function Login()
    {
        $json = json_decode(file_get_contents("php://input"));
        $username = $json->username;
        $password = $json->password;
        $sql = $this->db->prepare("
            SELECT * FROM tb_user WHERE (username = '$username' OR email = '$username' ) AND status = '1' AND confirmed ='1'
            ");
        $sql->execute(array());
        $row = $sql->fetchAll(PDO::FETCH_ASSOC);
        if (COUNT($row) === 1) {
            $stored_password = $row[0]['password'];
            // ทำการเปรียบเทียบรหัสผ่านที่ผู้ใช้ป้อนกับรหัสผ่านที่เก็บในฐานข้อมูล
            if (password_verify($password, $stored_password)) {
                $token = GenarateToken($username);
                $arr = [
                    "message" => "success",
                    "data" => $token,
                    "user_id" => $row,
                ];
                // รหัสผ่านถูกต้อง
                echo json_encode($arr, JSON_PRETTY_PRINT);
            } else {
                // รหัสผ่านไม่ถูกต้อง
                echo json_encode("error", JSON_PRETTY_PRINT);
            }
        } else {
            //ไม่พบผู้ใช้
            echo json_encode("info", JSON_PRETTY_PRINT);
        }
    }
    public function checkLogin()
    {
        $json = json_decode(file_get_contents("php://input"));
        $token = $json->token;
        $res = CheckToken($token);
        echo json_encode($res, JSON_PRETTY_PRINT);
    }
    public function getUser()
    {
        $sql = $this->db->prepare("
            SELECT * FROM tb_user WHERE  confirmed ='1'
            ");
        $sql->execute(array());
        $data = $sql->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($data, JSON_PRETTY_PRINT);
    }
    public function EditRole()
    {
        $json = json_decode(file_get_contents("php://input"));
        $id = $json->id;
        $status = $json->status;
        $sql = $this->db->prepare("
            UPDATE tb_user SET status = $status WHERE user_id = $id
            ");
        $sql->execute(array());
        echo json_encode("success", JSON_PRETTY_PRINT);
    }
    public function EditAuth()
    {
        $json = json_decode(file_get_contents("php://input"));
        $id = $json->id;
        $user_role = $json->user_role;
        $sql = $this->db->prepare("
            UPDATE tb_user SET user_role = $user_role WHERE user_id = $id
            ");
        $sql->execute(array());
        echo json_encode("success", JSON_PRETTY_PRINT);
    }
    public function ShowProfile()
    {
        $json = json_decode(file_get_contents("php://input"));
        $id = $json->id;
        $sqls = $this->db->prepare("
            SELECT * FROM tb_user  WHERE user_id = $id AND confirmed ='1'
            ");
        $sqls->execute(array());
        $data = $sqls->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($data, JSON_PRETTY_PRINT);
    }
    public function EditProfile()
    {
        $json = json_decode(file_get_contents("php://input"));
        $email = $json->email;
        $name = $json->name;
        $organize = $json->organization;
        $tell_number = $json->tell_number;
        $citizen = $json->citizen_id;
        $user_id = $json->user_id;
        $password = $json->password;

        // Fetch the current user's information
        $sql = $this->db->prepare("
        SELECT * FROM tb_user WHERE user_id = :user_id AND status = '1' AND confirmed = '1'
        ");
        $sql->bindParam(':user_id', $user_id);
        $sql->execute();
        $row = $sql->fetchAll(PDO::FETCH_ASSOC);

        if (COUNT($row) === 1) {
            $stored_password = $row[0]['password'];
            // Verify the provided password
            if (password_verify($password, $stored_password)) {
                // Check for duplicate email, username, or citizen_id
                $checkSql = $this->db->prepare("
                SELECT COUNT(*) FROM tb_user
                WHERE (email = :email  OR citizen_id = :citizen_id)
                AND user_id != :user_id
                ");
                $checkSql->bindParam(':email', $email);
                $checkSql->bindParam(':citizen_id', $citizen);
                $checkSql->bindParam(':user_id', $user_id);
                $checkSql->execute();
                $count = $checkSql->fetchColumn();

                if ($count > 0) {
                    echo json_encode(['message' => 'have']);
                    return;
                }

                // Update the user's information
                $updateSql = $this->db->prepare("
                UPDATE tb_user
                SET

                    name = :name,
                    organization = :organization,
                    tell_number = :tell_number,
                    citizen_id = :citizen_id,
                    email = :email
                WHERE user_id = :user_id
                ");
                $updateSql->bindParam(':name', $name);
                $updateSql->bindParam(':organization', $organize);
                $updateSql->bindParam(':tell_number', $tell_number);
                $updateSql->bindParam(':citizen_id', $citizen);
                $updateSql->bindParam(':email', $email);
                $updateSql->bindParam(':user_id', $user_id);
                $updateSql->execute();
                echo json_encode(['message' => 'success'], JSON_PRETTY_PRINT);
            } else {
                // Incorrect password
                echo json_encode(['message' => 'error'], JSON_PRETTY_PRINT);
            }
        } else {
            // User not found
            echo json_encode(['message' => 'info'], JSON_PRETTY_PRINT);
        }
    }

    public function ChangePass()
    {
        $json = json_decode(file_get_contents("php://input"));
        $id = $json->id;
        $newpass = $json->newpass;
        $oldpass = $json->oldpass;
        // $stored_password = $oldpass;
        // ทำการเปรียบเทียบรหัสผ่านที่ผู้ใช้ป้อนกับรหัสผ่านที่เก็บในฐานข้อมูล
        $sql = $this->db->prepare("
            SELECT * FROM tb_user WHERE user_id = '$id'
            ");
        $sql->execute(array());
        $row = $sql->fetchAll(PDO::FETCH_ASSOC);
        if (COUNT($row) === 1) {
            $stored_password = $row[0]['password'];
            // ทำการเปรียบเทียบรหัสผ่านที่ผู้ใช้ป้อนกับรหัสผ่านที่เก็บในฐานข้อมูล
            if (password_verify($oldpass, $stored_password)) {
                $hashed_password = password_hash($newpass, PASSWORD_BCRYPT);
                $sql = $this->db->prepare("
                UPDATE tb_user SET password = '$hashed_password' WHERE user_id = '$id' AND confirmed ='1'
                ");
                $sql->execute(array());
                echo json_encode("success", JSON_PRETTY_PRINT);
            } else {
                // รหัสผ่านไม่ถูกต้อง
                echo json_encode("error", JSON_PRETTY_PRINT);
            }
        } else {
            //ไม่พบผู้ใช้
            echo json_encode("info", JSON_PRETTY_PRINT);
        }
    }
}
