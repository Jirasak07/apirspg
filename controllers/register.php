<?php
class register extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        header("Content-Type: application/json; charset=UTF-8");
        $arr = array(
            'status' => 200,
            'status_name' => header_status(200),
            'data' => array(
                'success' => "ไม่อนุญาตให้เข้าใช้งานส่วนใดของระบบ",
            ),
        );
        echo json_encode($arr);
        http_response_code(200);
    }
    public function register()
    {
        $this->model->register();
    }
    public function verify($token)
    {
        $this->model->verify($token);
    }
    public function ChangePassword()
    {
        $this->model->ChangePassword();
    }
    public function savenewpass()
    {
        $this->model->savenewpass();
    }
}
