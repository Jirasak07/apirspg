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
    public function generateEmailContent($name, $token)
    {
        // <h1 class="text-center text-info">Hello, ' . htmlspecialchars($name) . '!</h1>
        $html = '
       <!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>HTML + CSS</title>
    <link rel="stylesheet" href="styles.css" />
  </head>
  <style>
    @import url("https://fonts.googleapis.com/css2?family=Sarabun:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800&display=swap");
    * {
      font-family: "Sarabun" !important;
    }
    button {
      position: relative;
      font-size: 1.2em;
      padding: 0.7em 1.4em;
      background-color: #578160;
      text-decoration: none;
      border: none;
      border-radius: 0.5em;
      color: #ffffff;
      box-shadow: 0.5em 0.5em 0.5em rgba(0, 0, 0, 0.3);
    }

    button::before {
      position: absolute;
      content: "";
      height: 0;
      width: 0;
      top: 0;
      left: 0;
      background: linear-gradient(
        135deg,
        rgb(255, 255, 255) 0%,
        rgb(255, 255, 255) 50%,
        rgb(25, 88, 51) 50%,
        rgb(22, 119, 74) 60%
      );
      border-radius: 0 0 0.5em 0;
      box-shadow: 0.2em 0.2em 0.2em rgba(0, 0, 0, 0.3);
      transition: 0.3s;
    }

    button:hover::before {
      width: 1.6em;
      height: 1.6em;
    }

    button:active {
      box-shadow: 0.2em 0.2em 0.3em rgb(255, 255, 255);
      transform: translate(0.1em, 0.1em);
    }
  </style>
  <body
    style="
      background-color: #578160;
      padding: 50px 50px 50px 50px;
      max-width: 450px;
    "
  >
    <div
      style="
        display: flex;
        flex-direction: column;
        align-items: center;
        padding-top: 30px;
        background-color: #ffffff;
        padding-bottom: 30px;
        box-shadow: rgba(50, 50, 93, 0.25) 0px 50px 100px -20px,
          rgba(0, 0, 0, 0.3) 0px 30px 60px -30px;
      "
    >
      <img src="https://www.rspg-kpppao.com/apirspg/public/images/logobtm.png"
      width=120px" />
      <h1
        style="
          text-decoration: none;
          line-height: 1;
          padding: 0;
          margin: 0;
          color: #2a9d8f;
          padding-top: 15px;
          font-size: 18px;
        "
      >
        ยืนยันการสมัครสมาชิก
      </h1>
      <h2
        style="
          margin-top: 12px;
          text-decoration: none;
          line-height: 2;
          padding: 0;
          margin: 0;
          font-size: 16px;
          justify-content: center;
          color: #5a189a;
        "
      >
        รบบจัดเก็บพันธุกรรมพืช
      </h2>
      <h2
        style="
          text-decoration: none;
          line-height: 1;
          padding: 0;
          margin: 0;
          font-size: 16px;
          justify-content: center;
          color: #5a189a;
        "
      >
        องค์การบริหารจังหวัดกำแพงเพชร
      </h2>
      <div style="margin-top: 20px">
        <button style="cursor: pointer">
          <b>คลิกเพื่อยืนยันการสมัคร</b>
        </button>
      </div>
    </div>
  </body>
</html>
';
        return $html;
    }
    private function sendVerificationEmail($email, $token)
    {
        try {
            $body = $this->generateEmailContent("่รหา่ก้ดาห่ก", $token);
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
            $mail->Subject = 'ยืนยันการสมัครสมาชิกระบบจัดเก็บพันธุกรรมพืช : องค์การบริหารส่วนจังหวัดกำแพงเพชร';
            $mail->Body = $body;
            $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

            $mail->send();
            echo json_encode('Send success', JSON_PRETTY_PRINT);
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
                header("Location: https://www.rspg-kpppao.com/login");
                exit();
            } else {
                // Token is invalid or expired
                echo "Invalid or expired token.";
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
        // } else {
        //     echo "No token provided.";
        // }
    }
}

// Usage
// $registerModel = new Register_model();
// $registerModel->register();

//

// mail.iue.co.th
