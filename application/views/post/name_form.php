<?php $this->load->view('_template/top');?>
<h1 class="page-header">Organization <small>Change Name</small></h1>
<?php echo form_open($process, 'class="form"'); ?>

  <div class="form-group">
    <label for="dt_begin">Since </label>
    <input type="date" class="form-control" name="dt_begin" id="dt_begin" value="<?php echo date('Y-m-d')?>" >
  </div>
  <div class="form-group">
    <label for="txt_name">Name</label>
    <input type="text" class="form-control" id="txt_name" name="txt_name" placeholder="">
    <p class="help-block">5-150 Characters.</p>
  </div>
  <?php echo anchor($cancelLink,'Cancel','class="btn btn-default"');?>  <button class="btn btn-primary">Save</button>
</form>
<?php $this->load->view('_template/bottom');?>
