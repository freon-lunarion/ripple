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

  public function SetFilterDate()
  {
    $begin = $this->input->post('begDa');
    $end   = $this->input->post('endDa');
    $this->session->set_userdata('filterBegDa',$begin);
    $this->session->set_userdata('filterEndDa',$end);
  }

}
