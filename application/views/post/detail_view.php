<?php $this->load->view('_template/top');?>
<?php echo anchor($backLink,'Back','class="btn btn-default"');?>
<h1 class="page-header">Position <small>View</small></h1>

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

<h2>Job Type</h2>
<dl class="">
  <dt>Id</dt>
  <dd>{jobId} <?php echo anchor($editJob,'Change','class="btn btn-link" title="Change Job"');?></dd>
  <dt>Name</dt>
  <dd>{jobName} </dd>
</dl>
<h2>History of Job Type</h2>
<table class="table table-striped table-hover">
  <thead>
    <tr>
      <th>Begin</th>
      <th>End</th>
      <th>Id</th>
      <th>Name</th>
    </tr>
  </thead>
  <tbody>
    {job}
      <tr class="{historyRow}">
        <td>{jobBegin}</td>
        <td>{jobEnd}</td>

        <td>{jobId}</td>
        <td>{jobName}</td>
      </tr>
    {/job}
  </tbody>
</table>

<h2>Holder</h2>
<dl class="">
  <dt>Id</dt>
  <dd>{holderId} <?php echo anchor($editHolder,'Change','class="btn btn-link" title="Change Holder"');?></dd>
  <dt>Name</dt>
  <dd>{holderName} </dd>
</dl>
<h2>History of Holder</h2>
<table class="table table-striped table-hover">
  <thead>
    <tr>
      <th>Begin</th>
      <th>End</th>
      <th>Id</th>
      <th>Name</th>
    </tr>
  </thead>
  <tbody>
    {holder}
      <tr class="{historyRow}">
        <td>{holderBegin}</td>
        <td>{holderEnd}</td>

        <td>{holderId}</td>
        <td>{holderName}</td>


      </tr>
    {/holder}
  </tbody>
</table>

<h2>Superior</h2>
<dl class="">
  <dt>Position ID</dt>
  <dd>{sprPostId} <?php echo anchor($editSpr,'Change','class="btn btn-link" title="Change Superior"');?></dd>
  <dt>Position Name</dt>
  <dd>{sprPostName}</dd>

</dl>

<h2>History of Superior</h2>
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
    {spr}
    <tr>
      <th>{sprBegin}</th>
      <th>{sprEnd}</th>
      <th>{sprId}</th>
      <th>{sprName}</th>

    </tr>
    {/spr}
  </tbody>
</table>

<h2>Subordinate</h2>
<table class="table table-striped table-hover">
  <thead>
    <tr>
      <th>Begin</th>
      <th>End</th>
      <th>Change</th>

      <th>Post Id</th>
      <th>Post Name</th>
      <th>Delimit</th>

    </tr>
  </thead>
  <tbody>
    {sub}
      <tr class="{historyRow}">
        <td>{subBegin}</td>
        <td>{subEnd}</td>
        <td><a href="{chgRel}" class="btn btn-link" title="Change Date">Chg. Date</a>

        <td>{subPostId}</td>
        <td>{subPostName}</td>
        <td><a href="{remRel}" class="btn btn-link" title="Change Date">Delete</a>

      </tr>
    {/sub}
  </tbody>
</table>

<h2>Peer</h2>
<table class="table table-striped table-hover">
  <thead>
    <tr>
      <th>Begin</th>
      <th>End</th>
      <th>Post Id</th>
      <th>Post Name</th>

    </tr>
  </thead>
  <tbody>
    {peer}
      <tr class="">
        <td>{peerBegin}</td>
        <td>{peerEnd}</td>
        <td>{peerPostId}</td>
        <td>{peerPostName}</td>
      </tr>
    {/peer}
  </tbody>
</table>

<h2>Assignment</h2>
<dl class="">
  <dt>Id</dt>
  <dd>{assId} <?php echo anchor($editAss,'Change','class="btn btn-link" title="Change Assigment"');?></dd>
  <dt>Name</dt>
  <dd>{assName} </dd>
</dl>
<h2>History of Assignment</h2>
<table class="table table-striped table-hover">
  <thead>
    <tr>
      <th>Begin</th>
      <th>End</th>
      <th>Id</th>
      <th>Name</th>
    </tr>
  </thead>
  <tbody>
    {ass}
      <tr class="{assRow}">
        <td>{assBegin}</td>
        <td>{assEnd}</td>
        <td>{assId}</td>
        <td>{assName}</td>
      </tr>
    {/ass}
  </tbody>
</table>

<h2>Managing</h2>
<dl class="">
  <dt>Id</dt>
  <dd>{manId} <?php echo anchor($editMan,'Change','class="btn btn-link" title="Change Managing"');?></dd>
  <dt>Name</dt>
  <dd>{manName} </dd>
</dl>
<h2>History of Managing</h2>
<table class="table table-striped table-hover">
  <thead>
    <tr>
      <th>Begin</th>
      <th>End</th>
      <th>Id</th>
      <th>Name</th>
    </tr>
  </thead>
  <tbody>
    {man}
      <tr class="manRow">
        <td>{manBegin}</td>
        <td>{manEnd}</td>
        <td>{manId}</td>
        <td>{manName}</td>
      </tr>
    {/man}
  </tbody>
</table>


<?php echo anchor($backLink,'Back','class="btn btn-default"');?> <?php echo anchor($delLink,'Delete','class="btn btn-danger"');?>
<?php $this->load->view('_template/bottom');?>
