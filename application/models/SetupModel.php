<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class SetupModel extends CI_Model{

  private $tblObj     = 'obj';
  private $tblAttr    = 'obj_attribute';
  private $tblRel     = 'obj_rel';
  private $tblRefObj  = 'ref_obj_type';
  private $tblRefRel  = 'ref_obj_rel';
  private $attributes = array('ENGINE' => 'InnoDB');
  // Relation Code (Ref to ref_obj_rel)
  private $relStruct = '101';
  private $relReport = '102';
  private $relAssign = '201';
  private $relChief  = '202';
  private $relHold   = '301';
  private $relJob    = '401';

  private $objOrg    = 'ORG';
  private $objPost   = 'POS';
  private $objJob    = 'JOB';
  private $objPerson = 'EMP';

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
        'code' => $this->objPerson,
        'name' => 'Employee',
      ),
      array(
        'code' => $this->objJob,
        'name' => 'Job',
      ),
      array(
        'code' => $this->objOrg,
        'name' => 'Organization',
      ),
      array(
        'code' => $this->objPost,
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
        'code'        => $this->relStruct,
        'top'         => $this->objOrg,
        'bottom'      => $this->objOrg,
        'description' => 'Organization Structure',
        'has_many'    => '1',
      ),
      array(
        'code'        => $this->relReport,
        'top'         => $this->objPost,
        'bottom'      => $this->objPost,
        'description' => 'Reporting Structure',
        'has_many'    => '1',
      ),
      array(
        'code'        => $this->relAssign,
        'top'         => $this->objOrg,
        'bottom'      => $this->objPost,
        'description' => 'Position Assignment to Organization',
        'has_many'    => '1',
      ),
      array(
        'code'        => $this->relChief,
        'top'         => $this->objOrg,
        'bottom'      => $this->objPost,
        'description' => 'Chief of Organization',
        'has_many'    => '0',
      ),
      array(
        'code'        => $this->relHold,
        'top'         => $this->objPost,
        'bottom'      => $this->objPerson,
        'description' => 'Employee Assignment to a position',
        'has_many'    => '0',
      ),
      array(
        'code'        => $this->relJob,
        'top'         => $this->objJob,
        'bottom'      => $this->objPost,
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
        'default'    => $this->objPost,
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
      'type'        => $this->objOrg,
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
      $this->objOrg => array('min' => 2, 'max' => 8),
      $this->objJob => array('min' => 9, 'max' => 18),
      $this->objPost => array('min' => 19, 'max' => 50),
      $this->objPerson => array('min' => 51, 'max' => 91),
    );
    $offset = $data[$this->objOrg]['min'];
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
      array($this->relStruct, 1, 2 ),
      array($this->relStruct, 1, 3 ),
      array($this->relStruct, 2, 4 ),
      array($this->relStruct, 2, 5 ),
      array($this->relStruct, 3, 6 ),
      array($this->relStruct, 3, 7 ),
      array($this->relStruct, 3, 8 ),
      array($this->relReport, 19, 20 ),
      array($this->relReport, 19, 21 ),
      array($this->relReport, 19, 27 ),
      array($this->relReport, 20, 22 ),
      array($this->relReport, 20, 23 ),
      array($this->relReport, 20, 28 ),
      array($this->relReport, 21, 24 ),
      array($this->relReport, 21, 25 ),
      array($this->relReport, 21, 26 ),
      array($this->relReport, 21, 29 ),
      array($this->relReport, 22, 30 ),
      array($this->relReport, 22, 31 ),
      array($this->relReport, 22, 32 ),
      array($this->relReport, 22, 33 ),
      array($this->relReport, 22, 34 ),
      array($this->relReport, 23, 35 ),
      array($this->relReport, 23, 36 ),
      array($this->relReport, 23, 37 ),
      array($this->relReport, 23, 38 ),
      array($this->relReport, 23, 39 ),
      array($this->relReport, 23, 30 ),
      array($this->relReport, 24, 41 ),
      array($this->relReport, 24, 42 ),
      array($this->relReport, 24, 43 ),
      array($this->relReport, 24, 44 ),
      array($this->relReport, 24, 45 ),
      array($this->relReport, 25, 46 ),
      array($this->relReport, 25, 47 ),
      array($this->relReport, 25, 48 ),
      array($this->relReport, 26, 49 ),
      array($this->relReport, 26, 50 ),
      array($this->relAssign, 1, 19 ),
      array($this->relChief, 1, 19 ),
      array($this->relAssign, 2, 20 ),
      array($this->relChief, 2, 20 ),
      array($this->relAssign, 3, 21 ),
      array($this->relChief, 3, 21 ),
      array($this->relAssign, 4, 22 ),
      array($this->relChief, 4, 22 ),
      array($this->relAssign, 5, 23 ),
      array($this->relChief, 5, 23 ),
      array($this->relAssign, 6, 24 ),
      array($this->relChief, 6, 24 ),
      array($this->relAssign, 7, 25 ),
      array($this->relChief, 7, 25 ),
      array($this->relAssign, 8, 26 ),
      array($this->relChief, 8, 26 ),
      array($this->relAssign, 1, 27 ),
      array($this->relAssign, 2, 28 ),
      array($this->relAssign, 3, 29 ),
      array($this->relAssign, 4, 30 ),
      array($this->relAssign, 4, 31 ),
      array($this->relAssign, 4, 32 ),
      array($this->relAssign, 4, 33 ),
      array($this->relAssign, 4, 34 ),
      array($this->relAssign, 5, 35 ),
      array($this->relAssign, 5, 36 ),
      array($this->relAssign, 5, 37 ),
      array($this->relAssign, 5, 38 ),
      array($this->relAssign, 5, 39 ),
      array($this->relAssign, 5, 40 ),
      array($this->relAssign, 6, 41 ),
      array($this->relAssign, 6, 42 ),
      array($this->relAssign, 6, 43 ),
      array($this->relAssign, 6, 44 ),
      array($this->relAssign, 6, 45 ),
      array($this->relAssign, 7, 46 ),
      array($this->relAssign, 7, 47 ),
      array($this->relAssign, 7, 48 ),
      array($this->relAssign, 8, 49 ),
      array($this->relAssign, 8, 50 ),
      array($this->relHold, 19, 51 ),
      array($this->relHold, 20, 52 ),
      array($this->relHold, 21, 53 ),
      array($this->relHold, 22, 54 ),
      array($this->relHold, 23, 55 ),
      array($this->relHold, 24, 56 ),
      array($this->relHold, 25, 57 ),
      array($this->relHold, 26, 58 ),
      array($this->relHold, 27, 59 ),
      array($this->relHold, 28, 60 ),
      array($this->relHold, 29, 61 ),
      array($this->relHold, 30, 62 ),
      array($this->relHold, 31, 63 ),
      array($this->relHold, 32, 64 ),
      array($this->relHold, 33, 65 ),
      array($this->relHold, 34, 66 ),
      array($this->relHold, 35, 67 ),
      array($this->relHold, 36, 68 ),
      array($this->relHold, 37, 69 ),
      array($this->relHold, 38, 70 ),
      array($this->relHold, 39, 71 ),
      array($this->relHold, 40, 72 ),
      array($this->relHold, 41, 73 ),
      array($this->relHold, 42, 74 ),
      array($this->relHold, 43, 75 ),
      array($this->relHold, 44, 76 ),
      array($this->relHold, 45, 77 ),
      array($this->relHold, 46, 78 ),
      array($this->relHold, 47, 79 ),
      array($this->relHold, 48, 80 ),
      array($this->relHold, 49, 81 ),
      array($this->relHold, 50, 82 ),
      array($this->relJob, 9, 19 ),
      array($this->relJob, 10, 20 ),
      array($this->relJob, 10, 21 ),
      array($this->relJob, 11, 22 ),
      array($this->relJob, 11, 23 ),
      array($this->relJob, 11, 24 ),
      array($this->relJob, 11, 25 ),
      array($this->relJob, 11, 26 ),
      array($this->relJob, 12, 27 ),
      array($this->relJob, 12, 28 ),
      array($this->relJob, 12, 29 ),
      array($this->relJob, 13, 30 ),
      array($this->relJob, 13, 35 ),
      array($this->relJob, 14, 31 ),
      array($this->relJob, 14, 50 ),
      array($this->relJob, 15, 32 ),
      array($this->relJob, 15, 33 ),
      array($this->relJob, 15, 34 ),
      array($this->relJob, 16, 44 ),
      array($this->relJob, 16, 45 ),
      array($this->relJob, 16, 48 ),
      array($this->relJob, 16, 49 ),
      array($this->relJob, 17, 37 ),
      array($this->relJob, 17, 38 ),
      array($this->relJob, 17, 39 ),
      array($this->relJob, 17, 41 ),
      array($this->relJob, 17, 42 ),
      array($this->relJob, 17, 43 ),
      array($this->relJob, 17, 46 ),
      array($this->relJob, 17, 47 ),
      array($this->relJob, 18, 40 ),
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
