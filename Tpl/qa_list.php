<?php
$qaclass=new question();
$qaclass->wheres = 'patient_id='.$_SESSION['patient_id'].' and actionkey='.$actionkey;
$qalist=$qaclass->getList();
if ($qalist) echo "<a name='qa'></a>";
foreach ($qalist as $rs){
?>
            <div class="doctorsSignoffBox">
			  <div><?=$rs['UserName'];?>　Date: <?=$rs['date_added'];?>
			  </div>
			  <div>
				<?=nl2br($rs['content']);?>
			  </div>
			  <div class="clearfloat"></div>
			</div>
<?}?>
