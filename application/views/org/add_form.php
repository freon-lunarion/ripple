<?php $this->load->view('_template/top');?>
<h1 class="page-header">Organization <small>Add</small></h1>
<?php echo form_open($process, 'class="form"'); ?>

  <?php $this->load->view('_element/add_form');?>
  <div class="form-group">
    <label for="">Parent</label>
    <div class="input-group">
      <input type="hidden" class="form-control" id="hdn_parent" name="hdn_parent" placeholder="">

      <input type="text" class="form-control readonly" id="txt_parent" name="txt_parent" placeholder="" readonly="readonly">
      <span class="input-group-btn">
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#explor_org">Org ...</button>

      </span>

    </div>

  </div>

  <!-- <div class="form-group">
    <label for="txt_name">Parent</label>
    <?php echo form_dropdown('slc_parent',$parentOpt, '','id="slc_parent" class="form-control"'); ?>
  </div> -->
  <?php $this->load->view('_element/form_act'); ?>

</form>
<?php $this->load->view('_template/bottom');?>
<!-- Modal -->
<div class="modal fade" id="explor_org" tabindex="-1" role="dialog" aria-labelledby="org_structlLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Organization Structure</h4>
      </div>
      <div class="modal-body" id="tbl_obj">

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<script>
  $('#explor_org').on('show.bs.modal', function (e) {
    ReloadList(0);
  })

  $('body').on('click', '.nav-open', function(event) {
    event.preventDefault();
    var id = $(this).data('id');
    ReloadList(id);
  });

  $('body').on('click', '.nav-select', function(event) {
    event.preventDefault();
    var id = $(this).data('id');
    var text = $(this).data('text');
    $('#txt_parent').val(text);
    $('#hdn_parent').val(id);
    // $('#explor_org').modal('hide');
  });

  function ReloadList(id) {
    var ajaxUrl = siteUrl + '/Org/AJaxStruc/explor' ;
    $.ajax({
      url: ajaxUrl,
      type: 'POST',
      dataType: 'html',
      data: {id: id}
    })
    .done(function(respond) {
      // console.log("success");
      $('#tbl_obj').html(respond);
    })
    .fail(function() {
      console.log("error");
    })
    .always(function() {
      console.log("complete");
    });

  }
</script>
