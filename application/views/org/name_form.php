<?php $this->load->view('_template/top');?>
<h1 class="page-header">Organization <small>Change Name</small></h1>
<?php echo form_open($process, 'class="form"'); ?>

<?php $this->load->view('element/name_form'); ?>

<?php $this->load->view('element/form_act'); ?>

</form>
<?php $this->load->view('_template/bottom');?>
