<?php
	class Plant extends Controller {
	    function __construct(){
	    		parent::__construct();
			}
			
	    function index(){
			$this->view->render('Plant/index');
	    }
        function getUser(){
            $this->model->getUser();
        }
        function SelectProvince(){
            $this->model->SelectProvince();
        }
        function SelectAmphur(){
            $this->model->SelectAmphur();
        }
        function SelectTambon(){
            $this->model->SelectTambon();
        }
        function ZipCode(){
            $this->model->ZipCode();
        }
        function AddPlant(){
            $this->model->AddPlant();
        }
        function uploadImage(){
            $this->model->uploadImage();
        }
        function updateImage(){
            $this->model->updateImage();
        }
        function getPlant(){
            $this->model->getPlant();
        }
        function Search(){
            $this->model->Search();
        }
        function detailPlant(){
            $this->model->detailPlant();
        }
        function Print(){
            $this->model->Print();
        }
        function PlantEdit(){
            $this->model->PlantEdit();
        }
        function ShowImage(){
            $this->model->ShowImage();
        }
	}
?>