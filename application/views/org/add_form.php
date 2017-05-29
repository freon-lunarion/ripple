<?php $this->load->view('_template/top');?>
<h1 class="page-header">Organization <small>Add</small></h1>
<?php echo form_open($process, 'class="form"'); ?>

  <?php $this->load->view('general/add_form_elm');?>
  <div class="form-group">
    <label for="txt_name">Parent</label>
    <?php echo form_dropdown('slc_parent',$parentOpt, '','id="slc_parent" class="form-control"'); ?>

  </div>
  <?php $this->load->view('general/form_act_elm'); ?>

</form>
<?php $this->load->view('_template/bottom');?>
