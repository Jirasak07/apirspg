<?php
    
    header("Content-Type: application/json; charset=UTF-8");
    if(!isset($headers['Authorization'])){
        $arr = array(
            'status' => 401,
            'status_name' => header_status(401)
        );
	    http_response_code(401);
    }
    
    echo json_encode($arr);
?>