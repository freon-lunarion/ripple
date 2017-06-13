<?php $this->load->view('_template/top');?>
<?php echo anchor('','Main Menu', 'class="btn btn-default"');?>
<h1 class="page-header">Organization <small></small></h1>
<?php echo anchor($addLink,'Add' ,'class="btn btn-default"');?>

<hr />
<?php $this->load->view('_element/rangedate_filter');?>
<div id="tbl_obj" class="refresh_target" data-ajax="{ajaxUrl}"></div>
<?php $this->load->view('_template/bottom');?>
 <script src="<?php echo base_url()?>assets/js/filterDate.js"></script>
<script>
  $('body').on('click', '.nav-link', function(event) {
    event.preventDefault();
    var id = $(this).data('id');
    ReloadList(id);
  });

  function ReloadList(id) {
    // var ajaxUrl = siteUrl + '/' + elm.data('ajax') ;
    var ajaxUrl = siteUrl + '/Org/AJaxStruc' ;
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
