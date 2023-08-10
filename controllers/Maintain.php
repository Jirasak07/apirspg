<?php
class Maintain extends Controller
{
    function __construct()
    {
        parent::__construct();
    }

    function CheckMaintain($id)
    {
        $this->model->CheckMaintain($id);
    }

    function SelectYearMaintain($id)
    {
        $this->model->SelectYearMaintain($id);
    }

    function SelectStudent($id)
    {
        $this->model->SelectStudent($id);
    }

    function SelectMaintain($id)
    {
        $this->model->SelectMaintain($id);
    }

    function InsertMaintain($id)
    {
        $this->model->InsertMaintain($id);
    }

    function EditMaintain($id)
    {
        $this->model->EditMaintain($id);
    }

    function CancelMaintain($id)
    {
        $this->model->CancelMaintain($id);
    }

    function SelectMaintainTeacher($id)
    {
        $this->model->SelectMaintainTeacher($id);
    }

    function SelectMaintainTeacherhistory($id)
    {
        $this->model->SelectMaintainTeacherhistory($id);
    }

    function UpdateStatusTeacher($id, $id1)
    {
        $this->model->UpdateStatusTeacher($id, $id1);
    }

    function SelectMaintainTabian()
    {
        $this->model->SelectMaintainTabian();
    }

    function SelectMaintainTabianhistory()
    {
        $this->model->SelectMaintainTabianhistory();
    }

    function UpdateStatusTabian($id, $id1)
    {
        $this->model->UpdateStatusTabian($id, $id1);
    }

    function SelectMaintainVicerector()
    {
        $this->model->SelectMaintainVicerector();
    }

    function UpdateStatusVicerector($id)
    {
        $this->model->UpdateStatusVicerector($id);
    }

    function UpdateStatusVicerectorAll($id)
    {
        $this->model->UpdateStatusVicerectorAll($id);
    }

    function ReportStatusMaintain()
    {
        $this->model->ReportStatusMaintain();
    }

    function ReportMaintain($typestudent, $term, $year)
    {
        $this->model->ReportMaintain($typestudent, $term, $year);
    }

    function ReportMaintainID($id, $term, $year)
    {
        $this->model->ReportMaintainID($id, $term, $year);
    }

    function PaymentMaintain($id)
    {
        $this->model->PaymentMaintain($id);
    }

    function SelectYearReport()
    {
        $this->model->SelectYearReport();
    }

    function PrintStatus($id, $term, $year){
        $this->model->PrintStatus($id, $term, $year);
    }

    function NotifyStudent(){
        $this->model->NotifyStudent();
    }
    function NotifyTeacher(){
        $this->model->NotifyTeacher();
    }
    function StatMaintain()
    {
        $this->model->StatMaintain();
    }
}
