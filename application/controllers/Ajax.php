<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ajax extends CI_Controller{

  public function __construct()
  {
    parent::__construct();
    //Codeigniter : Write Less Do More
  }

  function index()
  {

  }
  /**
   * [Set (Range) Date Filter in session]
   * @method SetFilterDate
   */

  public function SetFilterDate()
  {
    $begin = $this->input->post('begDa');
    if ($this->input->post('begDa') == '') {
      $begin = date('Y-m-d');
    }
    $end   = $this->input->post('endDa');
    if ($this->input->post('endDa') == '') {
      $end = date('Y-m-d');
    }
    $this->session->set_userdata('filterBegDa',$begin);
    $this->session->set_userdata('filterEndDa',$end);
  }


  public function ShowOrgStrucSelection()
  {
    $this->load->model('OrgModel');
    $orgId = $this->input->post('id');
    $mode  = strtolower($this->input->post('mode')); // [org,post]
    if (!$this->session->userdata('filterBegDa') || !$this->session->userdata('filterEndDa')) {
      $sess = array(
        'filterBegDa' => date('Y-m-d'),
        'filterEndDa' => date('Y-m-d'),
      );
      $this->session->set_userdata($sess);
    }
    $begin = $this->session->userdata('filterBegDa');
    $end   = $this->session->userdata('filterEndDa');
    $date  = array(
      'begin' => $begin,
      'end'   => $end,
    );

    // Breadcrumb Navigation
    $bc = $this->OrgModel->GetStruct($orgId,$date);
    switch ($mode) {
      case 'org':
        $data['bc'][0] = array(
          'id'   => 0,
          'name' => 'ROOT',
        );
        break;
    }
    foreach ($bc as $row) {
      $data['bc'][] = $row;
    }
    // --------------------------------------------

    // Children of Organization
    if ($orgId ==0 ) {
      $row = $this->OrgModel->GetByIdRow(1,$date);
      $data['org'][0] = array(
        'id'    => $row->id,
        'begda' => $row->begin_date,
        'endda' => $row->end_date,
        'name'  => $this->OrgModel->GetLastName(1,$date)->name,
      );


    } else {
      $child = $this->OrgModel->GetChildrenOrgList($orgId,$date);
      $i     = 0;
      $data['org'] = array();
      foreach ($child as $row) {
        $data['org'][$i] = array(
          'id'    => $row->child_id,
          'begda' => $row->child_begin_date,
          'endda' => $row->child_end_date,
          'name'  => $row->child_name,
        );
        $i++;
      }

    }

    // --------------------------------------------

    switch ($mode) {
      case 'org':
        $this->parser->parse('_element/orgStruct_content', $data);

        break;
      case 'post':
        // Position in Organization
        $i  = 0;
        $ls = $this->OrgModel->GetPostList($orgId,$date);
        foreach ($ls as $row) {
          $data['post'][$i] = array(
            'id'    => $row->post_id,
            'begda' => $row->post_begin_date,
            'endda' => $row->post_end_date,
            'name'  => $row->post_name,
          );
          $i++;
        }
        $this->parser->parse('_element/orgPostStruct_content', $data);

        break;
    }
  }

}
