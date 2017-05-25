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
    $rows = $this->PostModel->GetList($begin,$end);
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
        'selectId'    => $id,
        'filterBegDa' => $begin,
        'filterEndDa' => $end,
      );
      $this->session->set_userdata($array);
    }
    $keydate['begin'] = $begin;
    $keydate['end']   = $end;

    $obj  = $this->PostModel->GetByIdRow($id);
    $attr = $this->PostModel->GetLastName($id,$keydate);
    $data['begin']    = $begin;
    $data['end']      = $end;
    $data['objBegin'] = $obj->begin_date;
    $data['objEnd']   = $obj->end_date;
    $data['objName']  = $attr->name;
    $keydate['begin'] = '1990-01-01';
    $keydate['end']   = '9999-12-31';
    $ls = $this->PostModel->GetNameHistoryList($id,$keydate,'desc');

    if ($this->PostModel->CountSuperiorPost($id,$keydate)) {
      $spr = $this->PostModel->GetSuperiorPost($id,$keydate);
      $data['sprPostId']   = $spr->post_id;
      $data['sprPostName'] = $spr->post_name;
    } else {
      $data['sprPostId']   = '-';
      $data['sprPostName'] = '-';
    }
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

    $holder = $this->PostModel->GetLastHolder($id,$keydate);
    $data['holderBegin'] = $holder->person_begin_date;
    $data['holderEnd']   = $holder->person_end_date;
    $data['holderId']    = $holder->person_id;
    $data['holderName']  = $holder->person_name;

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


    $sub = array();
    $ls  = $this->PostModel->GetSubordinatePostList($id,$keydate);
    foreach ($ls as $row) {
      $sub[] = array(
        'subBegin'    => $row->post_begin_date,
        'subEnd'      => $row->post_end_date,
        'subPostId'   => $row->post_id,
        'subPostName' => $row->post_name,

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

    $data['backLink']   = $this->ctrlClass;
    $data['delLink']    = $this->ctrlClass.'DeleteProcess';
    $data['editDate']   = $this->ctrlClass.'EditDate/';
    $data['editName']   = $this->ctrlClass.'EditName/';
    $data['editHolder'] = $this->ctrlClass.'EditHolder/';
    $data['editSpr']    = $this->ctrlClass.'EditSuperior/';
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
    $ls     = $this->PostModel->GetList($begin,$end);
    $parent = array();
    foreach ($ls as $row) {
      $parent[$row->id] = $row->id.' - '.$row->name;
    }
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
    $this->PostModel->Create($name,$begin,$end,$parent);
    redirect($this->ctrlClass);
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

  public function DeleteProcess()
  {
    $id = $this->session->userdata('selectId');
    $this->PostModel->Delete($id);
    redirect($this->ctrlClass);

  }
}
