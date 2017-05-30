<?php $this->load->view('_template/top');?>
<?php echo anchor($backLink,'Back','class="btn btn-default"');?>
<h1 class="page-header">Organization <small>View</small></h1>

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
  <dd>{objBegin} - {objEnd}<?php echo anchor($editDate,'Change','class="btn btn-link" title="Change Date"');?></dd>
  <dt>Name</dt>
  <dd>{objName}<?php echo anchor($editName,'Change','class="btn btn-link" title="Change Name"'); ?></dd>
</dl>

<h2>History of Name</h2>
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

<h2>Parent Organization</h2>
<dl class="">
  <dt>ID</dt>
  <dd>{parentId} <?php echo anchor($editParent,'Change','class="btn btn-link" title="Change Parent"'); ?></dd>
  <dt>Name</dt>
  <dd>{parentName}</dd>
</dl>

<h2>History of Parent</h2>
<table class="table table-striped table-hover">
  <thead>
    <tr>
      <th>Begin</th>
      <th>End</th>
      <th>Name</th>
    </tr>
  </thead>
  <tbody>
    {parent}
      <tr class="{historyRow}">
        <td>{parentBegin}</td>
        <td>{parentEnd}</td>
        <td>{parentId}</td>
        <td>{parentName}</td>
      </tr>
    {/parent}
  </tbody>
</table>

<h2>Chief</h2>
<dl class="">
  <dt>Position ID</dt>
  <dd>{chiefPostId} <?php echo anchor($editChief,'Change','class="btn btn-link" title="Change Chief"'); ?></dd>
  <dt>Position Name</dt>
  <dd>{chiefPostName}</dd>
</dl>

<h2>History of Chief</h2>
<table class="table table-striped table-hover">
  <thead>
    <tr>
      <th>Begin</th>
      <th>End</th>
      <th>Position Id</th>
      <th>Position Name</th>
    </tr>
  </thead>
  <tbody>
    {chief}
      <tr class="{historyRow}">
        <td>{chiefBegin}</td>
        <td>{chiefEnd}</td>
        <td>{chiefId}</td>
        <td>{chiefName}</td>
      </tr>
    {/chief}
  </tbody>
</table>

<h2>Children Organization</h2>
<table class="table table-striped table-hover">
  <thead>
    <tr>
      <th>Begin</th>
      <th>End</th>
      <th>Change</th>

      <th>Id</th>
      <th>Name</th>
      <th>Delimit</th>
    </tr>
  </thead>
  <tbody>
    {children}
      <tr class="{historyRow}">
        <td>{childrenBegin}</td>
        <td>{childrenEnd}</td>
        <td><a href="{chgRel}" class="btn btn-link" title="Change Date">Chg. Date</a>

        <td>{childrenId}</td>
        <td>{childrenName}</td>
        <td><a href="{remRel}" class="btn btn-link" title="Change Date">Delete</a></td>

      </tr>
    {/children}
  </tbody>
</table>


<h2>Position List</h2>
<table class="table table-striped table-hover">
  <thead>
    <tr>
      <th>Begin</th>
      <th>End</th>
      <th>Change</th>

      <th>Id</th>
      <th>Name</th>
      <th>Delimit</th>
    </tr>
  </thead>
  <tbody>
    {post}
      <tr class="{historyRow}">
        <td>{postBegin}</td>
        <td>{postEnd}</td>
        <td><a href="{chgRel}" class="btn btn-link" title="Change Date">Chg. Date</a>

        <td>{postId}</td>
        <td>{postName}</td>
        <td><a href="{remRel}" class="btn btn-link" title="Change Date">Delete</a></td>

      </tr>
    {/post}
  </tbody>
</table>


<?php echo anchor($backLink,'Back','class="btn btn-default"');?> <?php echo anchor($delLink,'Delete','class="btn btn-danger"');?>
<?php $this->load->view('_template/bottom');?>
