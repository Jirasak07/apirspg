<?php
header("Access-Control-Allow-Origin: http://localhost:3000"); // แก้ไข URL ตามโดเมนของแอป React ของคุณ
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
        ini_set('display_errors', 1);
        ini_set('memory_limit', '256M'); 

        require 'libs/Bootstrap.php';
    	require 'libs/Controller.php';
    	require 'libs/Model.php';
    	require 'libs/View.php';
    	require 'libs/Database.php';
    	require 'libs/Session.php';
		require 'libs/ResponeCode.php';
		require 'libs/SignatureCode.php';
		require 'libs/CheckToken.php';

		require 'libs/Uuid.php';
        
        require 'config/paths.php';
		require 'config/database.php';
		define ('FPDF_FONTPATH','public/fpdf/font/');

        // require 'public/jwt/JWT.php';
		include_once 'public/jwt/BeforeValidException.php';
		include_once 'public/jwt/ExpiredException.php';
		include_once 'public/jwt/SignatureInvalidException.php';
		include_once 'public/jwt/JWT.php';


	$app = new Bootstrap();

?>
