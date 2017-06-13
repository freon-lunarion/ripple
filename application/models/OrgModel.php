<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class OrgModel extends CI_Model{

  public function __construct()
  {
    parent::__construct();
    $this->load->model('BaseModel');

    //Codeigniter : Write Less Do More
  }

  public function ChangeChiefPost($orgId,$newPost=0,$validOn='',$endDate='9999-12-31')
  {
    $this->BaseModel->ChangeRel('TOPDOWN','202',$orgId,$newPost,$validOn,$endDate);
  }

  public function ChangeName($orgId=0,$newName='',$validOn='',$endDate='9999-12-31')
  {
    $this->BaseModel->ChangeName($orgId,$newName,$validOn,$endDate);
  }

  public function ChangeParent($orgId=0,$newParent=0,$validOn='',$endDate='9999-12-31')
  {
    $this->BaseModel->ChangeRel('BOTUP','101',$orgId,$newParent,$validOn,$endDate);
  }

  public function ChangeRelDate($relId=0,$beginDate='',$endDate='')
  {
    $this->BaseModel->ChangeRelDate($relId,$beginDate,$endDate);
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

  public function CountChildrenOrg($orgId=0,$keyDate='')
  {
    return $this->BaseModel->CountTopDownRel($orgId,'101',$keyDate);
  }

  public function CountParentOrg($orgId=0,$keyDate='')
  {
    return $this->BaseModel->CountBotUpRel($orgId,'101',$keyDate);
  }

  public function CountPerson($orgId=0,$keyDate='')
  {
    $relCode = array('201','301');
    return $this->BaseModel->CountTopDownRel($orgId,$relCode,$keyDate);
  }

  public function CountPost($orgId=0,$keyDate='')
  {
    return $this->BaseModel->CountTopDownRel($orgId,'201',$keyDate);
  }

  public function Create($name='',$beginDate='1990-01-01',$endDate='9999-12-31',$parentOrg=0)
  {
    $orgId = $this->BaseModel->Create('ORG',$name,$beginDate,$endDate);

    $this->BaseModel->CreateRel('101',$parentOrg,$orgId,$beginDate,$endDate);

    return $orgId;
  }

  public function Delete($orgId=0)
  {
    $this->BaseModel->Delete($orgId);
  }

  public function DeleteRel($relId=0)
  {
    $this->BaseModel->DeleteRel($relId);
  }

  public function Delimit($orgId=0,$endDate='')
  {
    $this->BaseModel->Delimit($orgId,$endDate);
  }

  public function GetByIdRow($id=0)
  {
    return $this->BaseModel->GetByIdRow($id);
  }

  public function GetChiefPersonList($orgId=0,$keyDate='')
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

  public function GetChiefPostList($orgId=0,$keyDate='')
  {
    return $this->BaseModel->GetTopDownRelList($orgId,'202',$keyDate,'post');
  }

  public function GetChildrenOrgList($orgId=0,$keyDate='')
  {
    return $this->BaseModel->GetTopDownRelList($orgId,'101',$keyDate,'child');
  }
  public function GetLastChiefPerson($orgId=0,$keyDate='')
  {
    $relCode = array('202','301');
    $alias   = array('post','person');
    $count   = $this->BaseModel->CountTopDownRel($orgId,$relCode,$keyDate);
    while ($count == 0) {
      $parent = $this->GetParentOrg($orgId,$keyDate,'parent');
      $orgId  = $parent->parent_id;
      $count  = $this->BaseModel->CountTopDownRel($orgId,$relCode,$keyDate);

    }
    return $this->BaseModel->GetLastTopDownRel($orgId,$relCode,$keyDate,$alias);

  }

  public function GetLastChiefPost($orgId=0,$keyDate='')
  {
    return $this->BaseModel->GetLastTopDownRel($orgId,'202',$keyDate,'post');
  }

  public function GetLastName($orgId=0,$keyDate='')
  {
    return $this->BaseModel->GetLastAttr($orgId,$keyDate);
  }

  public function GetList($beginDate='1990-01-01',$endDate='9999-12-31')
  {
    $keydate['begin'] = $beginDate;
    $keydate['end']   = $endDate;
    return $this->BaseModel->GetList('ORG',$keydate);
  }

  public function GetNameHistoryList($orgId=0,$keyDate='')
  {
    return $this->BaseModel->GetAttrList($orgId,$keyDate);
  }

  public function GetParentOrg($orgId=0,$keyDate='')
  {
    return $this->BaseModel->GetLastBotUpRel($orgId,'101',$keyDate,'parent');
  }

  public function GetParentOrgList($orgId=0,$keyDate='')
  {
    return $this->BaseModel->GetBotUpRelList($orgId,'101',$keyDate,'parent');
  }

  public function GetPersonList($orgId=0,$keyDate='')
  {
    $relCode = array('201','301');
    $alias   = array('post','person');
    return $this->BaseModel->GetTopDownRelList($orgId,$relCode,$keyDate,$alias);

  }

  public function GetPostList($orgId=0,$keyDate='')
  {
    return $this->BaseModel->GetTopDownRelList($orgId,'201',$keyDate,'post');

  }

  public function GetRelByIdRow($relId=0)
  {
    return $this->BaseModel->GetRelById($relId);
  }

  public function GetStruct($objId=0,$keydate=array())
  {
    $obj  = $this->GetByIdRow($objId,$keydate);
    $attr = $this->GetLastName($objId,$keydate);
    if ($objId > 0) {
      $result[0] = array(
        'id'    => $obj->id,
        'name'  => $attr->name,
      );
      while ($this->CountParentOrg($objId,$keydate)) {
        $parent = $this->GetParentOrg($objId,$keydate);
        $result[] = array(
          'id'    => $parent->parent_id,
          'name'  => $parent->parent_name,
        );
        $objId  = $parent->parent_id;
      }
    }
    $result[] = array(
      'id'    => 0,
      'name'  => 'ROOT',
    );

    return array_reverse($result);
  }
}
