<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class SetupModel extends CI_Model{

  private $tblObj     = 'obj';
  private $tblAttr    = 'obj_attribute';
  private $tblRel     = 'obj_rel';
  private $tblRefObj  = 'ref_obj_type';
  private $tblRefRel  = 'ref_obj_rel';
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
        'after'      => 'id',

      ),
    );
    $this->dbforge->add_field($genField);
    $this->dbforge->add_key('id', TRUE);
    $this->dbforge->create_table($this->tblObj,TRUE,$this->attributes);
    $this->dbforge->add_column($this->tblObj,$fields);

    // -------------------------------------------
    // Table attributes
    $fields = array(
      'obj_id' => array(
        'type'       =>'INT',
        'constraint' => '11',
        'after'      => 'id',
      ),
      'name' => array(
        'type'       =>'VARCHAR',
        'constraint' => '150',
        'default'    => '',
        'after'      => 'obj_id',
      ),
    );
    $this->dbforge->add_field($genField);
    $this->dbforge->add_key('id', TRUE);
    $this->dbforge->create_table($this->tblAttr,TRUE,$this->attributes);
    $this->dbforge->add_column($this->tblAttr,$fields);

    // -------------------------------------------
    // Table Relation
    $fields = array(
      'rel_code' => array(
        'type'       =>'VARCHAR',
        'constraint' => '3',
        'after'      => 'id',

      ),
      'obj_top_id' => array(
        'type'       =>'INT',
        'constraint' => '11',
        'after'      => 'rel_code',
      ),
      'obj_bottom_id' => array(
        'type'       =>'INT',
        'constraint' => '11',
        'after'      => 'obj_top_id',
      ),
    );
    $this->dbforge->add_field($genField);
    $this->dbforge->add_key('id', TRUE);
    $this->dbforge->create_table($this->tblRel,TRUE,$this->attributes);
    $this->dbforge->add_column($this->tblRel,$fields);
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
      // 'timestamp'   => date('Y-m-d H:i:s'),
    );
    $this->db->insert($this->tblAttr, $data);
  }

  public function InsertDemoRecords()
  {
    $begin = '2000-01-01';
    $end   = '9999-12-31';
    // Object
    $data = array(
      'ORG' => array('min' => 2, 'max' => 8),
      'JOB' => array('min' => 9, 'max' => 18),
      'POS' => array('min' => 19, 'max' => 50),
      'EMP' => array('min' => 51, 'max' => 91),
    );
    $offset = $data['ORG']['min'];
    $set    = array(
      'Business Division',
      'Support Division',
      'Production Department',
      'Sales & Marketing Department',
      'Finance Department',
      'Human Resources Department',
      'Facility Department',
      'Top Managerial',
      'Middle Managerial',
      'Line Manager',
      'Secretary',
      'Designer',
      'Engineer',
      'Operator',
      'Clerk',
      'Officer',
      'Analyst',
      'CEO',
      'Business GM',
      'Supporting GM',
      'Production Manager',
      'Sales & Marketing Manager',
      'Finance Manager',
      'Human Resources Manager',
      'Facility Manager',
      'Secretary',
      'Secretary',
      'Secretary',
      'Industrial Designer',
      'Tech Designer',
      'Machine Operator',
      'Machine Operator',
      'Machine Operator',
      'Multimedia Designer',
      'Sales Executive',
      'Sales Executive',
      'Sales Executive',
      'Sales Executive',
      'Market Analyst',
      'Accountant',
      'Accountant',
      'Accountant',
      'Administrative Assistant',
      'Administrative Assistant',
      'HR Officer',
      'HR Officer',
      'Administrative Assistant',
      'Administrative Assistant',
      'IT Staff',
      'Myrta Deas',
      'Dann Bybee',
      'Aretha Deherrera',
      'Shondra Eggen',
      'Miranda Rodreguez',
      'Tobias Choice',
      'Bert Shear',
      'Raymonde Bultman',
      'Brianna Bissonette',
      'Adrianna Coy',
      'Jim Mento',
      'Alex Capote',
      'Marty Sowders',
      'Santina Ruland',
      'Gilberte Boedeker',
      'Refugio Mickel',
      'Chia Altamirano',
      'Mercedez Perrella',
      'Paola Acord',
      'Carlo Chmura',
      'Zulema Goldstein',
      'Adell Dickerson',
      'Thelma Boulanger',
      'Cherly Broman',
      'Delmer Segarra',
      'Jayna Verdejo',
      'Solomon Pietila',
      'Lala Calvert',
      'Eldora Mccall',
      'Dalila Scot',
      'John Smith',
      'Ira Granada',
      'Horace Carico',
      'Kayla Soules',
      'Vince Mitcham',
      'Alesha Roder',
      'Yuri Pool',
      'Daphne Howes',
      'Cristal Sanders',
      'Georgianne Caswell',
      'India Levay',
    );
    foreach ($data as $objType => $value) {
      $min = $value['min'];
      $max = $value['max'];
      for ($j=$min; $j <= $max ; $j++) {
        $data = array(
          'type'        => $objType,
          'begin_date'  => $begin,
          'end_date'    => $end,
          'create_time' => date('Y-m-d H:i:s'),
          // 'timestamp'   => date('Y-m-d H:i:s'),
        );
        $this->db->insert($this->tblObj, $data);
        $data = array(
          'obj_id'      => $j,
          'name'        => $set[$j-$offset],
          'begin_date'  => $begin,
          'end_date'    => $end,
          'create_time' => date('Y-m-d H:i:s'),
          // 'timestamp'   => date('Y-m-d H:i:s'),
        );
        $this->db->insert($this->tblAttr, $data);
      }
    }

    $set = array(
      array('101', 1, 2 ),
      array('101', 1, 3 ),
      array('101', 2, 4 ),
      array('101', 2, 5 ),
      array('101', 3, 6 ),
      array('101', 3, 7 ),
      array('101', 3, 8 ),
      array('102', 19, 20 ),
      array('102', 19, 21 ),
      array('102', 19, 27 ),
      array('102', 20, 22 ),
      array('102', 20, 23 ),
      array('102', 20, 28 ),
      array('102', 21, 24 ),
      array('102', 21, 25 ),
      array('102', 21, 26 ),
      array('102', 21, 29 ),
      array('102', 22, 30 ),
      array('102', 22, 31 ),
      array('102', 22, 32 ),
      array('102', 22, 33 ),
      array('102', 22, 34 ),
      array('102', 23, 35 ),
      array('102', 23, 36 ),
      array('102', 23, 37 ),
      array('102', 23, 38 ),
      array('102', 23, 39 ),
      array('102', 23, 30 ),
      array('102', 24, 41 ),
      array('102', 24, 42 ),
      array('102', 24, 43 ),
      array('102', 24, 44 ),
      array('102', 24, 45 ),
      array('102', 25, 46 ),
      array('102', 25, 47 ),
      array('102', 25, 48 ),
      array('102', 26, 49 ),
      array('102', 26, 50 ),
      array('201', 1, 19 ),
      array('202', 1, 19 ),
      array('201', 2, 20 ),
      array('202', 2, 20 ),
      array('201', 3, 21 ),
      array('202', 3, 21 ),
      array('201', 4, 22 ),
      array('202', 4, 22 ),
      array('201', 5, 23 ),
      array('202', 5, 23 ),
      array('201', 6, 24 ),
      array('202', 6, 24 ),
      array('201', 7, 25 ),
      array('202', 7, 25 ),
      array('201', 8, 26 ),
      array('202', 8, 26 ),
      array('201', 1, 27 ),
      array('201', 2, 28 ),
      array('201', 3, 29 ),
      array('201', 4, 30 ),
      array('201', 4, 31 ),
      array('201', 4, 32 ),
      array('201', 4, 33 ),
      array('201', 4, 34 ),
      array('201', 5, 35 ),
      array('201', 5, 36 ),
      array('201', 5, 37 ),
      array('201', 5, 38 ),
      array('201', 5, 39 ),
      array('201', 5, 40 ),
      array('201', 6, 41 ),
      array('201', 6, 42 ),
      array('201', 6, 43 ),
      array('201', 6, 44 ),
      array('201', 6, 45 ),
      array('201', 7, 46 ),
      array('201', 7, 47 ),
      array('201', 7, 48 ),
      array('201', 8, 49 ),
      array('201', 8, 50 ),
      array('301', 19, 51 ),
      array('301', 20, 52 ),
      array('301', 21, 53 ),
      array('301', 22, 54 ),
      array('301', 23, 55 ),
      array('301', 24, 56 ),
      array('301', 25, 57 ),
      array('301', 26, 58 ),
      array('301', 27, 59 ),
      array('301', 28, 60 ),
      array('301', 29, 61 ),
      array('301', 30, 62 ),
      array('301', 31, 63 ),
      array('301', 32, 64 ),
      array('301', 33, 65 ),
      array('301', 34, 66 ),
      array('301', 35, 67 ),
      array('301', 36, 68 ),
      array('301', 37, 69 ),
      array('301', 38, 70 ),
      array('301', 39, 71 ),
      array('301', 40, 72 ),
      array('301', 41, 73 ),
      array('301', 42, 74 ),
      array('301', 43, 75 ),
      array('301', 44, 76 ),
      array('301', 45, 77 ),
      array('301', 46, 78 ),
      array('301', 47, 79 ),
      array('301', 48, 80 ),
      array('301', 49, 81 ),
      array('301', 50, 82 ),
      array('401', 9, 19 ),
      array('401', 10, 20 ),
      array('401', 10, 21 ),
      array('401', 11, 22 ),
      array('401', 11, 23 ),
      array('401', 11, 24 ),
      array('401', 11, 25 ),
      array('401', 11, 26 ),
      array('401', 12, 27 ),
      array('401', 12, 28 ),
      array('401', 12, 29 ),
      array('401', 13, 30 ),
      array('401', 13, 35 ),
      array('401', 14, 31 ),
      array('401', 14, 50 ),
      array('401', 15, 32 ),
      array('401', 15, 33 ),
      array('401', 15, 34 ),
      array('401', 16, 44 ),
      array('401', 16, 45 ),
      array('401', 16, 48 ),
      array('401', 16, 49 ),
      array('401', 17, 37 ),
      array('401', 17, 38 ),
      array('401', 17, 39 ),
      array('401', 17, 46 ),
      array('401', 17, 47 ),
      array('401', 18, 40 ),
    );
    foreach ($set as $key => $value) {
      $data = array(
        'rel_code'      => $value[0],
        'obj_top_id'    => $value[1],
        'obj_bottom_id' => $value[2],
        'begin_date'    => $begin,
        'end_date'      => $end,
        'create_time'   => date('Y-m-d H:i:s'),
        // 'timestamp'     => date('Y-m-d H:i:s'),
      );
      $this->db->insert($this->tblRel, $data);

    }
  }

  public function DropTable()
  {
    $this->dbforge->drop_table($this->tblRel,TRUE);
    $this->dbforge->drop_table($this->tblAttr,TRUE);
    $this->dbforge->drop_table($this->tblObj,TRUE);
    $this->dbforge->drop_table($this->tblRefObj,TRUE);
    $this->dbforge->drop_table($this->tblRefRel,TRUE);
  }

}
