<?php
class News extends Controller
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
    public function AddNews()
    {
        $this->model->AddNews();
    }
    public function getNew()
    {
        $this->model->getNew();
    }
    public function getEditNews()
    {
        $this->model->getEditNews();
    }
    public function EditNews()
    {
        $this->model->EditNews();
    }
    public function getNewTable()
    {
        $this->model->getNewTable();
    }
    public function AddActiv()
    {
        $this->model->AddActiv();
    }
    public function AddImgNews()
    {
        $this->model->AddImgNews();
    }
    public function DeleteActiv()
    {
        $this->model->DeleteActiv();
    }
    public function getActivity()
    {
        $this->model->getActivity();
    }
    public function getImgActiv()
    {
        $this->model->getImgActiv();
    }
    public function DetailActiv()
    {
        $this->model->DetailActiv();
    }
}
