<?php $this->load->view('_template/top');?>
<h1 class="page-header">Job <small>Add</small></h1>
<?php echo form_open($process, 'class="form"'); ?>

  <?php $this->load->view('_element/add_form');?>

  <?php $this->load->view('_element/form_act'); ?>
  
</form>
<?php $this->load->view('_template/bottom');?>
