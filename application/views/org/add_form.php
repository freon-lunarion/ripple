<?php $this->load->view('_template/top');?>
<h1 class="page-header">Organization <small>Add</small></h1>
<?php echo form_open($process, 'class="form"'); ?>

  <?php $this->load->view('_element/add_form');?>
  <div class="form-group">
    <label for="">Parent</label>
    <?php $this->load->view('org/explorer_input');?>

  </div>
  <?php $this->load->view('_element/form_act'); ?>

</form>
<?php $this->load->view('_template/bottom');?>
<?php $this->load->view('org/explorer_modal');?>
