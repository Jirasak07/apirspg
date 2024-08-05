<?php
class news extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }
    public function GetNews()
    {
        $this->model->GetNews();
    }
    public function GetEditNews()
    {
        $this->model->GetEditNews();
    }
    public function InsertNews()
    {
        $this->model->InsertNews();
    }
    public function upload()
    {
        $this->model->upload();
    }
    public function SaveEditNews()
    {
        $this->model->SaveEditNews();

    }
    public function DeleteNews()
    {
        $this->model->DeleteNews();

    }
}
