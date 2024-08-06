<?php
class User extends Controller
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
    public function AddUser()
    {
        $this->model->AddUser();
    }
    public function Login()
    {
        $this->model->Login();
    }
    public function checkLogin()
    {
        $this->model->checkLogin();
    }
    public function getUser()
    {
        $this->model->getUser();
    }
    public function EditRole()
    {
        $this->model->EditRole();
    }
    public function EditAuth()
    {
        $this->model->EditAuth();
    }
    public function ShowProfile()
    {
        $this->model->ShowProfile();
    }
    public function EditProfile()
    {
        $this->model->EditProfile();
    }
    public function ChangePass()
    {
        $this->model->ChangePass();
    }
}
