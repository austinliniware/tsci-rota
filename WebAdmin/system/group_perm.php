<?php
if($_POST){
	group_perm::update($_POST['group_id'],'group_id',$_POST['perm']);
	$altmsg='權限修改成功';
}
!$_GET['group_id'] && $_GET['group_id']=1;
$group=new group();
$group->setLimit(0,1000);
$group=$group->getArray();
$permission=permission::getList();
$group_perm=new group_perm();
$tmpary=$group_perm->getGroupPerm($_GET['group_id']);
$perm=array();
foreach($tmpary as $ary){
	$perm[$ary['perm_id']]=$ary;
}
?>
<form id="editForm" method="post" onsubmit="return checkForm(this)">
 <h1 class="title"><span>權限管理</span></h1>
 <div class="pidding_5">
  <div class="search">
				<span>
					<select name="group_id" onchange="window.location.href='<?=urlkill('group_id')?>&group_id='+this.value">
						<?foreach($group as $gp){?>
						<option <?if($_GET['group_id']==$gp['id']) echo 'selected'?> value="<?=$gp['id']?>"><?=$gp['name']?></option>
						<?}?>
					</select>
					<input class="sub2" id="submitBtn" type="submit" value="修改">
				</span>
  </div>
	<?include('permission.php')?>
  </div>
</form>
