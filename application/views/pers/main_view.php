<?php $this->load->view('_template/top');?>
<?php echo anchor('','Main Menu', 'class="btn btn-default"');?>
<h1 class="page-header">Person <small></small></h1>
<?php echo anchor($addLink,'Add' ,'class="btn btn-default"');?>
<hr />
<?php $this->load->view('_element/rangedate_filter');?>
<?php $this->load->view('_element/obj_tbl');?>
<?php $this->load->view('_template/bottom');?>
