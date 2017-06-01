<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Exp extends CI_Controller{

  public function __construct()
  {
    parent::__construct();
    //Codeigniter : Write Less Do More
  }

  function index()
  {

  }

  public function Search()
  {
    $this->load->model(array('BaseModel'));
    $this->load->view('search_form');
  }

}
