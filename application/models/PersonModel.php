<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class PersonModel extends CI_Model{

  public function __construct()
  {
    parent::__construct();
    //Codeigniter : Write Less Do More
    $this->load->model('BaseModel');
  }

  public function GetList($beginDate='1990-01-01',$endDate='9999-12-31')
  {
    $keydate['begin'] = $beginDate;
    $keydate['end']   = $endDate;
    return $this->BaseModel->GetList('EMP',$keydate);
  }

  public function Create($name='',$postId=0,$beginDate='',$endDate='9999-12-31')
  {
    if ($beginDate == '') {
      $beginDate = date('Y-m-d');
    }
    $persId = $this->BaseModel->Create('EMP',$name,$beginDate,$endDate);

    $this->BaseModel->CreateRel('301',$postId,$persId,$beginDate,$endDate);

  }

  public function ChangeName($persId=0,$newName='',$validOn='',$endDate='9999-12-31')
  {
    $this->BaseModel->ChangeName($persId,$newName,$validOn,$endDate);

  }

  public function Delimit($persId=0,$endDate='')
  {
    $this->BaseModel->Delimit($persId,$endDate);
  }

  public function Delete($persId=0)
  {
    $this->BaseModel->Delete($persId);
  }

  public function CountPost($persId=0,$keyDate='')
  {
    return $this->BaseModel->CountBotUpRel($persId,'301',$keyDate);
  }

  public function GetPostList($persId=0,$keyDate='')
  {
    return $this->BaseModel->GetBotUpRelList($persId,'301',$keyDate,'post');
  }

  public function AssignPost($persId=0,$postId=0,$beginDate='',$endDate='9999-12-31')
  {
    if ($beginDate == '') {
      $beginDate = date('Y-m-d');
    }
    $this->BaseModel->CreateRel('301',$postId,$persId,$beginDate,$endDate);
  }

  public function ReleasePost($relId,$endDate='')
  {
    if ($endDate == '') {
      $endDate = date('Y-m-d');
    }

    $this->BaseModel->DelimitOn($this->BaseModel->tblRel,$relId,$endDate);
  }

}
