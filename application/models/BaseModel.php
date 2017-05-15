<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class BaseModel extends CI_Model{

  public $tblObj  = 'obj';
  public $tblAttr = 'obj_attribute';
  public $tblRel  = 'obj_rel';

  public function __construct()
  {
    parent::__construct();
    //Codeigniter : Write Less Do More
  }

  public function Create($type='',$name='',$begin='1990-01-01',$end='9999-12-31')
  {
    $data = array(
      'type'       => strtoupper($type),
      'begin_date' => $begin,
      'end_date'   => $end,
    );
    $objId = $this->InsertOn($this->tblObj, $data);

    $data = array(
      'obj_id'     => $objId,
      'name'       => $name,
      'begin_date' => $begin,
      'end_date'   => $end,
    );
    $this->InsertOn($this->tblAttr, $data);
    return $objId;
  }

  public function ChangeName($objId=0,$newName='',$validOn='',$endDate='9999-12-31')
  {
    if ($validOn == '') {
      $validOn = date('Y-m-d');
    }

    $this->db->select('id');
    $this->db->where('obj_id', $objId);
    $this->db->order_by('end_date','desc');
    $row    = $this->db->get($this->tblAttr)->row();

    $attId    = $row->id;
    $data     = array(
      'end_date' => date('Y-m-d',strtotime($validOn . '-1 days')),
    );
    $this->ChangeOn($this->tblAttr,$attId,$data);

    $data = array(
      'obj_id'     => $objId,
      'name'       => $newName,
      'begin_date' => $validOn,
      'end_date'   => $endDate,
    );
    $this->InsertOn($this->tblAttr,$data);
  }


  public function Delimit($objId=0,$endDate='')
  {
    $old = $this->GetByIdRow($objId);
    if ($endDate == '') {
      $endDate = date('Y-m-d');
    }
    $data = array(
      'end_date' => $endDate,
      'timestamp' => date('Y-m-d H:i:s'),
    );

    // Delimit Object
    $this->ChangeOn($this->tblObj,$objId);
    // TODO Delimit Attribut terakhir yang masih aktif
    // $attrId = $this->GetLastAttr($objId,$endDate)->id;
    // $this->db->where('id',$attrId);
    $this->db->where('obj_id', $objId);
    $this->db->where('is_delete', FALSE);
    $this->db->where('end_date ', $old->end_date);
    $this->db->update($this->tblAttr,$data);

    // TODO Delimit semua relasi topDown yang masih aktif
    $this->db->where('obj_top_id', $objId);
    $this->db->where('is_delete', FALSE);
    $this->db->where('end_date ', $old->end_date);
    $this->db->update($this->tblRel, $data);

    // TODO Delimit semua relasi botUp yang masih aktif
    $this->db->where('obj_bottom_id', $objId);
    $this->db->where('is_delete', FALSE);
    $this->db->where('end_date ', $old->end_date);
    $this->db->update($this->tblRel, $data);
  }

  public function GetByIdRow($id=0)
  {
    $this->db->where('is_delete', 0);
    return $this->db->get($this->tblObj, 1, 0)->row();
  }

  public function GetList($type='',$keydate='')
  {
    if (!is_array($keydate) && $keydate == '') {
      $keydate = date('Y-m-d');
    }
    // Sub Query 1

    $this->db->select('sub.name');
    $this->db->where('sub.is_delete', FALSE);
    $this->db->where('sub.obj_id = obj.id');
    $this->db->from($this->tblAttr .' sub');
    $this->db->order_by('end_date','desc');
    $this->db->limit(1,0);
    if (!is_array($keydate)) {
      $this->db->where('sub.begin_date >=', $keydate);
      $this->db->where('sub.end_date <=', $keydate);
    } else {
      $this->db->group_start();
        $this->db->group_start();
          $this->db->where('sub.begin_date >=', $keydate['begin']);
          $this->db->where('sub.end_date <=', $keydate['end']);
        $this->db->group_end();
        $this->db->or_group_start();
          $this->db->where('sub.end_date >=', $keydate['begin']);
          $this->db->where('sub.end_date <=', $keydate['end']);
        $this->db->group_end();
        $this->db->or_group_start();
          $this->db->where('sub.begin_date >=', $keydate['begin']);
          $this->db->where('sub.begin_date <=', $keydate['end']);
        $this->db->group_end();
        $this->db->or_group_start();
          $this->db->where('sub.begin_date <=', $keydate['begin']);
          $this->db->where('sub.end_date >=', $keydate['end']);
        $this->db->group_end();
      $this->db->group_end();
      // $this->db->not_group_start();
      //   $this->db->group_start();
      //     $this->db->where('sub.begin_date <=', $keydate['begin']);
      //     $this->db->where('sub.end_date <=', $keydate['begin']);
      //   $this->db->group_end();
      //   $this->db->or_group_start();
      //     $this->db->where('sub.begin_date >=', $keydate['end']);
      //     $this->db->where('sub.end_date >=', $keydate['end']);
      //   $this->db->group_end();
      // $this->db->group_end();

    }
    $subQuery = $this->db->get_compiled_select();
    $this->db->select('obj.id');
    $this->db->select('obj.begin_date');
    $this->db->select('obj.end_date');

    $this->db->select('('.$subQuery .' ) AS name');

    $this->db->where('obj.type', $type);
    $this->db->where('obj.is_delete', FALSE);

    if (!is_array($keydate)) {
      $this->db->where('obj.begin_date >=', $keydate);
      $this->db->where('obj.end_date <=', $keydate);
    } else {
      $this->db->group_start();
        $this->db->group_start();
          $this->db->where('obj.begin_date >=', $keydate['begin']);
          $this->db->where('obj.end_date <=', $keydate['end']);
        $this->db->group_end();
        $this->db->or_group_start();
          $this->db->where('obj.end_date >=', $keydate['begin']);
          $this->db->where('obj.end_date <=', $keydate['end']);
        $this->db->group_end();
        $this->db->or_group_start();
          $this->db->where('obj.begin_date >=', $keydate['begin']);
          $this->db->where('obj.begin_date <=', $keydate['end']);
        $this->db->group_end();
        $this->db->or_group_start();
          $this->db->where('obj.begin_date <=', $keydate['begin']);
          $this->db->where('obj.end_date >=', $keydate['end']);
        $this->db->group_end();
      $this->db->group_end();

    }

    return $this->db->get($this->tblObj)->result();

  }

  public function GetLastAttr($objId=0,$keydate='')
  {
    $this->db->where('obj_id', $objId);
    $this->db->where('is_delete', FALSE);

    if (!is_array($keydate)) {
      if ($keydate == '') {
        $keydate = date('Y-m-d');
      }
      $this->db->where('begin_date <=', $keydate);
      $this->db->where('end_date >=', $keydate);
    } else {
      $this->db->group_start();
        $this->db->group_start();
          $this->db->where('begin_date >=', $keydate['begin']);
          $this->db->where('end_date <=', $keydate['end']);
        $this->db->group_end();
        $this->db->or_group_start();
          $this->db->where('end_date >=', $keydate['begin']);
          $this->db->where('end_date <=', $keydate['end']);
        $this->db->group_end();
        $this->db->or_group_start();
          $this->db->where('begin_date >=', $keydate['begin']);
          $this->db->where('begin_date <=', $keydate['end']);
        $this->db->group_end();
        $this->db->or_group_start();
          $this->db->where('begin_date <=', $keydate['begin']);
          $this->db->where('end_date >=', $keydate['end']);
        $this->db->group_end();
      $this->db->group_end();

    }
    $this->db->order_by('end_date','desc');
    return $this->db->get($this->tblAttr)->row();
  }

  public function GetAttrList($objId=0,$keydate='',$sort='asc')
  {
    $this->db->where('obj_id', $objId);
    $this->db->where('is_delete', FALSE);

    if (!is_array($keydate)) {
      if ($keydate == '') {
        $keydate = date('Y-m-d');
      }
      $this->db->where('begin_date <=', $keydate);
      $this->db->where('end_date >=', $keydate);
    } else {
      $this->db->group_start();
        $this->db->group_start();
          $this->db->where('begin_date >=', $keydate['begin']);
          $this->db->where('end_date <=', $keydate['end']);
        $this->db->group_end();
        $this->db->or_group_start();
          $this->db->where('end_date >=', $keydate['begin']);
          $this->db->where('end_date <=', $keydate['end']);
        $this->db->group_end();
        $this->db->or_group_start();
          $this->db->where('begin_date >=', $keydate['begin']);
          $this->db->where('begin_date <=', $keydate['end']);
        $this->db->group_end();
        $this->db->or_group_start();
          $this->db->where('begin_date <=', $keydate['begin']);
          $this->db->where('end_date >=', $keydate['end']);
        $this->db->group_end();
      $this->db->group_end();

    }
    $this->db->order_by('end_date',$sort);
    return $this->db->get($this->tblAttr)->result();
  }

  public function CreateRel($relCode='',$topObjId=0,$botObjId=0,$begin='1990-01-01',$end='9999-12-31')
  {
    $data = array(
      'rel_code'      => $rel_code,
      'obj_top_id'    => $topObjId,
      'obj_bottom_id' => $botObjId,
      'begin_date'    => $begin,
      'end_date'      => $end,
    );
    $objId = $this->InsertOn($this->tblRel, $data);
  }

  public function ChangeRel($mode='BOTUP',$relCode='',$refId='',$newId='',$validOn='',$endDate='9999-12-31')
  {
    if ($validOn == '') {
      $validOn = date('Y-m-d');
    }

    $this->db->select('id');
    $this->db->where('rel_code', $rel_code);
    switch (strtoupper($mode)) {
      case 'BOTUP':
        $this->db->where('obj_bottom_id', $refId);

        break;

      default:
        $this->db->where('obj_top_id', $refId);

        break;
    }
    $this->db->where('is_delete', FALSE);
    $this->db->order_by('end_date');
    $relId = $this->db->get($this->tblRel, 1, 0)->row()->id;

    $data     = array(
      'end_date' => date('Y-m-d',strtotime($validOn . '-1 days')),
    );
    $this->ChangeOn($this->tblRel,$relId,$data);
    switch (strtoupper($mode)) {
      case 'BOTUP':
        $data             = array(
          'obj_top_id'    => $newId,
          'obj_bottom_id' => $refId,
          'rel_code'      => $relCode,
          'begin_date'    => $validOn,
          'end_date'      => $endDate,
        );

        break;

      default:
        $data             = array(
          'obj_top_id'    => $refId,
          'obj_bottom_id' => $newId,
          'rel_code'      => $relCode,
          'begin_date'    => $validOn,
          'end_date'      => $endDate,
        );

        break;
    }
    $this->InsertOn($this->tblRel,$data);
  }

  public function GetRelById($relId=0)
  {
    $this->db->where('id', $relId);
    $this->db->from($this->tblRel);
    return $this->db->get()->row();
  }
  public function CountTopDownRel($topObjId=0,$relCode=array(),$keydate='')
  {
    if (!is_array($keydate) && $keydate == '') {
      $keydate = date('Y-m-d');
    }
    $this->db->select('COUNT(rel_0.id) as val');
    $this->db->from($this->BaseModel->tblRel .' AS rel_0');
    $this->db->where('rel_0.obj_top_id', $topObjId);
    if (is_array($relCode)) {
      $count = count($relCode);
      if ($count > 1) {
        for ($i=1; $i < $count ; $i++) {
          $j = $i - 1;
          $this->db->join($this->BaseModel->tblRel .' AS rel_'.$i,'rel_'.$j.'.obj_bottom_id = rel_'.$i.'.obj_top_id');
        }
      }

      if (!is_array($keydate)) {
        for ($i=0; $i < $count ; $i++) {
          $this->db->where('rel_'.$i.'.begin_date >=', $keydate);
          $this->db->where('rel_'.$i.'.end_date <=', $keydate);
          if ($relCode[$i] != '') {
            $this->db->where('rel_'.$i.'.rel_code', $relCode[$i]);
          }
          $this->db->where('rel_'.$i.'.is_delete', FALSE);
        }

      } else {
        for ($i=0; $i < $count ; $i++) {
          $this->db->group_start();
            $this->db->group_start();
              $this->db->where('rel_'.$i.'.begin_date >=', $keydate['begin']);
              $this->db->where('rel_'.$i.'.end_date <=', $keydate['end']);
            $this->db->group_end();
            $this->db->or_group_start();
              $this->db->where('rel_'.$i.'.end_date >=', $keydate['begin']);
              $this->db->where('rel_'.$i.'.end_date <=', $keydate['end']);
            $this->db->group_end();
            $this->db->or_group_start();
              $this->db->where('rel_'.$i.'.begin_date >=', $keydate['begin']);
              $this->db->where('rel_'.$i.'.begin_date <=', $keydate['end']);
            $this->db->group_end();
            $this->db->or_group_start();
              $this->db->where('rel_'.$i.'.begin_date <=', $keydate['begin']);
              $this->db->where('rel_'.$i.'.end_date >=', $keydate['end']);
            $this->db->group_end();
          $this->db->group_end();


          if ($relCode[$i] != '') {
            $this->db->where('rel_'.$i.'.rel_code', $relCode[$i]);
          }
          $this->db->where('rel_'.$i.'.is_delete', FALSE);
        }
      }
    } else {

      if (!is_array($keydate)) {
        $this->db->where('rel_0.begin_date >=', $keydate);
        $this->db->where('rel_0.end_date <=', $keydate);
        if ($relCode != '') {
          $this->db->where('rel_0.rel_code', $relCode);
        }
        $this->db->where('rel_0.is_delete', FALSE);
      } else {
        // $this->db->where("NOT (rel_0.begin_date < '".$keydate['end']."' OR rel_0.end_date > '".$keydate['begin']."' )");
        $this->db->group_start();
          $this->db->group_start();
            $this->db->where('rel_0.begin_date >=', $keydate['begin']);
            $this->db->where('rel_0.end_date <=', $keydate['end']);
          $this->db->group_end();
          $this->db->or_group_start();
            $this->db->where('rel_0.end_date >=', $keydate['begin']);
            $this->db->where('rel_0.end_date <=', $keydate['end']);
          $this->db->group_end();
          $this->db->or_group_start();
            $this->db->where('rel_0.begin_date >=', $keydate['begin']);
            $this->db->where('rel_0.begin_date <=', $keydate['end']);
          $this->db->group_end();
          $this->db->or_group_start();
            $this->db->where('rel_0.begin_date <=', $keydate['begin']);
            $this->db->where('rel_0.end_date >=', $keydate['end']);
          $this->db->group_end();
        $this->db->group_end();

        if ($relCode != '') {
          $this->db->where('rel_0.rel_code', $relCode);
        }
        $this->db->where('rel_0.is_delete', FALSE);

      }
    }
    return $this->db->get()->row()->val;
  }

  public function GetTopDownRelList($topObjId=0,$relCode='',$keydate='',$alias='')
  {
    if (!is_array($keydate) && $keydate == '') {
      $keydate = date('Y-m-d');
    }
    // Sub Query 1

    $this->db->select('sub.name');
    $this->db->where('sub.is_delete', FALSE);
    $this->db->where('sub.obj_id = rel_NUM.obj_bottom_id');
    $this->db->from($this->tblAttr .' sub');
    $this->db->order_by('end_date','desc');
    $this->db->limit(1,0);
    if (!is_array($keydate)) {
      $this->db->where('sub.begin_date >=', $keydate);
      $this->db->where('sub.end_date <=', $keydate);
    } else {
      $this->db->group_start();
        $this->db->group_start();
          $this->db->where('sub.begin_date >=', $keydate['begin']);
          $this->db->where('sub.end_date <=', $keydate['end']);
        $this->db->group_end();
        $this->db->or_group_start();
          $this->db->where('sub.end_date >=', $keydate['begin']);
          $this->db->where('sub.end_date <=', $keydate['end']);
        $this->db->group_end();
        $this->db->or_group_start();
          $this->db->where('sub.begin_date >=', $keydate['begin']);
          $this->db->where('sub.begin_date <=', $keydate['end']);
        $this->db->group_end();
        $this->db->or_group_start();
          $this->db->where('sub.begin_date <=', $keydate['begin']);
          $this->db->where('sub.end_date >=', $keydate['end']);
        $this->db->group_end();
      $this->db->group_end();
    }
    $subQuery = $this->db->get_compiled_select();
    // END OF Sub Query 1

    $this->db->from($this->BaseModel->tblRel .' AS rel_0');
    $this->db->where('rel_0.obj_top_id', $topObjId);
    if (is_array($relCode)) {
      $count = count($relCode);
      // sub query 1
      for ($i=0; $i < $count ; $i++) {

        $select = str_replace('NUM',$i,$subQuery);
        if (is_array($alias) && $alias[$i] !='') {
          $this->db->select('rel_'.$i.'.obj_bottom_id AS '. $alias[$i].'_id');
          $this->db->select('('.$select.') AS '. $alias[$i].'_name');
        } else {
          $this->db->select('rel_'.$i.'.obj_bottom_id AS obj_'. $i.'_id');
          $this->db->select('('.$select.') AS obj_'.$i.'_name');

        }
      }
      // end of sub query 1
      if ($count > 1) {
        for ($i=1; $i < $count ; $i++) {
          $j = $i - 1;
          $this->db->join($this->BaseModel->tblRel .' AS rel_'.$i,'rel_'.$j.'.obj_bottom_id = rel_'.$i.'.obj_top_id');
        }
      }

      if (!is_array($keydate)) {
        for ($i=0; $i < $count ; $i++) {
          $this->db->where('rel_'.$i.'.begin_date >=', $keydate);
          $this->db->where('rel_'.$i.'.end_date <=', $keydate);
          if ($relCode[$i] != '') {
            $this->db->where('rel_'.$i.'.rel_code', $relCode[$i]);
          }
          $this->db->where('rel_'.$i.'.is_delete', FALSE);

        }

      } else {
        for ($i=0; $i < $count ; $i++) {
          $this->db->group_start();
            $this->db->group_start();
              $this->db->where('rel_'.$i.'.begin_date >=', $keydate['begin']);
              $this->db->where('rel_'.$i.'.end_date <=', $keydate['end']);
            $this->db->group_end();
            $this->db->or_group_start();
              $this->db->where('rel_'.$i.'.end_date >=', $keydate['begin']);
              $this->db->where('rel_'.$i.'.end_date <=', $keydate['end']);
            $this->db->group_end();
            $this->db->or_group_start();
              $this->db->where('rel_'.$i.'.begin_date >=', $keydate['begin']);
              $this->db->where('rel_'.$i.'.begin_date <=', $keydate['end']);
            $this->db->group_end();
            $this->db->or_group_start();
              $this->db->where('rel_'.$i.'.begin_date <=', $keydate['begin']);
              $this->db->where('rel_'.$i.'.end_date >=', $keydate['end']);
            $this->db->group_end();
          $this->db->group_end();
          if ($relCode[$i] != '') {
            $this->db->where('rel_'.$i.'.rel_code', $relCode[$i]);
          }
          $this->db->where('rel_'.$i.'.is_delete', FALSE);

        }
      }
    } else {
      // Sub query 1
      $select = str_replace('NUM','0',$subQuery);
      if ($alias[$i] !='') {
        $this->db->select('rel_0.obj_bottom_id AS '. $alias.'_id');
        $this->db->select('('.$select.') AS '. $alias.'_name');
      } else {
        $this->db->select('rel_0.obj_bottom_id AS obj_id');
        $this->db->select('('.$select.') AS obj_name');

      }
      //  end of Sub query 1

      if (!is_array($keydate)) {
        $this->db->where('rel_0.begin_date >=', $keydate);
        $this->db->where('rel_0.end_date <=', $keydate);
        if ($relCode != '') {
          $this->db->where('rel_0.rel_code', $relCode);
        }
        $this->db->where('rel_0.is_delete', FALSE);
      } else {
        $this->db->group_start();
          $this->db->group_start();
            $this->db->where('rel_0.begin_date >=', $keydate['begin']);
            $this->db->where('rel_0.end_date <=', $keydate['end']);
          $this->db->group_end();
          $this->db->or_group_start();
            $this->db->where('rel_0.end_date >=', $keydate['begin']);
            $this->db->where('rel_0.end_date <=', $keydate['end']);
          $this->db->group_end();
          $this->db->or_group_start();
            $this->db->where('rel_0.begin_date >=', $keydate['begin']);
            $this->db->where('rel_0.begin_date <=', $keydate['end']);
          $this->db->group_end();
          $this->db->or_group_start();
            $this->db->where('rel_0.begin_date <=', $keydate['begin']);
            $this->db->where('rel_0.end_date >=', $keydate['end']);
          $this->db->group_end();
        $this->db->group_end();
        if ($relCode != '') {
          $this->db->where('rel_0.rel_code', $relCode);
        }
        $this->db->where('rel_0.is_delete', FALSE);

      }
    }
    return $this->db->get()->result();
  }

  public function CountBotUpRel($botObjId=0,$relCode='',$keydate='')
  {
    if (!is_array($keydate) && $keydate == '') {
      $keydate = date('Y-m-d');
    }
    $this->db->select('COUNT(rel_0.id) as val');
    $this->db->from($this->BaseModel->tblRel .' AS rel_0');
    $this->db->where('rel_0.obj_bottom_id', $botObjId);
    if (is_array($relCode)) {
      $count = count($relCode);
      if ($count > 1) {
        for ($i=1; $i < $count ; $i++) {
          $j = $i - 1;
          $this->db->join($this->BaseModel->tblRel .' AS rel_'.$i,'rel_'.$j.'.obj_top_id = rel_'.$i.'.obj_bottom_id');
        }
      }

      if (!is_array($keydate)) {
        for ($i=0; $i < $count ; $i++) {
          $this->db->where('rel_'.$i.'.begin_date >=', $keydate);
          $this->db->where('rel_'.$i.'.end_date <=', $keydate);
          if ($relCode[$i] != '') {
            $this->db->where('rel_'.$i.'.rel_code', $relCode[$i]);
          }
          $this->db->where('rel_'.$i.'.is_delete', FALSE);
        }

      } else {
        for ($i=0; $i < $count ; $i++) {
          $this->db->group_start();
            $this->db->group_start();
              $this->db->where('rel_'.$i.'.begin_date >=', $keydate['begin']);
              $this->db->where('rel_'.$i.'.end_date <=', $keydate['end']);
            $this->db->group_end();
            $this->db->or_group_start();
              $this->db->where('rel_'.$i.'.end_date >=', $keydate['begin']);
              $this->db->where('rel_'.$i.'.end_date <=', $keydate['end']);
            $this->db->group_end();
            $this->db->or_group_start();
              $this->db->where('rel_'.$i.'.begin_date >=', $keydate['begin']);
              $this->db->where('rel_'.$i.'.begin_date <=', $keydate['end']);
            $this->db->group_end();
            $this->db->or_group_start();
              $this->db->where('rel_'.$i.'.begin_date <=', $keydate['begin']);
              $this->db->where('rel_'.$i.'.end_date >=', $keydate['end']);
            $this->db->group_end();
          $this->db->group_end();

          if ($relCode[$i] != '') {
            $this->db->where('rel_'.$i.'.rel_code', $relCode[$i]);
          }
          $this->db->where('rel_'.$i.'.is_delete', FALSE);
        }
      }
    } else {

      if (!is_array($keydate)) {
        $this->db->where('rel_0.begin_date >=', $keydate);
        $this->db->where('rel_0.end_date <=', $keydate);
        if ($relCode != '') {
          $this->db->where('rel_0.rel_code', $relCode);
        }
        $this->db->where('rel_0.is_delete', FALSE);
      } else {
        $this->db->group_start();
          $this->db->group_start();
            $this->db->where('rel_0.begin_date >=', $keydate['begin']);
            $this->db->where('rel_0.end_date <=', $keydate['end']);
          $this->db->group_end();
          $this->db->or_group_start();
            $this->db->where('rel_0.end_date >=', $keydate['begin']);
            $this->db->where('rel_0.end_date <=', $keydate['end']);
          $this->db->group_end();
          $this->db->or_group_start();
            $this->db->where('rel_0.begin_date >=', $keydate['begin']);
            $this->db->where('rel_0.begin_date <=', $keydate['end']);
          $this->db->group_end();
          $this->db->or_group_start();
            $this->db->where('rel_0.begin_date <=', $keydate['begin']);
            $this->db->where('rel_0.end_date >=', $keydate['end']);
          $this->db->group_end();
        $this->db->group_end();
        if ($relCode != '') {
          $this->db->where('rel_0.rel_code', $relCode);
        }
        $this->db->where('rel_0.is_delete', FALSE);

      }
    }
    return $this->db->get()->row()->val;
  }

  public function GetBotUpRelList($botObjId=0,$relCode='',$keydate='',$alias='')
  {
    if (!is_array($keydate) && $keydate == '') {
      $keydate = date('Y-m-d');
    }
    // Sub Query 1

    $this->db->select('sub.name');
    $this->db->where('sub.is_delete', FALSE);
    $this->db->where('sub.obj_id = rel_NUM.obj_top_id');
    $this->db->from($this->tblAttr .' sub');
    $this->db->order_by('end_date','desc');
    $this->db->limit(1,0);
    if (!is_array($keydate)) {
      $this->db->where('sub.begin_date >=', $keydate);
      $this->db->where('sub.end_date <=', $keydate);
    } else {
      $this->db->group_start();
        $this->db->group_start();
          $this->db->where('sub.begin_date >=', $keydate['begin']);
          $this->db->where('sub.end_date <=', $keydate['end']);
        $this->db->group_end();
        $this->db->or_group_start();
          $this->db->where('sub.end_date >=', $keydate['begin']);
          $this->db->where('sub.end_date <=', $keydate['end']);
        $this->db->group_end();
        $this->db->or_group_start();
          $this->db->where('sub.begin_date >=', $keydate['begin']);
          $this->db->where('sub.begin_date <=', $keydate['end']);
        $this->db->group_end();
        $this->db->or_group_start();
          $this->db->where('sub.begin_date <=', $keydate['begin']);
          $this->db->where('sub.end_date >=', $keydate['end']);
        $this->db->group_end();
      $this->db->group_end();
    }
    $subQuery = $this->db->get_compiled_select();
    // END OF Sub Query 1

    $this->db->from($this->BaseModel->tblRel .' AS rel_0');
    $this->db->where('rel_0.obj_bottom_id', $botObjId);
    if (is_array($relCode)) {
      $count = count($relCode);
      // sub query 1
      for ($i=0; $i < $count ; $i++) {

        $select = str_replace('NUM',$i,$subQuery);
        if (is_array($alias) && $alias[$i] !='') {
          $this->db->select('rel_'.$i.'.obj_top_id AS '. $alias[$i].'_id');
          $this->db->select('('.$select.') AS '. $alias[$i].'_name');
        } else {
          $this->db->select('rel_'.$i.'.obj_top_id AS obj_'. $i.'_id');
          $this->db->select('('.$select.') AS obj_'.$i.'_name');

        }
      }
      // end of sub query 1
      if ($count > 1) {
        for ($i=1; $i < $count ; $i++) {
          $j = $i - 1;
          $this->db->join($this->BaseModel->tblRel .' AS rel_'.$i,'rel_'.$j.'.obj_top_id = rel_'.$i.'.obj_bottom_id');
        }
      }

      if (!is_array($keydate)) {
        for ($i=0; $i < $count ; $i++) {
          $this->db->where('rel_'.$i.'.begin_date >=', $keydate);
          $this->db->where('rel_'.$i.'.end_date <=', $keydate);
          if ($relCode[$i] != '') {
            $this->db->where('rel_'.$i.'.rel_code', $relCode[$i]);
          }
          $this->db->where('rel_'.$i.'.is_delete', FALSE);

        }

      } else {
        for ($i=0; $i < $count ; $i++) {
          $this->db->group_start();
            $this->db->group_start();
              $this->db->where('rel_'.$i.'.begin_date >=', $keydate['begin']);
              $this->db->where('rel_'.$i.'.end_date <=', $keydate['end']);
            $this->db->group_end();
            $this->db->or_group_start();
              $this->db->where('rel_'.$i.'.end_date >=', $keydate['begin']);
              $this->db->where('rel_'.$i.'.end_date <=', $keydate['end']);
            $this->db->group_end();
            $this->db->or_group_start();
              $this->db->where('rel_'.$i.'.begin_date >=', $keydate['begin']);
              $this->db->where('rel_'.$i.'.begin_date <=', $keydate['end']);
            $this->db->group_end();
            $this->db->or_group_start();
              $this->db->where('rel_'.$i.'.begin_date <=', $keydate['begin']);
              $this->db->where('rel_'.$i.'.end_date >=', $keydate['end']);
            $this->db->group_end();
          $this->db->group_end();

          if ($relCode[$i] != '') {
            $this->db->where('rel_'.$i.'.rel_code', $relCode[$i]);
          }
          $this->db->where('rel_'.$i.'.is_delete', FALSE);

        }
      }
    } else {
      // Sub query 1
      $select = str_replace('NUM','0',$subQuery);
      if ($alias[$i] !='') {
        $this->db->select('rel_0.obj_top_id AS '. $alias.'_id');
        $this->db->select('('.$select.') AS '. $alias.'_name');
      } else {
        $this->db->select('rel_0.obj_top_id AS obj_id');
        $this->db->select('('.$select.') AS obj_name');

      }
      //  end of Sub query 1

      if (!is_array($keydate)) {
        $this->db->where('rel_0.begin_date >=', $keydate);
        $this->db->where('rel_0.end_date <=', $keydate);
        if ($relCode != '') {
          $this->db->where('rel_0.rel_code', $relCode);
        }
        $this->db->where('rel_0.is_delete', FALSE);
      } else {
        $this->db->group_start();
          $this->db->group_start();
            $this->db->where('rel_0.begin_date >=', $keydate['begin']);
            $this->db->where('rel_0.end_date <=', $keydate['end']);
          $this->db->group_end();
          $this->db->or_group_start();
            $this->db->where('rel_0.end_date >=', $keydate['begin']);
            $this->db->where('rel_0.end_date <=', $keydate['end']);
          $this->db->group_end();
          $this->db->or_group_start();
            $this->db->where('rel_0.begin_date >=', $keydate['begin']);
            $this->db->where('rel_0.begin_date <=', $keydate['end']);
          $this->db->group_end();
          $this->db->or_group_start();
            $this->db->where('rel_0.begin_date <=', $keydate['begin']);
            $this->db->where('rel_0.end_date >=', $keydate['end']);
          $this->db->group_end();
        $this->db->group_end();
        if ($relCode != '') {
          $this->db->where('rel_0.rel_code', $relCode);
        }
        $this->db->where('rel_0.is_delete', FALSE);

      }
    }
    return $this->db->get()->result();
  }

  public function GetLastBotUpRel($botObjId=0,$relCode='',$keydate='',$alias='')
  {
    if (!is_array($keydate) && $keydate == '') {
      $keydate = date('Y-m-d');
    }
    // Sub Query 1

    $this->db->select('sub.name');
    $this->db->where('sub.is_delete', FALSE);
    $this->db->where('sub.obj_id = rel_NUM.obj_top_id');
    $this->db->from($this->tblAttr .' sub');
    $this->db->order_by('end_date','desc');
    $this->db->limit(1,0);
    if (!is_array($keydate)) {
      $this->db->where('sub.begin_date >=', $keydate);
      $this->db->where('sub.end_date <=', $keydate);
    } else {
      $this->db->group_start();
        $this->db->group_start();
          $this->db->where('sub.begin_date >=', $keydate['begin']);
          $this->db->where('sub.end_date <=', $keydate['end']);
        $this->db->group_end();
        $this->db->or_group_start();
          $this->db->where('sub.end_date >=', $keydate['begin']);
          $this->db->where('sub.end_date <=', $keydate['end']);
        $this->db->group_end();
        $this->db->or_group_start();
          $this->db->where('sub.begin_date >=', $keydate['begin']);
          $this->db->where('sub.begin_date <=', $keydate['end']);
        $this->db->group_end();
        $this->db->or_group_start();
          $this->db->where('sub.begin_date <=', $keydate['begin']);
          $this->db->where('sub.end_date >=', $keydate['end']);
        $this->db->group_end();
      $this->db->group_end();
    }
    $subQuery = $this->db->get_compiled_select();
    // END OF Sub Query 1
    $this->db->from($this->BaseModel->tblRel .' AS rel_0');
    $this->db->where('rel_0.obj_bottom_id', $botObjId);
    if (is_array($relCode)) {
      $count = count($relCode);
      // sub query 1
      for ($i=0; $i < $count ; $i++) {
        $this->db->order_by('rel_'.$i.'.end_date','desc');

        $select = str_replace('NUM',$i,$subQuery);
        if (is_array($alias) && $alias[$i] !='') {
          $this->db->select('rel_'.$i.'.obj_top_id AS '. $alias[$i].'_id');
          $this->db->select('('.$select.') AS '. $alias[$i].'_name');
        } else {
          $this->db->select('rel_'.$i.'.obj_top_id AS obj_'. $i.'_id');
          $this->db->select('('.$select.') AS obj_'.$i.'_name');

        }
      }
      // end of sub query 1
      if ($count > 1) {
        for ($i=1; $i < $count ; $i++) {
          $j = $i - 1;
          $this->db->join($this->BaseModel->tblRel .' AS rel_'.$i,'rel_'.$j.'.obj_top_id = rel_'.$i.'.obj_bottom_id');
        }
      }

      if (!is_array($keydate)) {
        for ($i=0; $i < $count ; $i++) {
          $this->db->where('rel_'.$i.'.begin_date >=', $keydate);
          $this->db->where('rel_'.$i.'.end_date <=', $keydate);
          if ($relCode[$i] != '') {
            $this->db->where('rel_'.$i.'.rel_code', $relCode[$i]);
          }
          $this->db->where('rel_'.$i.'.is_delete', FALSE);

        }

      } else {
        for ($i=0; $i < $count ; $i++) {
          $this->db->group_start();
            $this->db->group_start();
              $this->db->where('rel_'.$i.'.begin_date >=', $keydate['begin']);
              $this->db->where('rel_'.$i.'.end_date <=', $keydate['end']);
            $this->db->group_end();
            $this->db->or_group_start();
              $this->db->where('rel_'.$i.'.end_date >=', $keydate['begin']);
              $this->db->where('rel_'.$i.'.end_date <=', $keydate['end']);
            $this->db->group_end();
            $this->db->or_group_start();
              $this->db->where('rel_'.$i.'.begin_date >=', $keydate['begin']);
              $this->db->where('rel_'.$i.'.begin_date <=', $keydate['end']);
            $this->db->group_end();
            $this->db->or_group_start();
              $this->db->where('rel_'.$i.'.begin_date <=', $keydate['begin']);
              $this->db->where('rel_'.$i.'.end_date >=', $keydate['end']);
            $this->db->group_end();
          $this->db->group_end();

          if ($relCode[$i] != '') {
            $this->db->where('rel_'.$i.'.rel_code', $relCode[$i]);
          }
          $this->db->where('rel_'.$i.'.is_delete', FALSE);

        }
      }
    } else {
      // Sub query 1
      $select = str_replace('NUM','0',$subQuery);
      if ($alias[$i] !='') {
        $this->db->select('rel_0.obj_top_id AS '. $alias.'_id');
        $this->db->select('('.$select.') AS '. $alias.'_name');
      } else {
        $this->db->select('rel_0.obj_top_id AS obj_id');
        $this->db->select('('.$select.') AS obj_name');

      }
      //  end of Sub query 1

      if (!is_array($keydate)) {
        $this->db->where('rel_0.begin_date >=', $keydate);
        $this->db->where('rel_0.end_date <=', $keydate);
        if ($relCode != '') {
          $this->db->where('rel_0.rel_code', $relCode);
        }
        $this->db->where('rel_0.is_delete', FALSE);
      } else {
        $this->db->group_start();
          $this->db->group_start();
            $this->db->where('rel_0.begin_date >=', $keydate['begin']);
            $this->db->where('rel_0.end_date <=', $keydate['end']);
          $this->db->group_end();
          $this->db->or_group_start();
            $this->db->where('rel_0.end_date >=', $keydate['begin']);
            $this->db->where('rel_0.end_date <=', $keydate['end']);
          $this->db->group_end();
          $this->db->or_group_start();
            $this->db->where('rel_0.begin_date >=', $keydate['begin']);
            $this->db->where('rel_0.begin_date <=', $keydate['end']);
          $this->db->group_end();
          $this->db->or_group_start();
            $this->db->where('rel_0.begin_date <=', $keydate['begin']);
            $this->db->where('rel_0.end_date >=', $keydate['end']);
          $this->db->group_end();
        $this->db->group_end();
        if ($relCode != '') {
          $this->db->where('rel_0.rel_code', $relCode);
        }
        $this->db->where('rel_0.is_delete', FALSE);

      }
      $this->db->order_by('rel_0.end_date','desc');
    }
    $this->db->limit(1,0);
    return $this->db->get()->row();
  }

  public function InsertOn($tbl='',$data=array())
  {
    $data['create_time'] = date('Y-m-d H:i:s');
    $data['timestamp']   = date('Y-m-d H:i:s');
    $this->db->insert($tbl, $data);

    return $this->db->insert_id();
  }

  public function ChangeOn($tbl='',$id=0,$data=array())
  {
    $data['timestamp']   = date('Y-m-d H:i:s');
    if (is_array($id)) {
      $this->db->where_in('id', $id);
    } else {
      $this->db->where('id', $id);
    }
    $this->db->update($tbl, $data);
  }

  public function DelimitOn($tbl='',$id=0,$endDate='')
  {
    $data  = array(
      'end_date'  => $endDate,
      'timestamp' => date('Y-m-d H:i:s')
    );
    if (is_array($id)) {
      $this->db->where_in('id', $id);
    } else {
      $this->db->where('id', $id);
    }
    $this->db->update($tbl, $data);
  }

  public function DeleteOn($tbl='',$id=0)
  {
    $data  = array(
      'is_delete' => 1,
      'timestamp' => date('Y-m-d H:i:s')
    );
    if (is_array($id)) {
      $this->db->where_in('id', $id);
    } else {
      $this->db->where('id', $id);
    }
    $this->db->update($tbl, $data);
  }

}
