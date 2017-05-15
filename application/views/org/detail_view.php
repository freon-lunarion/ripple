<?php $this->load->view('_template/top');?>
<?php echo anchor('Job','Back','class="btn btn-default"');?>
<h1 class="page-header">Job <small>View</small></h1>

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

<h2>Detail</h2>
<dl class="">
  <dt>Begin - End</dt>
  <dd>{objBegin} - {objEnd}<?php echo anchor('Job/EditDate/','<i class="glyphicon glyphicon-pencil"></i>','class="btn btn-link" title="Change Date"');?></dd>
  <dt>Name</dt>
  <dd>{objName}<?php echo anchor('Job/EditName/','<i class="glyphicon glyphicon-pencil"></i>','class="btn btn-link" title="Change Name"'); ?></dd>
</dl>

<h2>History</h2>
<table class="table table-striped table-hover">
  <thead>
    <tr>
      <th>Begin</th>
      <th>End</th>
      <th>Name</th>
    </tr>
  </thead>
  <tbody>
    {history}
      <tr class="{historyRow}">
        <td>{historyBegin}</td>
        <td>{historyEnd}</td>
        <td>{historyName}</td>
      </tr>
    {/history}
  </tbody>
</table>
<?php echo anchor('Job','Back','class="btn btn-default"');?> <?php echo anchor('Job/DeleteProcess','Delete','class="btn btn-danger"');?>
<?php $this->load->view('_template/bottom');?>
