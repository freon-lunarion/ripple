<?php $this->load->view('_template/top');?>

<?php echo anchor('','Main Menu', 'class="btn btn-default"');?>

<div class="container" style="margin-top: 8%;">
  <div class="col-md-6 col-md-offset-3">
  <div class="row">

    <form role="form" id="form-buscar">
      <div class="form-group">
        <div class="input-group">
        <input id="1" class="form-control" type="text" name="search" placeholder="Search..." required/>
        <span class="input-group-btn">
        <button class="btn btn-default" type="submit">
        <i class="glyphicon glyphicon-search" aria-hidden="true"></i> Search
        </button>
        </span>
        </div>
      </div>
    </form>
  </div>
  </div>
</div>
<?php $this->load->view('_template/bottom');?>
