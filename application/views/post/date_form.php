<?php $this->load->view('_template/top');?>
<h1 class="page-header">Position <small>Change Date</small></h1>
<?php echo form_open($process, 'class="form"',$hidden); ?>

<?php $this->load->view('element/date_form'); ?>

<?php $this->load->view('element/form_act'); ?>

</form>
<?php $this->load->view('_template/bottom');?>
