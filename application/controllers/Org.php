<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Org extends CI_Controller{

  private $viewDir   = 'org/';
  private $ctrlClass = 'Org/';

  public function __construct()
  {
    parent::__construct();
    $this->load->model('OrgModel');

  }

  function index()
  {
    $this->session->unset_userdata('selectId');
    $begin = $this->input->post('dt_begin');
    $end   = $this->input->post('dt_end');

    if ($begin == '') {
      $begin = date('Y-m-d');
    }

    if ($end == '') {
      $end = '9999-12-31';
    }
    $rows = $this->OrgModel->GetList($begin,$end);
    $data['rows'] = array();
    $i = 0 ;
    foreach ($rows as $row) {
      $temp = array(
        'id'       => $row->id,
        'begda'    => $row->begin_date,
        'endda'    => $row->end_date,
        'name'     => $row->name,
        'viewlink' => anchor($this->ctrlClass.'View/'.$row->id.'/'.$begin.'/'.$end,'View','class="btn btn-link" title="view"'),
      );
      $data['rows'][$i] = $temp;
      $i++;
    }

    $data['begin'] = $begin;
    $data['end']   = $end;
    $data['addLink'] = $this->ctrlClass.'Add';

    $this->parser->parse($this->viewDir.'main_view',$data);
  }

  public function Add()
  {
    $begin  = $this->session->userdata('filterBegDa');
    $end    = $this->session->userdata('filterEndDa');
    if (is_null($begin) OR $begin == '') {
      $begin = date('Y-m-d');
    }
    if (is_null($end) OR $end == '') {
      $end = date('Y-m-d');
    }
    $ls     = $this->OrgModel->GetList($begin,$end);
    $parent = array();
    foreach ($ls as $row) {
      $parent[$row->id] = $row->id.' - '.$row->name;
    }
    $data['process']    = $this->ctrlClass.'AddProcess';
    $data['parentOpt']  = $parent;
    $data['cancelLink'] = $this->ctrlClass;

    $this->load->view($this->viewDir.'add_form',$data);

  }

  public function AddProcess()
  {
    $begin  = $this->input->post('dt_begin');
    $end    = $this->input->post('dt_end');
    $name   = $this->input->post('txt_name');
    $parent = $this->input->post('slc_parent');
    $this->OrgModel->Create($name,$begin,$end,$parent);
    redirect($this->ctrlClass);
  }

  public function EditChief()
  {
    $this->load->model('PostModel');
    $id    = $this->session->userdata('selectId');
    $begin = $this->session->userdata('filterBegDa');
    $end   = $this->session->userdata('filterEndDa');
    if ($id == '') {
      redirect($this->ctrlClass);
    }
    $keydate['begin'] = $begin;
    $keydate['end']   = $end;
    $ls = $this->PostModel->GetList($begin,$end);
    $chiefOpt = array();
    foreach ($ls as $row) {
      $chiefOpt[$row->id] = $row->id .' - '.$row->name;
    }
    $chief = $this->OrgModel->GetLastChiefPost($id,$keydate);
    $data['chiefOpt']   = $chiefOpt;
    $data['chiefSlc']   = $chief->post_id;
    $data['begin']      = date('Y-m-d');

    $data['cancelLink'] = $this->ctrlClass.'View/';
    $data['process']    = $this->ctrlClass.'EditChiefProcess';
    $this->load->view($this->viewDir.'chief_form', $data);

  }

  public function EditChiefProcess()
  {
    $validOn  = $this->input->post('dt_begin');
    $newChief = $this->input->post('slc_chief');
    $id       = $this->session->userdata('selectId');
    $this->OrgModel->ChangeChiefPost($id,$newChief,$validOn,'9999-12-31');
    redirect($this->ctrlClass.'View/');
  }

  public function EditDate()
  {
    $id  = $this->session->userdata('selectId');
    if ($id == '') {
      redirect($this->ctrlClass);
    }
    $old = $this->OrgModel->GetByIdRow($id);
    $data['end']   = $old->end_date;

    $data['cancelLink'] = $this->ctrlClass.'View/';
    $data['process'] = $this->ctrlClass.'EditDateProcess';
    $this->load->view('element/date_form', $data);

  }

  public function EditDateProcess()
  {
    $id  = $this->session->userdata('selectId');
    $end = $this->input->post('dt_end');
    $this->OrgModel->Delimit($id,$end);
    redirect($this->ctrlClass.'View/');

  }

  public function EditName()
  {
    $id  = $this->session->userdata('selectId');
    if ($id == '') {
      redirect($this->ctrlClass);
    }
    $old                = $this->OrgModel->GetLastName($id);
    $data['begin']      = date('Y-m-d');
    $data['name']       = $old->name;
    $data['cancelLink'] = $this->ctrlClass.'View/';
    $data['process']    = $this->ctrlClass.'EditNameProcess';
    $this->load->view('element/name_form', $data);

  }

  public function EditNameProcess()
  {
    $validOn = $this->input->post('dt_begin');
    $newName = $this->input->post('txt_name');
    $id      = $this->session->userdata('selectId');
    $this->OrgModel->ChangeName($id,$newName,$validOn,'9999-12-31');
    redirect($this->ctrlClass.'View/'.$id.'/'.$validOn.'/9999-12-31');
  }

  public function EditParent()
  {
    $id    = $this->session->userdata('selectId');
    $begin = $this->session->userdata('filterBegDa');
    $end   = $this->session->userdata('filterEndDa');
    $keydate['begin'] = $begin;
    $keydate['end']   = $end;
    $old = $this->OrgModel->GetParentOrg($id,$keydate);

    $ls     = $this->OrgModel->GetList($begin,$end);
    $parent = array();
    foreach ($ls as $row) {
      $parent[$row->id] = $row->id.' - '.$row->name;
    }
    $data['parentOpt']  = $parent;
    $data['parentSlc']  = $old->parent_id;
    $data['cancelLink'] = $this->ctrlClass.'View/';
    $data['process'] = $this->ctrlClass.'EditParentProcess/';
    $this->load->view($this->viewDir.'parent_form', $data);
  }

  public function EditParentProcess()
  {
    $id = $this->session->userdata('selectId');
    $since     = $this->input->post('dt_begin');
    $newParent = $this->input->post('slc_parent');
    $this->OrgModel->ChangeParent($id,$newParent,$since,'9999-12-31');
  }

  public function EditRel($relId=0)
  {
    $data['hidden']  = array(
      'rel_id' => $relId
    );
    $old = $this->OrgModel->GetRelByIdRow($relId);
    $data['process'] = $this->ctrlClass.'EditRelProcess';
    $data['begin']   = $old->begin_date;
    $data['end']     = $old->end_date;
    $data['cancelLink'] = $this->ctrlClass.'View/';

    $this->load->view('element/date_form', $data);
  }

  public function EditRelProcess()
  {
    $relId = $this->input->post('rel_id');
    $begin = $this->input->post('dt_begin');
    $end   = $this->input->post('dt_end');
    $this->OrgModel->ChangeRelDate($relId,$begin,$end);
    redirect($this->ctrlClass.'View/');
  }

  public function DeleteProcess()
  {
    $id = $this->session->userdata('selectId');
    $this->OrgModel->Delete($id);
    redirect($this->ctrlClass);

  }

  public function DeleteRelProcess($relId=0)
  {
    $this->OrgModel->DeleteRel($relId);
    redirect($this->ctrlClass.'View/');
  }

  public function View($id=0,$begin='',$end='')
  {
    $delimit = site_url($this->ctrlClass.'EditRel/');
    $remove  = site_url($this->ctrlClass.'DeleteRelProcess/');

    if ($id == 0 && $begin == '' && $end == '') {
      $id    = $this->session->userdata('selectId');
      $begin = $this->session->userdata('filterBegDa');
      $end   = $this->session->userdata('filterEndDa');
    } else {
      $array = array(
        'selectId'    => $id,
        'filterBegDa' => $begin,
        'filterEndDa' => $end,
      );
      $this->session->set_userdata($array);
    }
    $keydate['begin'] = $begin;
    $keydate['end']   = $end;

    $obj  = $this->OrgModel->GetByIdRow($id);
    $attr = $this->OrgModel->GetLastName($id,$keydate);
    $data['begin']    = $begin;
    $data['end']      = $end;
    $data['objBegin'] = $obj->begin_date;
    $data['objEnd']   = $obj->end_date;
    $data['objName']  = $attr->name;
    $keydate['begin'] = $begin;
    $keydate['end']   = $end;
    $ls = $this->OrgModel->GetNameHistoryList($id,$keydate,'desc');
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
    if ($this->OrgModel->CountParentOrg($id,$keydate)) {
      $parent = $this->OrgModel->GetParentOrg($id,$keydate);
      $data['parentId']   = $parent->parent_id;
      $data['parentName'] = $parent->parent_name;
    } else {
      $data['parentId']   = '';
      $data['parentName'] = '';
    }

    $ls = $this->OrgModel->GetParentOrgList($id,$keydate);
    $parent = array();
    foreach ($ls as $row) {
      $parent[] = array(
        'parentBegin' => $row->parent_begin_date,
        'parentEnd'   => $row->parent_end_date,
        'parentId'    => $row->parent_id,
        'parentName'  => $row->parent_name,
      );

    }
    $data['parent'] = $parent;

    $children = array();
    if ($this->OrgModel->CountChildrenOrg($id,$keydate)) {
      $child = $this->OrgModel->GetChildrenOrgList($id,$keydate);
      foreach ($child as $row) {
        $children[] = array(
          'childrenBegin' => $row->child_begin_date,
          'childrenEnd'   => $row->child_end_date,
          'childrenId'    => $row->child_id,
          'childrenName'  => $row->child_name,
          'chgRel'        => $delimit.$row->child_rel_id,
          'remRel'        => $remove.$row->child_rel_id,
        );
      }
    }
    $data['children'] = $children;
    $post = array();
    if ($this->OrgModel->CountPost($id,$keydate)) {
      $ls = $this->OrgModel->GetPostList($id,$keydate);
      foreach ($ls as $row) {
        $post[] = array(
          'postBegin' => $row->post_begin_date,
          'postEnd'   => $row->post_end_date,
          'postId'    => $row->post_id,
          'postName'  => $row->post_name,
          'chgRel'    => $delimit.$row->post_rel_id,
          'remRel'    => $remove.$row->post_rel_id,
        );
      }
    }
    $data['post']     = $post;

    if ($this->OrgModel->CountChiefPost($id,$keydate)) {
      $chief = $this->OrgModel->GetLastChiefPost($id,$keydate);
      $data['chiefPostId']   = $chief->post_id;
      $data['chiefPostName'] = $chief->post_name;
    } else {
      $data['chiefPostId']   = '-';
      $data['chiefPostName'] = '-';
    }
    $ls = $this->OrgModel->GetChiefPostList($id,$keydate);
    $chief = array();
    foreach ($ls as $row) {
      $chief[] = array(
        'chiefBegin' => $row->post_begin_date,
        'chiefEnd'   => $row->post_end_date,
        'chiefId'    => $row->post_id,
        'chiefName'  => $row->post_name,
      );
    }
    $data['chief'] = $chief;

    $data['backLink']   = $this->ctrlClass;
    $data['delLink']    = $this->ctrlClass.'DeleteProcess';
    $data['editDate']   = $this->ctrlClass.'EditDate/';
    $data['editName']   = $this->ctrlClass.'EditName/';
    $data['editParent'] = $this->ctrlClass.'EditParent/';
    $data['editChief']  = $this->ctrlClass.'EditChief/';;
    $this->parser->parse($this->viewDir.'detail_view',$data);
  }


}
