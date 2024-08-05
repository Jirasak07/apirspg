<?php
class Plant extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $this->view->render('Plant/index');
    }
    public function getUser()
    {
        $this->model->getUser();
    }
    public function SelectProvince()
    {
        $this->model->SelectProvince();
    }
    public function SelectAmphur()
    {
        $this->model->SelectAmphur();
    }
    public function SelectTambon()
    {
        $this->model->SelectTambon();
    }
    public function ZipCode()
    {
        $this->model->ZipCode();
    }
    public function AddPlant()
    {
        $this->model->AddPlant();
    }
    public function uploadImage()
    {
        $this->model->uploadImage();
    }
    public function updateImage()
    {
        $this->model->updateImage();
    }
    public function getPlant()
    {
        $this->model->getPlant();
    }
    public function Search()
    {
        $this->model->Search();
    }
    public function detailPlant()
    {
        $this->model->detailPlant();
    }
    public function detailPlants()
    {
        $this->model->detailPlants();
    }
    public function GetPlants()
    {
        $this->model->GetPlants();
    }
    public function print()
    {
        $this->model->Print();
    }
    public function PlantEdit()
    {
        $this->model->PlantEdit();
    }
    public function ShowImage()
    {
        $this->model->ShowImage();
    }
    public function ShowImagePdf()
    {
        $this->model->ShowImagePdf();
    }
    public function upload()
    {
        $this->model->upload();
    }
    public function uploads()
    {
        $this->model->uploads();
    }
    public function GetPlantLocate()
    {
        $this->model->GetPlantLocate();
    }
}
