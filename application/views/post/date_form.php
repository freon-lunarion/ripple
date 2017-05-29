<?php $this->load->view('_template/top');?>
<h1 class="page-header">Position <small>Change Date</small></h1>
<?php echo form_open($process, 'class="form"',$hidden); ?>

<?php $this->load->view('general/date_form_elm'); ?>

<?php $this->load->view('general/form_act_elm'); ?>

</form>
<?php $this->load->view('_template/bottom');?>
