<?php $this->load->view('_template/top');?>
<h1 class="page-header">Position <small>Add</small></h1>
<?php echo form_open($process, 'class="form"'); ?>

  <?php $this->load->view('general/add_form_elm');?>
  <div class="form-group">
    <label for="txt_name">Parent Organization</label>
    <?php echo form_dropdown('slc_parent',$parentOpt, '','id="slc_parent" class="form-control"'); ?>
  </div>
  <div class="checkbox">
    <label>
      <input type="checkbox" name="chk_chief" id="chk_chief"> Chief
    </label>
  </div>
  <div class="form-group">
    <label for="txt_name">Superior</label>
    <?php echo form_dropdown('slc_super',$superOpt, '','id="slc_super" class="form-control"'); ?>
  </div>
  <div class="form-group">
    <label for="txt_name">Job Type</label>
    <?php echo form_dropdown('slc_job',$jobOpt, '','id="slc_job" class="form-control"'); ?>
  </div>
  <div class="form-group">
    <label for="txt_name">Employee</label>
    <?php echo form_dropdown('slc_emp',$empOpt, '','id="slc_emp" class="form-control"'); ?>
  </div>
  <?php $this->load->view('general/form_act_elm'); ?>

</form>
<?php $this->load->view('_template/bottom');?>
