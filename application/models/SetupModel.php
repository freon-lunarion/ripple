<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class SetupModel extends CI_Model{

  private $tblObj     = 'test_obj';
  private $tblAttr    = 'test_obj_attribute';
  private $tblRel     = 'test_obj_rel';
  private $tblRefObj  = 'test_ref_obj_type';
  private $tblRefRel  = 'test_ref_obj_rel';
  private $attributes = array('ENGINE' => 'InnoDB');

  public function __construct()
  {
    parent::__construct();
    //Codeigniter : Write Less Do More
    $this->load->dbforge();

  }

  public function CreateRefTable()
  {
    // Table Ref Object Type
    $fields = array(
      'code' => array(
        'type'       =>'VARCHAR',
        'constraint' => '3',
        'default'    => '',
      ),
      'name' => array(
        'type'       =>'VARCHAR',
        'constraint' => '15',
        'default'    => '',
      ),
    );
    $this->dbforge->add_field($fields);
    $this->dbforge->add_key('code', TRUE);
    $this->dbforge->create_table($this->tblRefObj,TRUE,$this->attributes);

    $data = array(
      array(
        'code' => 'EMP',
        'name' => 'Employee',
      ),
      array(
        'code' => 'JOB',
        'name' => 'Job',
      ),
      array(
        'code' => 'ORG',
        'name' => 'Organization',
      ),
      array(
        'code' => 'POS',
        'name' => 'Position',
      ),
    );
    $this->db->insert_batch($this->tblRefObj, $data);
    // -------------------------------------------
    // Table Ref Relation Type
    $fields = array(
      'code' => array(
        'type'       =>'VARCHAR',
        'constraint' => '3',
        'default'    => '',
      ),
      'top' => array(
        'type'       =>'VARCHAR',
        'constraint' => '3',
        'default'    => '',
      ),
      'bottom' => array(
        'type'       =>'VARCHAR',
        'constraint' => '3',
        'default'    => '',
      ),
      'description' => array(
        'type'       =>'VARCHAR',
        'constraint' => '120',
        'default'    => '',
      ),
      'has_many' => array(
        'type'       =>'TINYINT',
        'constraint' => '1',
        'default'    => '0',
      ),
    );
    $this->dbforge->add_field($fields);
    $this->dbforge->add_key('code', TRUE);
    $this->dbforge->create_table($this->tblRefRel,TRUE,$this->attributes);

    $data = array(
      array(
        'code'        => '101',
        'top'         => 'ORG',
        'bottom'      => 'ORG',
        'description' => 'Organization Structure',
        'has_many'    => '1',
      ),
      array(
        'code'        => '102',
        'top'         => 'POS',
        'bottom'      => 'POS',
        'description' => 'Reporting Structure',
        'has_many'    => '1',
      ),
      array(
        'code'        => '201',
        'top'         => 'ORG',
        'bottom'      => 'POS',
        'description' => 'Position Assignment to Organization',
        'has_many'    => '1',
      ),
      array(
        'code'        => '202',
        'top'         => 'ORG',
        'bottom'      => 'POS',
        'description' => 'Chief of Organization',
        'has_many'    => '0',
      ),
      array(
        'code'        => '301',
        'top'         => 'POS',
        'bottom'      => 'EMP',
        'description' => 'Employee Assignment to a position',
        'has_many'    => '0',
      ),
      array(
        'code'        => '401',
        'top'         => 'JOB',
        'bottom'      => 'POS',
        'description' => 'Associating position with a job',
        'has_many'    => '0',
      ),
    );
    $this->db->insert_batch($this->tblRefRel, $data);
    // -------------------------------------------
  }

  public function CreateTable()
  {

    $genField = array(
      'id' => array(
        'type' => 'INT',
        'constraint' => 11,
        'unsigned' => TRUE,
        'auto_increment' => TRUE
      ),
      'begin_date' => array(
        'type'    => 'DATE',
        'default' => '2000-01-01',
      ),
      'end_date' => array(
        'type'    => 'DATE',
        'default' => '9999-01-01',
      ),
      'is_delete' => array(
        'type'       => 'TINYINT',
        'constraint' => 1,
        'default'    => '0',
      ),
      'create_time' => array(
        'type'    =>'DATETIME',
      ),
      'timestamp' => array(
        'type'    =>'timestamp',
      ),
    );

    // Table Object
    $fields = array(
      'type' => array(
        'type'       =>'VARCHAR',
        'constraint' => '3',
        'default'    => 'POS',
      ),
    );
    $this->dbforge->add_field($genField);
    $this->dbforge->add_field($fields);
    $this->dbforge->add_key('id', TRUE);
    $this->dbforge->create_table($this->tblObj,TRUE,$this->attributes);
    // -------------------------------------------
    // Table attributes
    $fields = array(
      'obj_id' => array(
        'type'       =>'INT',
        'constraint' => '11',
      ),
      'name' => array(
        'type'       =>'VARCHAR',
        'constraint' => '150',
        'default'    => '',
      ),
    );
    $this->dbforge->add_field($genField);
    $this->dbforge->add_field($fields);
    $this->dbforge->add_key('id', TRUE);
    $this->dbforge->create_table($this->tblAttr,TRUE,$this->attributes);
    // -------------------------------------------
    // Table Relation
    $fields = array(
      'rel_code' => array(
        'type'       =>'VARCHAR',
        'constraint' => '3',
      ),
      'obj_top_id' => array(
        'type'       =>'INT',
        'constraint' => '11',
      ),
      'obj_bottom_id' => array(
        'type'       =>'INT',
        'constraint' => '11',
      ),
    );
    $this->dbforge->add_field($genField);
    $this->dbforge->add_field($fields);
    $this->dbforge->add_key('id', TRUE);
    $this->dbforge->create_table($this->tblRel,TRUE,$this->attributes);
    // -------------------------------------------
    $data = array(
      'type'        => 'ORG',
      'begin_date'  => '2000-01-01',
      'end_date'    => '9999-12-31',
      'create_time' => date('Y-m-d H:i:s'),
      'timestamp'   => date('Y-m-d H:i:s'),
    );
    $this->db->insert($this->tblObj, $data);
    $data = array(
      'obj_id'      => 1,
      'name'        => 'Holding Company',
      'begin_date'  => '2000-01-01',
      'end_date'    => '9999-12-31',
      'create_time' => date('Y-m-d H:i:s'),
      'timestamp'   => date('Y-m-d H:i:s'),
    );
    $this->db->insert($this->tblAttr, $data);
  }

  public function InsertDemoRecords()
  {

  }

  public function DropTable()
  {
    # code...
  }

}
