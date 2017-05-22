<?php $this->load->view('_template/top');?>
<h1 class="page-header">Job <small>Change Date</small></h1>
<?php echo form_open($process, 'class="form"'); ?>

  <div class="form-group">
    <label for="dt_end">End Date</label>
    <input type="date" class="form-control" name="dt_end" id="dt_end" value="<?php echo $end?>" >
  </div>

  <?php echo anchor($cancelLink,'Cancel','class="btn btn-default"');?>  <button class="btn btn-primary">Save</button>
</form>
<?php $this->load->view('_template/bottom');?>
