<?php $this->load->view('_template/top');?>
<?php echo anchor('','Main Menu', 'class="btn btn-default"');?>
<h1 class="page-header">Organization <small></small></h1>
<?php echo anchor($addLink,'Add' ,'class="btn btn-default"');?>
<!-- Single button -->
<!-- <div id="btn_view_mode" class="btn-group">
  <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    View Mode <span class="caret"></span>
  </button>
  <ul class="dropdown-menu">
    <li><a href="#" data-type="struct">Structure</a></li>
    <li><a href="#" data-type="all">All</a></li>

  </ul>
</div> -->
<hr />
<?php $this->load->view('_element/rangedate_filter');?>
<div id="tbl_obj" class="refresh_target" data-ajax="{ajaxUrl}"></div>
<?php $this->load->view('_template/bottom');?>
<script src="<?php echo base_url()?>assets/js/filterDate.js"></script>
