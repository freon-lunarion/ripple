<?php $this->load->view('_template/top');?>
<h1 class="page-header">Position <small>Change Holder</small></h1>
<?php echo form_open($process, 'class="form"'); ?>

  <div class="form-group">
    <label for="dt_begin">Since </label>
    <input type="date" class="form-control" name="dt_begin" id="dt_begin" value="<?php echo date('Y-m-d')?>" >
  </div>

  <div class="form-group">
    <label for="txt_name">Employee</label>
    <?php echo form_dropdown('slc_emp',$empOpt, $empSlc,'id="slc_emp" class="form-control"'); ?>

  </div>
  <?php $this->load->view('general/form_act_elm'); ?>

</form>
<?php $this->load->view('_template/bottom');?>
