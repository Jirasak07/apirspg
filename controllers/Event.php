<?php
class Event extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }
    public function GetEvent()
    {
        $this->model->GetEvent();
    }
    public function GetEditEvent()
    {
        $this->model->GetEditEvent();
    }
    public function InsertEvent()
    {
        $this->model->InsertEvent();
    }
    public function upload()
    {
        $this->model->upload();
    }
    public function SaveEditEvent()
    {
        $this->model->SaveEditEvent();
    }
    public function DeleteEvent()
    {
        $this->model->DeleteEvent();
    }
    public function GetIMG()
    {
        $this->model->GetIMG();
    }
    public function GetEventForMain()
    {
        $this->model->GetEventForMain();
    }
    public function GetEventForMainDetail()
    {
        $this->model->GetEventForMainDetail();
    }
}
