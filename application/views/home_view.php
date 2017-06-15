<?php $this->load->view('_template/top.php'); ?>
<ul class="nav nav-pills nav-stacked">
  <li><?php echo anchor('Job','Job')?></li>
  <li><?php echo anchor('Org','Organization')?></li>
  <li><?php echo anchor('Post','Position')?></li>
  <li><?php echo anchor('Pers','Person')?></li>
  <hr />
  <li><?php echo anchor('Exp/Search','Search')?></li>
  <hr />
  <li><?php echo anchor('Setup/Database/demo','Reset DB','id="reset_db"')?></li>

</ul>
<?php $this->load->view('_template/bottom.php'); ?>
<script>
  $("#reset_db").click(function(event) {
    /* Act on the event */
    event.preventDefault();

    var url = $(this).attr('href');
    /* Act on the event */
    swal({
      title: "Are you sure?",
      text: 'Write "YES" to reset Database',
      type: "input",
      showCancelButton: true,
      closeOnConfirm: false,
      animation: "pop",
      inputPlaceholder: "YES"
    },
    function(inputValue){
      if (inputValue === false) return false;

      if (inputValue === "") {
        return false
      } else if (inputValue.toLowerCase() == 'yes') {
        window.location.replace(url);
      }
    });
  });
</script>
