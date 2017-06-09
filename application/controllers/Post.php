<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Post extends CI_Controller{

  private $viewDir   = 'post/';
  private $ctrlClass = 'Post/';

  public function __construct()
  {
    parent::__construct();
    $this->load->model('PostModel');

  }

  function index()
  {
    $this->session->unset_userdata('selectId');
    $begin = $this->session->userdata('filterBegDa');
    $end   = $this->session->userdata('filterEndDa');

    if ($begin == '') {
      $begin = date('Y-m-d');
    }

    if ($end == '') {
      $end = date('Y-m-d');
    }

    $data['ajaxUrl'] = $this->ctrlClass.'AjaxGetList';
    $data['begin'] = $begin;
    $data['end']   = $end;
    $data['addLink'] = $this->ctrlClass.'Add';

    $this->parser->parse($this->viewDir.'main_view',$data);
  }

  public function AjaxGetList()
  {
    $begin = $this->session->userdata('filterBegDa');
    $end   = $this->session->userdata('filterEndDa');
    $rows = $this->PostModel->GetList($begin,$end);

    $data['rows'] = array();
    $i = 0 ;
    foreach ($rows as $row) {
      $temp = array(
        'id'       => $row->id,
        'begda'    => $row->begin_date,
        'endda'    => $row->end_date,
        'name'     => $row->name,
        'viewlink' => anchor($this->ctrlClass.'View/'.$row->id,'View','class="btn btn-link" title="view"'),
      );
      $data['rows'][$i] = $temp;
      $i++;
    }
    $this->parser->parse('_element/obj_tbl',$data);

  }

  public function Add()
  {
    $this->load->model(array('OrgModel','JobModel','PersModel'));
    $begin  = $this->session->userdata('filterBegDa');
    $end    = $this->session->userdata('filterEndDa');
    if (is_null($begin) OR $begin == '') {
      $begin = date('Y-m-d');
    }
    if (is_null($end) OR $end == '') {
      $end = date('Y-m-d');
    }
    $ls     = $this->PostModel->GetList($begin,$end);
    $super = array();
    foreach ($ls as $row) {
      $super[$row->id] = $row->id.' - '.$row->name;
    }

    $ls     = $this->OrgModel->GetList($begin,$end);
    $parent = array();
    foreach ($ls as $row) {
      $parent[$row->id] = $row->id.' - '.$row->name;
    }
    $ls     = $this->JobModel->GetList($begin,$end);
    $job = array();
    foreach ($ls as $row) {
      $job[$row->id] = $row->id.' - '.$row->name;
    }

    $ls  = $this->PersModel->GetList($begin,$end);
    $emp = array(''=>'');
    foreach ($ls as $row) {
      $emp[$row->id] = $row->id.' - '.$row->name;
    }
    $data['process']    = $this->ctrlClass.'AddProcess';
    $data['superOpt']   = $super;
    $data['parentOpt']  = $parent;
    $data['jobOpt']     = $job;
    $data['empOpt']     = $emp;
    $data['cancelLink'] = $this->ctrlClass;

    $this->load->view($this->viewDir.'add_form',$data);

  }

  public function AddProcess()
  {
    $begin   = $this->input->post('dt_begin');
    $end     = $this->input->post('dt_end');
    $name    = $this->input->post('txt_name');
    $spr     = $this->input->post('slc_super');
    $parent  = $this->input->post('slc_parent');
    $job     = $this->input->post('slc_job');
    $isChief = $this->input->post('chk_chief');
    $emp     = $this->input->post('slc_emp');
    $this->PostModel->Create($name,$begin,$end,$parent,$spr,$isChief,$job,$emp);
    redirect($this->ctrlClass);
  }

  public function DeleteRelProcess($relId=0)
  {
    $this->OrgModel->DeleteRel($relId);
    redirect($this->ctrlClass.'View/');
  }

  public function DeleteProcess()
  {
    $id = $this->session->userdata('selectId');
    $this->PostModel->Delete($id);
    redirect($this->ctrlClass);

  }
  public function EditAssignment()
  {
    $this->load->model(array('OrgModel'));
    $id     = $this->session->userdata('selectId');
    $begin  = $this->session->userdata('filterBegDa');
    $end    = $this->session->userdata('filterEndDa');
    $keydate['begin'] = $begin;
    $keydate['end']   = $end;
    $ls     = $this->OrgModel->GetList($begin,$end);
    $orgOpt = array();
    foreach ($ls as $row) {
      $orgOpt[$row->id] = $row->id .' - '.$row->name;
    }
    $data['orgOpt'] = $orgOpt;
    $data['orgSlc'] = $this->PostModel->GetLastAssignmentOrg($id,$keydate)->org_id;

    $data['cancelLink'] = $this->ctrlClass.'View/';
    $data['process']    = $this->ctrlClass.'EditAssignmentProcess/';
    $this->load->view($this->viewDir.'assignment_form', $data);

  }

  public function EditAssignmentProcess()
  {
    $validOn = $this->input->post('dt_begin');
    $newOrg  = $this->input->post('slc_org');
    $id      = $this->session->userdata('selectId');
    $this->PostModel->ChangeAssigmentOrg($id,$newOrg,$validOn,'9999-12-31');
    redirect($this->ctrlClass.'View/');
  }

  public function EditDate()
  {
    $id  = $this->session->userdata('selectId');
    if ($id == '') {
      redirect($this->ctrlClass);
    }
    $old = $this->PostModel->GetByIdRow($id);
    $data['end']   = $old->end_date;

    $data['cancelLink'] = $this->ctrlClass.'View/';
    $data['process'] = $this->ctrlClass.'EditDateProcess';
    $this->load->view($this->viewDir.'date_form', $data);

  }

  public function EditDateProcess()
  {
    $id  = $this->session->userdata('selectId');
    $end = $this->input->post('dt_end');
    $this->PostModel->Delimit($id,$end);
    redirect($this->ctrlClass.'View/');

  }

  public function EditHolder()
  {
    $this->load->model('PersModel');
    $id    = $this->session->userdata('selectId');
    $begin = $this->session->userdata('filterBegDa');
    $end   = $this->session->userdata('filterEndDa');
    if ($id == '') {
      redirect($this->ctrlClass);
    }

    $keydate['begin'] = $begin;
    $keydate['end']   = $end;

    $ls = $this->PersModel->GetList($begin,$end);
    $empOpt = array(''=>'');
    foreach ($ls as $row) {
      $empOpt[$row->id] = $row->id .' - '.$row->name;
    }

    if ($this->PostModel->CountHolder($id,$keydate)) {
      $holder = $this->PostModel->GetLastHolder($id,$keydate);
      $emp = $this->PostModel->GetLastHolder($id,$keydate)->person_id;
    } else {
      $emp = '';
    }
    $emp = $this->PostModel->GetLastHolder($id,$keydate);
    $data['empOpt']   = $empOpt;
    $data['empSlc']   = $emp;
    $data['cancelLink'] = $this->ctrlClass.'View/';
    $data['process']  = $this->ctrlClass.'EditHolderProcess/';
    $this->load->view($this->viewDir.'holder_form', $data);

  }

  public function EditHolderProcess()
  {
    $validOn   = $this->input->post('dt_begin');
    $newHolder = $this->input->post('slc_emp');
    $id        = $this->session->userdata('selectId');
    $this->PostModel->ChangeHolder($id,$newHolder,$validOn,'9999-12-31');
    redirect($this->ctrlClass.'View/');
  }

  public function EditJob()
  {
    $this->load->model('JobModel');
    $id    = $this->session->userdata('selectId');
    $begin = $this->session->userdata('filterBegDa');
    $end   = $this->session->userdata('filterEndDa');
    if ($id == '') {
      redirect($this->ctrlClass);
    }

    $keydate['begin'] = $begin;
    $keydate['end']   = $end;

    $ls = $this->JobModel->GetList($begin,$end);
    $jobOpt = array();
    foreach ($ls as $row) {
      $jobOpt[$row->id] = $row->id .' - '.$row->name;
    }
    $job = $this->PostModel->GetLastJob($id,$keydate);
    $data['jobOpt']   = $jobOpt;
    $data['jobSlc']   = $job->job_id;
    $data['cancelLink'] = $this->ctrlClass.'View/';
    $data['process']  = $this->ctrlClass.'EditJobProcess/';
    $this->load->view($this->viewDir.'job_form', $data);

  }

  public function EditJobProcess()
  {
    $validOn   = $this->input->post('dt_begin');
    $newJob    = $this->input->post('slc_job');
    $id        = $this->session->userdata('selectId');
    $this->PostModel->ChangeJob($id,$newJob,$validOn,'9999-12-31');
    redirect($this->ctrlClass.'View/');
  }

  public function EditManaging()
  {
    $this->load->model(array('OrgModel'));
    $id     = $this->session->userdata('selectId');
    $begin  = $this->session->userdata('filterBegDa');
    $end    = $this->session->userdata('filterEndDa');
    $keydate['begin'] = $begin;
    $keydate['end']   = $end;
    $ls     = $this->OrgModel->GetList($begin,$end);
    $orgOpt = array();
    foreach ($ls as $row) {
      $orgOpt[$row->id] = $row->id .' - '.$row->name;
    }
    $data['orgOpt'] = $orgOpt;
    if ($this->PostModel->CountManagingOrg($id,$keydate)) {
      $data['orgSlc'] = $this->PostModel->GetLastManagingOrg($id,$keydate)->org_id;
    } else {
      $data['orgSlc'] = '';
    }

    $data['cancelLink'] = $this->ctrlClass.'View/';
    $data['process']    = $this->ctrlClass.'EditManagingProcess';
    $this->load->view($this->viewDir.'managing_form', $data);

  }

  public function EditManagingProcess()
  {
    $validOn = $this->input->post('dt_begin');
    $newOrg  = $this->input->post('slc_org');
    $id      = $this->session->userdata('selectId');
    $this->PostModel->ChangeManagingOrg($id,$newOrg,$validOn,'9999-12-31');
    redirect($this->ctrlClass.'View/');
  }

  public function EditName()
  {
    $id  = $this->session->userdata('selectId');
    if ($id == '') {
      redirect($this->ctrlClass);
    }
    $old                = $this->PostModel->GetLastName($id);
    $data['begin']      = date('Y-m-d');
    $data['name']       = $old->name;
    $data['cancelLink'] = $this->ctrlClass.'View/';
    $data['process']    = $this->ctrlClass.'EditNameProcess';
    $this->load->view($this->viewDir.'name_form', $data);

  }

  public function EditNameProcess()
  {
    $validOn = $this->input->post('dt_begin');
    $newName = $this->input->post('txt_name');
    $id      = $this->session->userdata('selectId');
    $this->PostModel->ChangeName($id,$newName,$validOn,'9999-12-31');
    redirect($this->ctrlClass.'View/'.$id.'/'.$validOn.'/9999-12-31');
  }

  public function EditRel($relId=0)
  {
    $data['hidden']  = array(
      'rel_id' => $relId
    );
    $old = $this->PostModel->GetRelByIdRow($relId);
    $data['process'] = $this->ctrlClass.'EditRelProcess';
    $data['begin']   = $old->begin_date;
    $data['end']     = $old->end_date;
    $data['cancelLink'] = $this->ctrlClass.'View/';

    $this->load->view($this->viewDir.'date_form', $data);
  }

  public function EditRelProcess()
  {
    $relId = $this->input->post('rel_id');
    $begin = $this->input->post('dt_begin');
    $end   = $this->input->post('dt_end');
    $this->PostModel->ChangeRelDate($relId,$begin,$end);
    redirect($this->ctrlClass.'View/');
  }

  public function EditSuperior()
  {
    $id    = $this->session->userdata('selectId');
    $begin = $this->session->userdata('filterBegDa');
    $end   = $this->session->userdata('filterEndDa');
    if ($id == '') {
      redirect($this->ctrlClass);
    }

    $keydate['begin'] = $begin;
    $keydate['end']   = $end;

    $ls = $this->PostModel->GetList($begin,$end);
    $postOpt = array();
    foreach ($ls as $row) {
      $postOpt[$row->id] = $row->id .' - '.$row->name;
    }
    $post = $this->PostModel->GetLastSuperiorPost($id,$keydate);
    $data['postOpt']    = $postOpt;
    $data['postSlc']    = $post->post_id;
    $data['cancelLink'] = $this->ctrlClass.'View/';
    $data['process']    = $this->ctrlClass.'EditSuperiorProcess/';
    $this->load->view($this->viewDir.'superior_form', $data);

  }

  public function EditSuperiorProcess()
  {
    $validOn   = $this->input->post('dt_begin');
    $newPost = $this->input->post('slc_post');
    $id        = $this->session->userdata('selectId');
    $this->PostModel->ChangeSuperior($id,$newPost,$validOn,'9999-12-31');
    redirect($this->ctrlClass.'View/');
  }

  public function View($id=0)
  {
    $begin = $this->session->userdata('filterBegDa');
    $end   = $this->session->userdata('filterEndDa');
    if ($id == 0 ) {
      $id    = $this->session->userdata('selectId');
    } else {
      $array = array(
        'selectId' => $id,
      );
      $this->session->set_userdata($array);
    }
    $data['begin']    = $begin;
    $data['end']      = $end;
    $data['backLink'] = $this->ctrlClass;
    $data['delLink']  = $this->ctrlClass.'DeleteProcess';
    $data['ajaxUrl1'] = $this->ctrlClass.'AjaxGetDetail';
    $data['ajaxUrl2'] = $this->ctrlClass.'AjaxGetRel';
    $this->parser->parse($this->viewDir.'detail_view',$data);
  }

  public function AjaxGetDetail()
  {
    $id    = $this->session->userdata('selectId');
    $begin = $this->session->userdata('filterBegDa');
    $end   = $this->session->userdata('filterEndDa');
    $keydate['begin'] = $begin;
    $keydate['end']   = $end;

    $obj  = $this->PostModel->GetByIdRow($id);
    $attr = $this->PostModel->GetLastName($id,$keydate);
    $data['begin']    = $begin;
    $data['end']      = $end;
    $data['objBegin'] = $obj->begin_date;
    $data['objEnd']   = $obj->end_date;
    $data['objName']  = $attr->name;

    $data['editDate'] = $this->ctrlClass.'EditDate/';
    $data['editName'] = $this->ctrlClass.'EditName/';
    $this->parser->parse('_element/obj_detail',$data);


    $ls = $this->PostModel->GetNameHistoryList($id,$keydate,'desc');
    $history = array();
    foreach ($ls as $row) {
      if ($attr->id == $row->id) {
        $class = 'info';
      } else {
        $class = '';
      }
      $history[] = array(
        'historyRow'   => $class,
        'historyBegin' => $row->begin_date,
        'historyEnd'   => $row->end_date,
        'historyName'  => $row->name,
      );
    }
    $data['history']  = $history;
    $this->parser->parse('_element/hisname_tbl',$data);

  }

  public function AjaxGetRel()
  {
    $id    = $this->session->userdata('selectId');
    $begin = $this->session->userdata('filterBegDa');
    $end   = $this->session->userdata('filterEndDa');

    $delimit = site_url($this->ctrlClass.'EditRel/');
    $remove  = site_url($this->ctrlClass.'DeleteRelProcess/');

    $keydate['begin'] = $begin;
    $keydate['end']   = $end;
    $data['editAss']    = $this->ctrlClass.'EditAssignment/';
    $data['editHolder'] = $this->ctrlClass.'EditHolder/';
    $data['editJob']    = $this->ctrlClass.'EditJob/';
    $data['editMan']    = $this->ctrlClass.'EditManaging/';
    $data['editSpr']    = $this->ctrlClass.'EditSuperior/';
    if ($this->PostModel->CountSuperiorPost($id,$keydate)) {
      $spr = $this->PostModel->GetLastSuperiorPost($id,$keydate);
      $data['sprPostId']   = $spr->post_id;
      $data['sprPostName'] = $spr->post_name;
    } else {
      $data['sprPostId']   = '-';
      $data['sprPostName'] = '-';
    }

    $ls = $this->PostModel->GetSuperiorPostList($id,$keydate);
    $spr = array();
    foreach ($ls as $row) {
      $spr[] = array(
        'sprBegin' => $row->post_begin_date,
        'sprEnd'   => $row->post_end_date,
        'sprId'    => $row->post_id,
        'sprName'  => $row->post_name,
      );
    }
    $data['spr'] = $spr;
    if ($this->PostModel->CountHolder($id,$keydate)) {
      $holder = $this->PostModel->GetLastHolder($id,$keydate);
      $data['holderBegin'] = $holder->person_begin_date;
      $data['holderEnd']   = $holder->person_end_date;
      $data['holderId']    = $holder->person_id;
      $data['holderName']  = $holder->person_name;
    } else {
      $data['holderBegin'] = '';
      $data['holderEnd']   = '';
      $data['holderId']    = '';
      $data['holderName']  = '';
    }

    $ls = $this->PostModel->GetHolderHistoryList($id,$keydate);
    $holder = array();
    foreach ($ls as $row) {
      $holder[] = array(
        'holderBegin' => $row->person_begin_date,
        'holderEnd'   => $row->person_end_date,
        'holderId'    => $row->person_id,
        'holderName'  => $row->person_name,
      );
    }
    $data['holder'] = $holder;

    $job = $this->PostModel->GetLastJob($id,$keydate);
    $data['jobBegin'] = $job->job_begin_date;
    $data['jobEnd']   = $job->job_end_date;
    $data['jobId']    = $job->job_id;
    $data['jobName']  = $job->job_name;
    $ls = $this->PostModel->GetJobList($id,$keydate);

    $job = array();
    foreach ($ls as $row) {
      $job[] = array(
        'jobBegin' => $row->job_begin_date,
        'jobEnd'   => $row->job_end_date,
        'jobId'    => $row->job_id,
        'jobName'  => $row->job_name,
      );
    }
    $data['job'] = $job;

    $sub = array();
    $ls  = $this->PostModel->GetSubordinatePostList($id,$keydate);
    foreach ($ls as $row) {
      $sub[] = array(
        'subBegin'    => $row->post_begin_date,
        'subEnd'      => $row->post_end_date,
        'subPostId'   => $row->post_id,
        'subPostName' => $row->post_name,
        'chgRel'      => $delimit.$row->post_rel_id,
        'remRel'      => $remove.$row->post_rel_id,

      );
    }
    $data['sub'] = $sub;

    $peer = array();
    if ($this->PostModel->CountPeerPerson($id,$keydate)) {
      $ls = $this->PostModel->GetPeerPostList($id,$keydate);
      foreach ($ls as $row) {
        $peer[] = array(
          'peerBegin'    => $row->post_begin_date,
          'peerEnd'      => $row->post_end_date,
          'peerPostId'   => $row->post_id,
          'peerPostName' => $row->post_name,
        );
      }
    }
    $data['peer'] = $peer;

    $data['man']     = array();
    $data['manId']   = '';
    $data['manName'] = '';
    if ($this->PostModel->CountManagingOrg($id,$keydate)) {
      $man = $this->PostModel->GetLastManagingOrg($id,$keydate);
      $data['manId']   = $man->org_id;
      $data['manName'] = $man->org_name;

      $ls = $this->PostModel->GetManagingOrgList($id,$keydate);
      $man = array();
      foreach ($ls as $row) {
        $man[] = array(
          'manBegin' => $row->org_begin_date,
          'manEnd'   => $row->org_end_date,
          'manId'    => $row->org_id,
          'manName'  => $row->org_name,
        );
      }
      $data['man']     = $man;
    }

    $data['ass']     = array();
    $data['assId']   = '';
    $data['assName'] = '';
    $ass = $this->PostModel->GetLastAssignmentOrg($id,$keydate);
    $data['assId']   = $ass->org_id;
    $data['assName'] = $ass->org_name;

    $ls = $this->PostModel->GetAssignmentOrgList($id,$keydate);
    $ass = array();
    foreach ($ls as $row) {
      $ass[] = array(
        'assBegin' => $row->org_begin_date,
        'assEnd'   => $row->org_end_date,
        'assId'    => $row->org_id,
        'assName'  => $row->org_name,
      );
    }
    $data['ass']     = $ass;
    $this->parser->parse($this->viewDir . 'rel_elm',$data);

  }
}
