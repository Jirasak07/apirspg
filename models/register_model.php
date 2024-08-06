<?php
header("Content-Type: application/json; charset=UTF-8");

// use PHPMailer\PHPMailer\PHPMailer;
// use PHPMailer\PHPMailer\Exception;

// require 'vendor/autoload.php';

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
        try {
            // date_default_timezone_set('Asia/Bangkok');
            // $alert_sentmail = null;
            $mail = new PHPMailer(true);
            $mail->isSMTP();
            $mail->SMTPDebug = 0;
            $mail->Debugoutput = 'html';
            $mail->CharSet = "utf-8";
            // $mail->Host = "mail.rspg-kpppao.com";
            $mail->Host = "s054ns1.hostinghispeed.com";
            $mail->SMTPSecure = 'tls'; // Enable TLS encryption, `ssl` also accepted
            $mail->Port = 587;
            $mail->SMTPAuth = true;
            $mail->Username = "kamphaengphet.pao@rspg-kpppao.com";
            $mail->Password = "Merlin162990.";
            $mail->setFrom('kamphaengphet.pao@rspg-kpppao.com', 'kamphaengphet.pao@rspg-kpppao.com');
            $mail->addAddress('bee.110243@gmail.com', 'Recipient'); // Add a recipient

            // Content
            $mail->isHTML(true); // Set email format to HTML
            $mail->Subject = 'ยืนยันการสมัครสมาชิกระบบจัดเก็บพันธุกรรมพืช : องค์การบริหารส่วนจังหวัดกำแพงเพชร';
            $mail->Body    = "Click on the link to verify your account: <a href='http://localhost/backend/verify.php?token=$token'>Verify Account</a>";
            $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

            $mail->send();
            echo 'Message has been sent';
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    }
}

// Usage
// $registerModel = new Register_model();
// $registerModel->register();



//
		
// mail.iue.co.th