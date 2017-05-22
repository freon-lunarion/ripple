<?php $this->load->view('_template/top');?>
<h1 class="page-header">Organization <small>Add</small></h1>
<?php echo form_open('Org/AddProcess', 'class="form"'); ?>

  <div class="form-group">
    <label for="dt_begin">Begin </label>
    <input type="date" class="form-control" name="dt_begin" id="dt_begin" value="<?php echo date('Y-m-d')?>" >
  </div>
  <div class="form-group">
    <label for="dt_end">End </label>
    <input type="date" class="form-control" name="dt_end" id="dt_end" value="9999-12-31" >
  </div>
  <div class="form-group">
    <label for="txt_name">Parent</label>
    <?php echo form_dropdown('slc_parent',$parentOpt, '','id="slc_parent" class="form-control"'); ?>

  </div>
  <div class="form-group">
    <label for="txt_name">Name</label>
    <input type="text" class="form-control" id="txt_name" name="txt_name" placeholder="">
    <p class="help-block">5-150 Characters.</p>
  </div>
  <?php echo anchor($cancelLink,'Cancel','class="btn btn-default"');?>  <button class="btn btn-primary">Save</button>
</form>
<?php $this->load->view('_template/bottom');?>
