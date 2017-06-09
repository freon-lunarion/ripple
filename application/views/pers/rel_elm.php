<h2>Related Position</h2>
<div class="row">
  <div class="col-xs-12">
    <?php echo anchor($addPost,'Add','class="btn btn-default"');?>
  </div>
</div>
<table class="table table-striped table-hover">
  <thead>
    <tr>
      <th>Begin</th>
      <th>End</th>
      <th>Change</th>
      <th>Id</th>
      <th>Name</th>
      <th>Superior</th>
      <th class="text-danger">Delete</th>
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
        <td><a href="{sprLink}" class="btn btn-link">View</a></td>
        <td><a href="{remRel}" class="btn btn-link btn-delete" title="Delete">Delete</a>
          </td>
      </tr>
    {/post}
  </tbody>
</table>
