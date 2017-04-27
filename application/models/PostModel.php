<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class PostModel extends CI_Model{

  public function __construct()
  {
    parent::__construct();
    //Codeigniter : Write Less Do More
    $this->load->model('BaseModel');
  }

  public function Create($name='',$beginDate='1990-01-01',$endDate='9999-12-31-31',$orgId=0,$reportTo=0,$isChief=FALSE)
  {
    $postId = $this->BaseModel->Create('POS',$name,$beginDate,$endDate);

    $this->BaseModel->CreateRel('102',$reportTo,$postId,$beginDate,$endDate);
    $this->BaseModel->CreateRel('201',$orgId,$postId,$beginDate,$endDate);
    if ($isChief) {
      $this->BaseModel->CreateRel('202',$orgId,$postId,$beginDate,$endDate);
    }

    return $postId;
  }

  public function ChangeName($postId = 0, $newName='',$validOn='',$endDate='9999-12-31')
  {
    $this->BaseModel->ChangeName($postId,$newName,$validOn,$endDate);
  }

  public function ChangeOrg($postId=0,$newOrg=0,$validOn='',$endDate='9999-12-31')
  {
    $this->BaseModel->ChangeRel('BotUp','201',$postId,$newOrg,$validOn,$endDate);

  }

  public function ChangeSuperior($postId=0,$newPost=0,$validOn='',$endDate='9999-12-31')
  {
    $this->BaseModel->ChangeRel('BotUp','102',$postId,$newOrg,$validOn,$endDate);
  }

  public function ChangeHolder($postId=0,$newPersId=0,$validOn='',$endDate='9999-12-31')
  {
    $this->BaseModel->ChangeRel('TopDown','301',$postId,$newPersId,$validOn,$endDate);
  }

  public function Delimit($postId=0,$endDate='')
  {
    $this->BaseModel->Delimit($postId,$endDate);
  }

  public function Delete($postId=0)
  {
    $this->BaseModel->Delete($postId);
  }

  public function GetLastName($postId=0,$keyDate='')
  {
    return $this->BaseModel->GetLastAttr($postId,$keyDate);
  }

  public function GetNameHistoryList($postId=0,$keyDate='')
  {
    return $this->BaseModel->GetAttrList($postId,$keyDate);
  }

  public function CountHolder($postId=0,$keyDate='')
  {
    return $this->BaseModel->CountTopDownRel($postId,'301',$keyDate);

  }

  public function GetLastHolder($postId=0,$keyDate='')
  {
    return $this->BaseModel->GetLastBotUpRel($postId,'301',$keyDate,'person');

  }

  public function GetHolderHistoryList($postId=0,$keyDate='')
  {
    return $this->BaseModel->GetBotUpRelList($postId,'301',$keyDate,'person');
  }

  public function CountSuperiorPost($postId=0,$keyDate='')
  {
    return $this->BaseModel->CountBotUpRel($postId,'102',$keyDate);
  }

  public function CountSubordinatePost($postId=0,$keyDate='')
  {
    return $this->BaseModel->CountTopDownRel($postId,'102',$keyDate);
  }

  public function CountPeerPost($postId=0,$keyDate='')
  {
    $chiefId = $this->GetSuperiorPost($postId,$keyDate)->post_id;
    return   ($this->BaseModel->CountTopDownRel($chiefId,'102',$keyDate) - 1);

  }

  public function CountSuperiorPerson($postId=0,$keyDate='')
  {
    $relCode = array('102','301');
    return $this->BaseModel->CountBotUpRel($postId,$relCode,$keyDate);

  }

  public function CountSubordinatePerson($postId=0,$keyDate='')
  {
    $relCode = array('102','301');
    return $this->BaseModel->CountTopDownRel($postId,$relCode,$keyDate);

  }

  public function CountPeerPerson($postId=0,$keyDate='')
  {
    $chiefId = $this->GetSuperiorPost($postId,$keyDate)->post_id;

    $relCode = array('102','301');
    return ($this->BaseModel->CountTopDownRel($postId,$relCode,$keyDate) - 1);

  }

  public function GetSuperiorPost($postId=0,$keyDate='')
  {
    return $this->BaseModel->GetLastBotUpRel($postId,'102',$keyDate,'post');
  }

  public function GetSubordinatePostList($postId=0,$keyDate='')
  {
    $this->BaseModel->GetTopDownRelList($postId,'102',$keyDate,'post');
  }

  public function GetPeerPostList($postId=0,$keyDate='')
  {
    $chiefId = $this->GetSuperiorPost($postId,$keyDate)->post_id;

    $list = $this->BaseModel->GetTopDownRelList($chiefId,'102',$keyDate,'post');

    $result = array();
    foreach ($list as $row) {
      if ($row->post_id != $postId) {
        $result[] = $row;
      }
    }
    return $result;

  }

  public function GetSuperiorPerson($postId=0,$keyDate='')
  {
    $relCode = array('102','301');
    $alias   = array('post','person');
    $count   = $this->BaseModel->CountBotUpRel($postId,$relCode,$keyDate);
    while ($count == 0) {
      $postId = $this->GetSuperiorPost($postId,$keyDate)->post_id;
      $count   = $this->BaseModel->CountBotUpRel($postId,$relCode,$keyDate);
    }

    return $this->BaseModel->GetLastBotUpRel($postId,$relCode,$keyDate,$alias);

  }

  public function GetSubordinatePersonList($postId=0,$keyDate='')
  {
    $relCode = array('102','301');
    $alias   = array('post','person');
    return $this->BaseModel->GetTopDownRelList($postId,$relCode,$keyDate,$alias);

  }

  public function GetPeerPersonList($postId=0,$keyDate='')
  {
    $chiefId = $this->GetSuperiorPost($postId,$keyDate)->post_id;
    $relCode = array('102','301');
    $alias   = array('post','person');

    $list = $this->BaseModel->GetTopDownRelList($chiefId,$relCode,$keyDate,$alias);

    $result = array();
    foreach ($list as $row) {
      if ($row->post_id != $postId) {
        $result[] = $row;
      }
    }
    return $result;

  }

}
