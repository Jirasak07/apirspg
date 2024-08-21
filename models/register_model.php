<?php
header("Content-Type: application/json; charset=UTF-8");
$templatePath = __DIR__ . '/template_register.html';
// use PHPMailer\PHPMailer\PHPMailer;
// use PHPMailer\PHPMailer\Exception;

// require 'vendor/autoload.php';

class Register_model extends Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function register()
    {
        $json = json_decode(file_get_contents("php://input"));
        $email = $json->email;
        $name = $json->name;
        $organization = $json->organization;
        $tell_number = $json->tell_number;
        $citizen_id = $json->citizen_id;
        $password = password_hash($json->password, PASSWORD_BCRYPT);
        $token = bin2hex(random_bytes(16));

        // Check for duplicate email, username, or citizen_id
        $checkSql = "SELECT COUNT(*) FROM tb_user WHERE email = :email OR citizen_id = :citizen_id";
        $checkStmt = $this->db->prepare($checkSql);
        $checkStmt->bindParam(':email', $email);
        $checkStmt->bindParam(':citizen_id', $citizen_id);

        try {
            $checkStmt->execute();
            $count = $checkStmt->fetchColumn();

            if ($count > 0) {
                echo json_encode("have", JSON_PRETTY_PRINT);
                return;
            }
            $timestamp = date("Y-m-d H:i:s");
            $sql = "
        INSERT INTO tb_user
        (
            email,
            password,
            name,
            organization,
            user_role,
            tell_number,
            status,
            citizen_id,
            token,
            confirmed,
            date
        ) VALUES (
            :email,
            :password,
            :name,
            :organization,
            '2',
            :tell_number,
            '1',
            :citizen_id,
            :token,
            '0',
            $timestamp
        )";

            $stmt = $this->db->prepare($sql);

            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':password', $password);
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':organization', $organization);
            $stmt->bindParam(':tell_number', $tell_number);
            $stmt->bindParam(':citizen_id', $citizen_id);
            $stmt->bindParam(':token', $token);

            $stmt->execute();
            $this->sendVerificationEmail($email, $token);
        } catch (PDOException $e) {
            echo json_encode(['message' => 'Error registering user: ' . $e->getMessage()]);
        }
    }
    public function generateEmailContent($token)
    {
        // <h1 class="text-center text-info">Hello, ' . htmlspecialchars($name) . '!</h1>
        //   href="https://www.rspg-kppao.com/apirspg/register/' . $token . '"
        $html = '
<html lang="en">
  <body style="padding: 0px; margin: 0px; width: 70vw;">
    <div style="margin: 0px auto; width: 500px">
      <div
        style="
          background-image: url(https://www.rspg-kpppao.com/apirspg/public/images/regist.png);
          width: 500px;
          height: 400px;
        "
      ></div>
      <a
        href="https://www.rspg-kpppao.com/apirspg/register/verify/' . $token . '"
        style="
          display: inline-block;
          text-align: center;
          text-decoration: none;
          width: 500px;
          height: 50px;
          line-height: 50px;
          border: none;
          font-size: 20px;
          font-weight: 700;
          color: white;
          background-color: #208b3a;
          cursor: pointer;
        "
      >
        คลิกเพื่อยืนยันการสมัคร
      </a>
    </div>
  </body>
</html>

';
        return $html;
    }
    private function sendVerificationEmail($email, $token)
    {
        try {
            $body = $this->generateEmailContent($token);
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
            $mail->addAddress($email, 'Recipient'); // Add a recipient

            // Content
            $mail->isHTML(true); // Set email format to HTML
            $mail->Subject = 'องค์การบริหารส่วนจังหวัดกำแพงเพชร';
            $mail->Body = $body;
            $mail->send();
            echo json_encode('success', JSON_PRETTY_PRINT);
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    }
    public function verify($token)
    {
        // if (isset($_GET['token'])) {
        //     $token = $_GET['token'];

        // Prepare the SQL statement to find the user with the provided token
        $sql = "SELECT * FROM tb_user WHERE token = :token";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':token', $token);

        try {
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user) {
                // User found, update the status to 'confirmed'
                $updateSql = "UPDATE tb_user SET confirmed = '1', token = NULL WHERE token = :token";
                $updateStmt = $this->db->prepare($updateSql);
                $updateStmt->bindParam(':token', $token);
                $updateStmt->execute();
                // header("Location: https://www.rspg-kpppao.com/login");
                header("Location: https://www.rspg-kpppao.com/ver");
                exit();
            } else {
                // Token is invalid or expired
                header("Location: https://www.rspg-kpppao.com/login");
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
        // } else {
        //     echo "No token provided.";
        // }
    }

    public function ChangePassword()
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $email = $_POST['email'];
            // ตรวจสอบว่ามีอีเมลนี้ในฐานข้อมูลหรือไม่
            $sql = "SELECT * FROM tb_user WHERE email = :email";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user) {
                // สร้าง token ใหม่
                $token = bin2hex(random_bytes(16));

                // บันทึก token ลงฐานข้อมูล
                $updateSql = "UPDATE tb_user SET token = :token WHERE email = :email";
                $updateStmt = $this->db->prepare($updateSql);
                $updateStmt->bindParam(':token', $token);
                $updateStmt->bindParam(':email', $email);
                $updateStmt->execute();

                // ส่งอีเมลรีเซ็ตรหัสผ่าน
                $mail = new PHPMailer(true);
                try {
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
                    $mail->addAddress($email, 'Recipient'); // Add a recipient

                    // Content
                    $mail->isHTML(true); // Set email format to HTML
                    $mail->Subject = 'องค์การบริหารส่วนจังหวัดกำแพงเพชร';
                    //   href="https://www.rspg-kpppao.com/formchange/' . $token . '"
                    $mail->Body = '
                    <html lang="en">
  <body style="padding: 0px; margin: 0px; width: 70vw;">
    <div style="margin: 0px auto; width: 500px">
      <a
                          href="https://www.rspg-kpppao.com/formchange/' . $token . '"
        style="
          display: inline-block;
          text-align: center;
          text-decoration: none;
          width: 500px;
          height: 50px;
          line-height: 50px;
          border: none;
          font-size: 20px;
          font-weight: 700;
          color: white;
          background-color: #208b3a;
          cursor: pointer;
        "
      >
        คลิกเพื่อเปลี่ยนรหัสผ่าน
      </a>
    </div>
  </body>
</html>';

                    $mail->send();
                    echo json_encode("success", JSON_PRETTY_PRINT);
                } catch (Exception $e) {
                    echo "Error sending email: " . $mail->ErrorInfo;
                }
            } else {
                echo "Email not found.";
            }
        }
    }
    public function savenewpass()
    {
        $token = $_POST['token'];
        $newPassword = password_hash($_POST['password'], PASSWORD_BCRYPT);

        // ตรวจสอบ token และอัพเดตรหัสผ่าน
        $sql = "SELECT * FROM tb_user WHERE token = :token";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':token', $token);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            // อัพเดตรหัสผ่านใหม่และลบ token
            $updateSql = "UPDATE tb_user SET password = :password, token = NULL WHERE token = :token";
            $updateStmt = $this->db->prepare($updateSql);
            $updateStmt->bindParam(':password', $newPassword);
            $updateStmt->bindParam(':token', $token);
            $updateStmt->execute();
            echo json_encode("success", JSON_PRETTY_PRINT);
            exit();
        } else {
            echo "Invalid or expired token.";
        }
    }
}
