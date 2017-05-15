<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class OrgModel extends CI_Model{

  public function __construct()
  {
    parent::__construct();
    $this->load->model('BaseModel');

    //Codeigniter : Write Less Do More
  }
  public function GetList($beginDate='1990-01-01',$endDate='9999-12-31')
  {
    $keydate['begin'] = $beginDate;
    $keydate['end']   = $endDate;
    return $this->BaseModel->GetList('ORG',$keydate);
  }

  public function Create($name='',$beginDate='1990-01-01',$endDate='9999-12-31',$parentOrg=0)
  {
    $orgId = $this->BaseModel->Create('ORG',$name,$beginDate,$endDate);

    $this->BaseModel->CreateRel('101',$parentOrg,$orgId,$beginDate,$endDate);

    return $orgId;
  }

  public function ChangeName($orgId=0,$newName='',$validOn='',$endDate='9999-12-31')
  {
    $this->BaseModel->ChangeName($orgId,$newName,$validOn,$endDate);
  }

  public function ChangeParent($orgId=0,$newParent=0,$validOn='',$endDate='9999-12-31')
  {
    $this->BaseModel->ChangeRel('BotUp','101',$orgId,$newParent,$validOn,$endDate);
  }

  public function ChangeChiefPost($orgId,$newPost=0,$validOn='',$endDate='9999-12-31')
  {
    $this->BaseModel->ChangeRel('TopDown','201',$orgId,$newPost,$validOn,$endDate);
  }

  public function Delimit($orgId=0,$endDate='')
  {
    $this->BaseModel->Delimit($orgId,$endDate);
  }

  public function Delete($orgId=0)
  {
    $this->BaseModel->Delete($orgId);
  }

  public function GetLastName($orgId=0,$keyDate='')
  {
    return $this->BaseModel->GetLastAttr($orgId,$keyDate);
  }

  public function GetNameHistoryList($orgId=0,$keyDate='')
  {
    return $this->BaseModel->GetAttrList($orgId,$keyDate);
  }

  public function CountParentOrg($orgId=0,$keyDate='')
  {
    return $this->BaseModel->CountBotUpRel($orgId,'101',$keyDate);
  }

  public function CountChildrenOrg($orgId=0,$keyDate='')
  {
    return $this->BaseModel->CountTopDownRel($orgId,'101',$keyDate);
  }

  public function CountPost($orgId=0,$keyDate='')
  {
    return $this->BaseModel->CountTopDownRel($orgId,'201',$keyDate);
  }

  public function CountPerson($orgId=0,$keyDate='')
  {
    $relCode = array('201','301');
    return $this->BaseModel->CountTopDownRel($orgId,$relCode,$keyDate);
  }

  public function CountChiefPost($orgId=0,$keyDate='')
  {
    return $this->BaseModel->CountTopDownRel($orgId,'201',$keyDate);
  }

  public function CountChiefPerson($orgId=0,$keyDate='')
  {
    $relCode = array('202','301');
    return $this->BaseModel->CountTopDownRel($orgId,$relCode,$keyDate);

  }

  public function GetParentOrg($orgId=0,$keyDate='')
  {
    return $this->BaseModel->GetLastBotUpRel($orgId,'101',$keyDate,'parent');
  }

  public function GetParentOrgList($orgId=0,$keyDate='')
  {
    $result = array();
    $count = $this->CountParentOrg($orgId,$keyDate);
    while ($count > 0 ) {
      $row      = $this->GetParentOrg($orgId,$keyDate);
      $result[] = $row;
      $orgId    = $this->parent_id;
      $count    = $this->CountParentOrg($orgId,$keyDate);
    }

    return $result;
  }

  public function GetChildrenOrgList($orgId=0,$keyDate='')
  {
    return $this->BaseModel->GetTopDownRelList($orgId,'101',$keyDate);
  }

  public function GetPostList($orgId=0,$keyDate='')
  {
    return $this->BaseModel->GetTopDownRelList($orgId,'201',$keyDate,'Post');

  }

  public function GetPersonList($orgId=0,$keyDate='')
  {
    $relCode = array('201','301');
    $alias   = array('post','person');
    return $this->BaseModel->GetTopDownRelList($orgId,$relCode,$keyDate,$alias);

  }

  public function GetChiefPost($orgId=0,$keyDate='')
  {
    return $this->BaseModel->GetTopDownRelList($orgId,'202',$keyDate,'Post');
  }

  public function GetChiefPerson($orgId=0,$keyDate='')
  {
    $relCode = array('202','301');
    $alias   = array('post','person');
    $count   = $this->BaseModel->CountTopDownRel($orgId,$relCode,$keyDate);
    while ($count == 0) {
      $parent = $this->GetParentOrg($orgId,$keyDate,'parent');
      $orgId  = $parent->parent_id;
      $count  = $this->BaseModel->CountTopDownRel($orgId,$relCode,$keyDate);

    }
    return $this->BaseModel->GetTopDownRelList($orgId,$relCode,$keyDate,$alias);

  }
}
