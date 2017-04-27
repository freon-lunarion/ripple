<?php $this->load->view('_template/top');?>
<h1 class="page-header">Job <small>Add</small></h1>
<?php echo form_open('Job/EditNameProcess', 'class="form"'); ?>

  <div class="form-group">
    <label for="dt_begin">Start Valid on</label>
    <input type="date" class="form-control" name="dt_begin" id="dt_begin" value="<?php echo date('Y-m-d')?>" >
  </div>
  <div class="form-group">
    <label for="txt_name">Name</label>
    <input type="text" class="form-control" id="txt_name" name="txt_name" value="<?php echo $name; ?>"placeholder="">
    <p class="help-block">5-150 Characters.</p>
  </div>
  <?php echo anchor('Job','Cancel','class="btn btn-default"');?>  <button class="btn btn-primary">Save</button>
</form>
<?php $this->load->view('_template/bottom');?>
