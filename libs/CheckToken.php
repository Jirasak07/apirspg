<?php
    function GenarateTokenSecret($username){
        $key = "PGMSKPP2023";
        $token = array(
            "iat" => time(),
            "exp" => time()+(60*60*24*365),
            "userid" => $username
        );
        $jwt = Firebase\JWT\JWT::encode($token, $key);
        return $jwt;
    }
    
    function GenarateToken($username){
        $key = "PGMSKPP2023";
        $token = array(
            "iat" => time(),
            "exp" => time()+(60*60*60),
            "userid" => $username
        );

        $jwt = Firebase\JWT\JWT::encode($token, $key);
        return $jwt;
    }


    function CheckToken($Token){
        $key = "PGMSKPP2023";
        try{
            $token = (array)Firebase\JWT\JWT::decode($Token, $key, array('HS256'));
            
            return $token;
        }
        catch (\Exception $e){
            http_response_code(401);
        }
        

    }



?>