<?php
header("Content-Type: application/json; charset=UTF-8");

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

class Register_model extends Model
{
    public function __construct()
    {
        parent::__construct();
    }

    function register()
    {
        $json = json_decode(file_get_contents("php://input"));
        $email = $json->email;
        $username = $json->username;
        $name = $json->name;
        $organization = $json->organization;
        $tell_number = $json->tell_number;
        $citizen_id = $json->citizen_id;
        $password = password_hash($json->password, PASSWORD_BCRYPT);
        $token = bin2hex(random_bytes(16));

        $sql = "
        INSERT INTO tb_user 
        (
            email,
            username,
            password,
            name, 
            organization,
            user_role,
            tell_number,
            status,
            citizen_id,
            token,
            confirmed
        ) VALUES (
            :email,
            :username,
            :password,
            :name, 
            :organization,
            '2',
            :tell_number,
            '1',
            :citizen_id,
            :token,
            '0'
        )";
        
        $stmt = $this->db->prepare($sql);

        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $password);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':organization', $organization);
        $stmt->bindParam(':tell_number', $tell_number);
        $stmt->bindParam(':citizen_id', $citizen_id);
        $stmt->bindParam(':token', $token);

        try {
            $stmt->execute();
            $this->sendVerificationEmail($email, $token);
            // echo json_encode(['message' => 'Verification email sent']);
        } catch (PDOException $e) {
            echo json_encode(['message' => 'Error registering user: ' . $e->getMessage()]);
        }
    }

    private function sendVerificationEmail($email, $token)
    {
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host       = 'rspg-kpppao.com'; // Your SMTP server
            $mail->SMTPAuth   = true;
            $mail->Username   = 'kamphaengphet.pao@rspg-kpppao.com';
            $mail->Password   = 'Merlin162990.';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; // Use SSL/TLS on port 465
            $mail->Port       = 465;
    
            $mail->setFrom('kamphaengphet.pao@rspg-kpppao.com', 'Your Name');
            $mail->addAddress($email);
    
            $mail->isHTML(true);
            $mail->Subject = 'Account Verification';
            $mail->Body    = "Click on the link to verify your account: <a href='http://localhost/backend/verify.php?token=$token'>Verify Account</a>";
    
            $mail->send();
        } catch (Exception $e) {
            echo json_encode(['message' => 'Error sending email: ' . $mail->ErrorInfo]);
        }
    }
}

// Usage
$registerModel = new Register_model();
$registerModel->register();
