<!-- Modal -->
<div class="modal fade" id="explor_post" tabindex="-1" role="dialog" aria-labelledby="post_structlLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Position Explorer</h4>
      </div>
      <div class="modal-body" id="post_exp">

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<script>
  $('#explor_post').on('show.bs.modal', function (e) {
    ReloadList(1);
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
    $('#txt_post').val(text);
    $('#hdn_post').val(id);
  });

  function ReloadList(id) {
    var ajaxUrl = siteUrl + '/Post/AJaxStruc/explor' ;
    $.ajax({
      url: ajaxUrl,
      type: 'POST',
      dataType: 'html',
      data: {id: id}
    })
    .done(function(respond) {
      $('#post_exp').html(respond);
    })
    .fail(function() {
      console.log("error");
    })
    .always(function() {
      console.log("complete");
    });

  }
</script>
