<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Job extends CI_Controller{

  public function __construct()
  {
    parent::__construct();
    //Codeigniter : Write Less Do More
    $this->load->model('JobModel');
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
    $rows = $this->JobModel->GetList($begin,$end);
    $data['rows'] = array();
    $i = 0 ;
    foreach ($rows as $row) {
      $temp = array(
        'id'       => $row->id,
        'begda'    => $row->begin_date,
        'endda'    => $row->end_date,
        'name'     => $row->name,
        'viewlink' => anchor('Job/View/'.$row->id.'/'.$begin.'/'.$end,'View','class="btn btn-link" title="view"'),
      );
      $data['rows'][$i] = $temp;
      $i++;
    }

    $data['begin'] = $begin;
    $data['end']   = $end;

    $this->parser->parse('job/main_view',$data);
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

    $obj  = $this->JobModel->GetByIdRow($id);
    $attr = $this->JobModel->GetLastName($id,$keydate);
    $data['begin']    = $begin;
    $data['end']      = $end;
    $data['objBegin'] = $obj->begin_date;
    $data['objEnd']   = $obj->end_date;
    $data['objName']  = $attr->name;
    $keydate['begin'] = '1990-01-01';
    $keydate['end']   = '9999-12-31';
    $ls = $this->JobModel->GetNameHistoryList($id,$keydate,'desc');
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
    $this->parser->parse('job/detail_view',$data);
  }

  public function Add()
  {
    $this->load->view('job/add_form');
  }

  public function AddProcess()
  {
    $begin = $this->input->post('dt_begin');;
    $end   = $this->input->post('dt_end');;
    $name  = $this->input->post('txt_name');;
    $this->JobModel->Create($name,$begin,$end);
    redirect('Job');
  }

  public function EditName()
  {
    $id  = $this->session->userdata('selectId');
    if ($id == '') {
      redirect('Job');
    }
    $old = $this->JobModel->GetLastName($id);
    $data['begin'] = date('Y-m-d');
    $data['name']  = $old->name;
    // $data['process'] = 'Job/EditNameProcess';
    $this->load->view('job/name_form', $data);

  }

  public function EditNameProcess()
  {
    $validOn = $this->input->post('dt_begin');
    $newName = $this->input->post('txt_name');
    $id      = $this->session->userdata('selectId');

    $this->JobModel->ChangeName($id,$newName,$validOn,'9999-12-31');
    redirect('Job/View/'.$id.'/'.$validOn.'/9999-12-31');
  }

  public function EditDate()
  {
    $id  = $this->session->userdata('selectId');
    if ($id == '') {
      redirect('Job');
    }
    $old = $this->JobModel->GetByIdRow($id);
    $data['end']   = $old->end_date;

    $data['process'] = 'Job/EditDateProcess';
    $this->load->view('job/date_form', $data);

  }

  public function EditDateProcess()
  {
    $id  = $this->session->userdata('selectId');
    $end = $this->input->post('dt_end');
    $this->JobModel->Delimit($id,$end);
    redirect('Job/View/');

  }

  public function DeleteProcess()
  {
    $id  = $this->session->userdata('selectId');
    $this->JobModel->Delete($id);
    redirect('Job/');

  }

}
