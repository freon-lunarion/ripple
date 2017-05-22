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
    $this->session->unset_userdata('filterBegDa');
    $this->session->unset_userdata('filterEndDa');
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

  public function View($id=0,$begin='',$end='')
  {
    if ($id == 0 && $begin == '' && $end == '') {
      $id    = $this->session->userdata('selectId');
      $begin = $this->session->userdata('filterBegDa');
      $end   = $this->session->userdata('filterEndDa');
    } else {
      $array = array(
        'selectId' => $id,
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
    $keydate['begin'] = '1990-01-01';
    $keydate['end']   = '9999-12-31';
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

    if ($this->OrgModel->CountChildrenOrg($id,$keydate)) {
      $child = $this->OrgModel->GetChildrenOrgList($id,$keydate);
      $children = array();
      foreach ($child as $row) {
        $children[] = array(
          'childrenBegin' => $row->child_begin_date,
          'childrenEnd'   => $row->child_end_date,
          'childrenId'    => $row->child_id,
          'childrenName'  => $row->child_name,
        );
      }
      $data['children'] = $children;
    } else {
      $data['children'] = array();
    }
    $data['backLink'] = $this->ctrlClass;
    $data['delLink']  = $this->ctrlClass.'DeleteProcess';
    $data['editDate'] = $this->ctrlClass.'EditDate/';
    $data['editName'] = $this->ctrlClass.'EditName/';
    $this->parser->parse($this->viewDir.'detail_view',$data);
  }

  public function Breadcrumb($id=0)
  {

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
    $data['parentOpt'] = $parent;
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

  public function DeleteProcess()
  {
    $id = $this->session->userdata('selectId');
    $this->OrgModel->Delete($id);
    redirect($this->ctrlClass);

  }
}
