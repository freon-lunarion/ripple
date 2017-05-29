<?php $this->load->view('_template/top');?>
<h1 class="page-header">Organization <small>Change Name</small></h1>
<?php echo form_open($process, 'class="form"'); ?>

<?php $this->load->view('general/name_form'); ?>

<?php $this->load->view('general/form_act_elm'); ?>

</form>
<?php $this->load->view('_template/bottom');?>
