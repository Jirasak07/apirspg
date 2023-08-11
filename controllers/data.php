<?php
    $api_url = 'https://raw.githubusercontent.com/kongvut/thai-province-data/master/api_province_with_amphure_tambon.json';

    // สร้างการเชื่อมต่อด้วย cURL
    $ch = curl_init($api_url);
    
    // กำหนดการตั้งค่า cURL
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    
    // ส่งคำขอและรับข้อมูลจาก API
    $response = curl_exec($ch);
    
    // ปิดการเชื่อมต่อ cURL
    curl_close($ch);
    
    // แปลงข้อมูล JSON ที่ได้รับเป็นอาร์เรย์หรือออบเจ็กต์
    $data = json_decode($response, true);
 echo json_encode($data,JSON_PRETTY_PRINT);
?>