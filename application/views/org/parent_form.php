<?php $this->load->view('_template/top');?>
<h1 class="page-header">Organization <small>Change Parent</small></h1>
<?php echo form_open($process, 'class="form"'); ?>

  <div class="form-group">
    <label for="dt_begin">Since </label>
    <input type="date" class="form-control" name="dt_begin" id="dt_begin" value="<?php echo date('Y-m-d')?>" >
  </div>

  <div class="form-group">
    <label for="txt_name">Parent</label>
    <?php echo form_dropdown('slc_parent',$parentOpt, $parentSlc,'id="slc_parent" class="form-control"'); ?>

  </div>
  <?php $this->load->view('element/form_act'); ?>

</form>
<?php $this->load->view('_template/bottom');?>
