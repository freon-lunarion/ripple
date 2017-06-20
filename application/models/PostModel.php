<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class PostModel extends CI_Model{

  private $objType   = 'POS';
  // Relation Code (Ref to ref_obj_rel)
  private $relStruct = '101';
  private $relReport = '102';
  private $relAssign = '201';
  private $relChief  = '202';
  private $relHold   = '301';
  private $relJob    = '401';

  public function __construct()
  {
    parent::__construct();
    //Codeigniter : Write Less Do More
    $this->load->model('BaseModel');
  }

  public function ChangeAssigmentOrg($postId=0,$newOrg=0,$validOn='',$endDate='9999-12-31')
  {
    $this->BaseModel->ChangeRel('BotUp',$this->relAssign,$postId,$newOrg,$validOn,$endDate);
  }

  public function ChangeHolder($postId=0,$newPersId=FALSE,$validOn='',$endDate='9999-12-31')
  {
    if ($newPersId) {
      $this->BaseModel->ChangeRel('TopDown',$this->relHold,$postId,$newPersId,$validOn,$endDate);
    }
  }

  public function ChangeJob($postId=0,$newJobId=0,$validOn='',$endDate='9999-12-31')
  {
    $this->BaseModel->ChangeRel('BotUp',$this->relJob,$postId,$newPersId,$validOn,$endDate);
  }
  public function ChangeName($postId = 0, $newName='',$validOn='',$endDate='9999-12-31')
  {
    $this->BaseModel->ChangeName($postId,$newName,$validOn,$endDate);
  }

  public function ChangeManagingOrg($postId=0,$newOrg=0,$validOn='',$endDate='9999-12-31')
  {
    $this->BaseModel->ChangeRel('BotUp',$this->relChief,$postId,$newOrg,$validOn,$endDate);
  }

  public function ChangeRelDate($relId=0,$beginDate='',$endDate='')
  {
    $this->BaseModel->ChangeRelDate($relId,$beginDate,$endDate);
  }

  public function ChangeSuperior($postId=0,$newPost=0,$validOn='',$endDate='9999-12-31')
  {
    $this->BaseModel->ChangeRel('BotUp',$this->relReport,$postId,$newOrg,$validOn,$endDate);
  }

  public function CountAssigmentOrg($postId=0,$keyDate='')
  {
    return $this->BaseModel->CountBotUpRel($postId,$this->relChief,$keyDate);
  }

  public function CountHolder($postId=0,$keyDate='')
  {
    return $this->BaseModel->CountTopDownRel($postId,$this->relHold,$keyDate);
  }

  public function CountJob($postId=0,$keyDate='')
  {
    return $this->BaseModel->CountBotUpRel($postId,$this->relJob,$keyDate);
  }

  public function CountPeerPerson($postId=0,$keyDate='')
  {
    $chief = $this->GetLastSuperiorPost($postId,$keyDate);
    if ($chief) {
      $relCode = array($this->relReport,$this->relHold);
      return ($this->BaseModel->CountTopDownRel($chief->post_id,$relCode,$keyDate) - 1);
      # code...
    } else {
      return false;
    }

  }

  public function CountPeerPost($postId=0,$keyDate='')
  {
    $chiefId = $this->GetSuperiorPost($postId,$keyDate)->post_id;
    return   ($this->BaseModel->CountTopDownRel($chiefId,$this->relReport,$keyDate) - 1);

  }

  public function CountManagingOrg($postId=0,$keyDate='')
  {
    return $this->BaseModel->CountBotUpRel($postId,$this->relChief,$keyDate);
  }

  public function CountSubordinatePerson($postId=0,$keyDate='')
  {
    $relCode = array($this->relReport,$this->relHold);
    return $this->BaseModel->CountTopDownRel($postId,$relCode,$keyDate);

  }

  public function CountSubordinatePost($postId=0,$keyDate='')
  {
    return $this->BaseModel->CountTopDownRel($postId,$this->relReport,$keyDate);
  }

  public function CountSuperiorPerson($postId=0,$keyDate='')
  {
    $res = $this->BaseModel->CountBotUpRel($postId,$this->relReport,$keyDate);
    if ($res) {
      $sprId = $this->BaseModel->GetLastBotUpRel($postId,$this->relReport,$keyDate)->obj_id;

      return $this->BaseModel->CountTopDownRel($sprId,$this->relHold,$keyDate);
    } else {
      return 0;
    }

  }

  public function CountSuperiorPost($postId=0,$keyDate='')
  {
    return $this->BaseModel->CountBotUpRel($postId,$this->relReport,$keyDate);
  }

  public function Create($name='',$beginDate='1990-01-01',$endDate='9999-12-31-31',$orgId=0,$reportTo=0,$isChief=FALSE,$jobId=0,$empId=false)
  {
    $postId = $this->BaseModel->Create($this->objType,$name,$beginDate,$endDate);

    $this->BaseModel->CreateRel($this->relReport,$reportTo,$postId,$beginDate,$endDate);
    $this->BaseModel->CreateRel($this->relAssign,$orgId,$postId,$beginDate,$endDate);
    if ($isChief) {
      $this->BaseModel->CreateRel($this->relChief,$orgId,$postId,$beginDate,$endDate);
    }
    $this->BaseModel->CreateRel($this->relJob,$jobId,$postId,$beginDate,$endDate);
    if ($empId) {
      $this->BaseModel->CreateRel($this->relHold,$postId,$empId,$beginDate,$endDate);

    }


    return $postId;
  }

  public function Delete($postId=0)
  {
    $this->BaseModel->Delete($postId);
  }

  public function DeleteRel($relId=0)
  {
    $this->BaseModel->DeleteRel($relId);
  }

  public function Delimit($postId=0,$endDate='')
  {
    $this->BaseModel->Delimit($postId,$endDate);
  }

  public function GetAssignmentOrgList($postId=0,$keyDate='')
  {
    return $this->BaseModel->GetBotUpRelList($postId,$this->relAssign,$keyDate,'org');
  }

  public function GetByIdRow($id=0)
  {
    return $this->BaseModel->GetByIdRow($id);
  }

  public function GetHolderHistoryList($postId=0,$keyDate='')
  {
    return $this->BaseModel->GetTopDownRelList($postId,$this->relHold,$keyDate,'person');
  }

  public function GetJobList($postId=0,$keyDate='')
  {
    return $this->BaseModel->GetBotUpRelList($postId,$this->relJob,$keyDate,'job');
  }

  public function GetLastAssignmentOrg($postId=0,$keyDate='')
  {
    return $this->BaseModel->GetLastBotUpRel($postId,$this->relAssign,$keyDate,'org');

  }

  public function GetLastManagingOrg($postId=0,$keyDate='')
  {
    return $this->BaseModel->GetLastBotUpRel($postId,$this->relChief,$keyDate,'org');

  }

  public function GetLastName($postId=0,$keyDate='')
  {
    return $this->BaseModel->GetLastAttr($postId,$keyDate);
  }

  public function GetLastHolder($postId=0,$keyDate='')
  {
    return $this->BaseModel->GetLastTopDownRel($postId,$this->relHold,$keyDate,'person');

  }

  public function GetLastJob($postId=0,$keyDate='')
  {
    return $this->BaseModel->GetLastBotUpRel($postId,$this->relJob,$keyDate,'job');

  }

  public function GetLastSuperiorPerson($postId=0,$keyDate='')
  {
    $count = $this->CountSuperiorPerson($postId,$keyDate);
    while ($count == 0) {
      $postId = $this->GetSuperiorPost($postId,$keyDate)->post_id;
      $count   = $this->CountSuperiorPerson($postId,$keyDate);
    }
    $post   = $this->GetLastSuperiorPost($postId,$keyDate,'post');
    $person = $this->BaseModel->GetLastTopDownRel($post->post_id,$this->relHold,$keyDate,'person');
    $result = array(
      'post_id'     => $post->post_id,
      'post_name'   => $post->post_name,
      'person_id'   => $person->person_id,
      'person_name' => $person->person_name,
    );
    return (Object) $result;
  }

  public function GetLastSuperiorPost($postId=0,$keyDate='')
  {
    return $this->BaseModel->GetLastBotUpRel($postId,$this->relReport,$keyDate,'post');
  }

  public function GetList($beginDate='1990-01-01',$endDate='9999-12-31')
  {
    $keydate['begin'] = $beginDate;
    $keydate['end']   = $endDate;
    return $this->BaseModel->GetList($this->objType,$keydate);
  }

  public function GetManagingOrgList($postId=0,$keyDate='')
  {
    return $this->BaseModel->GetBotUpRelList($postId,$this->relChief,$keyDate,'org');
  }

  public function GetNameHistoryList($postId=0,$keyDate='')
  {
    return $this->BaseModel->GetAttrList($postId,$keyDate);
  }

  public function GetPeerPostList($postId=0,$keyDate='')
  {
    $chiefId = $this->GetLastSuperiorPost($postId,$keyDate)->post_id;
    $list = $this->BaseModel->GetTopDownRelList($chiefId,$this->relReport,$keyDate,'post');

    $result = array();
    foreach ($list as $row) {
      if ($row->post_id != $postId) {
        $result[] = $row;
      }
    }
    return $result;

  }

  public function GetPeerPersonList($postId=0,$keyDate='')
  {
    $chiefId = $this->GetSuperiorPost($postId,$keyDate)->post_id;
    $relCode = array($this->relReport,$this->relHold);
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

  public function GetRelByIdRow($relId=0)
  {
    return $this->BaseModel->GetRelById($relId);
  }

  public function GetSubordinatePersonList($postId=0,$keyDate='')
  {
    $relCode = array($this->relReport,$this->relHold);
    $alias   = array('post','person');
    return $this->BaseModel->GetTopDownRelList($postId,$relCode,$keyDate,$alias);

  }

  public function GetSubordinatePostList($postId=0,$keyDate='')
  {
    return $this->BaseModel->GetTopDownRelList($postId,$this->relReport,$keyDate,'post');
  }

  public function GetSuperiorPostList($postId=0,$keyDate='')
  {
    return $this->BaseModel->GetBotUpRelList($postId,$this->relReport,$keyDate,'post');
  }

}
