<?php $this->load->view('_template/top.php'); ?>
<ul class="nav nav-pills nav-stacked">
  <li><?php echo anchor('Job','Job')?></li>
  <li><?php echo anchor('Org','Organization')?></li>
  <li><?php echo anchor('Post','Position')?></li>
  <li><?php echo anchor('Pers','Person')?></li>
  <hr />
  <!--<li><?php //echo anchor('Exp/Search','Search')?></li>-->
</ul>
<?php $this->load->view('_template/bottom.php'); ?>
