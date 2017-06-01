<?php $this->load->view('_template/top');?>
<h1 class="page-header">Organization <small>Add</small></h1>
<?php echo form_open($process, 'class="form"'); ?>

  <?php $this->load->view('element/add_form');?>
  <div class="form-group">
    <label for="txt_name">Parent</label>
    <?php echo form_dropdown('slc_parent',$parentOpt, '','id="slc_parent" class="form-control"'); ?>

  </div>
  <?php $this->load->view('element/form_act'); ?>

</form>
<?php $this->load->view('_template/bottom');?>
