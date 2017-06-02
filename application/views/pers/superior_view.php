<?php $this->load->view('_template/top');?>
<?php echo anchor($backLink,'Back','class="btn btn-default"');?>

<h1 class="page-header">Person <small>Superior</small></h1>

<dl class="">
  <dt>Person</dt>
  <dd>{persId} - {persName}</dd>
  <dt>Position</dt>
  <dd>{postId} - {postName}</dd>
</dl>

<h2>Supervisor</h2>
<dl class="">
  <dt>Begin - End</dt>
  <dd>{begin} - {end}</dd>
  <dt>Position</dt>
  <dd>{sprPostId} - {sprPostName}</dd>
  <dt>Person</dt>
  <dd>{sprPersId} - {sprPersName}</dd>
</dl>

<?php $this->load->view('_template/bottom');?>
