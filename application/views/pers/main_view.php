<?php $this->load->view('_template/top');?>
<?php echo anchor('','Main Menu', 'class="btn btn-default"');?>
<h1 class="page-header">Person <small></small></h1>
<?php echo anchor($addLink,'Add' ,'class="btn btn-default"');?>
<hr />
<form action="" class="form-inline" method="post">
  <div class="form-group">
    <label for="dt_begin">Begin </label>
    <input type="date" class="form-control" name="dt_begin" id="dt_begin" value="{begin}" >
  </div>
  <div class="form-group">
    <label for="dt_end">End </label>
    <input type="date" class="form-control" name="dt_end" id="dt_end" value="{end}" >
  </div>
  <button type="submit" value="search" class="btn btn-default">View</button>
</form>
<table class="table">
  <thead>
    <tr>
      <th>ID</th>
      <th>Name</th>
      <th>Begin</th>
      <th>End</th>
      <th>View</th>
    </tr>
  </thead>
  <tbody>
    {rows}
      <tr>
        <td>{id}</td>
        <td>{name}</td>
        <td>{begda}</td>
        <td>{endda}</td>
        <td>{viewlink}</td>
        <td></td>
        <td></td>
      </tr>
    {/rows}
  </tbody>
</table>
<?php $this->load->view('_template/bottom');?>
