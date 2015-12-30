<?php
$className=$_GET['cn'];
include('header_title.php');
$class=new $className;
if($_POST){
	if($_POST['id']){
		$_POST['id']=(int)$_GET["id"];
		$class->edit($_POST,$_POST['id']);
		$altmsg='修改'.$classStr.'成功';
	}else{
		$class->add($_POST);
		// go(urlkill('altmsg').'&altmsg='.urlencode('新增'.$classStr.'成功'));
		$altmsg='新增'.$classStr.'成功'; 
	}
}
if($_GET['id']){
	$info=$class->getInfo($_GET['id']);
}else{
	if(!permission::check($class->tableName,"a_tag")){
		echo "<script>alert('對不起你沒有該操作的權限');</script>";
		exit;
	}
}

?>
<form method="post" onsubmit="return checkForm(this);" enctype="multipart/form-data">
	<?if($_GET['id']){?><input type="hidden" name="id" value="<?=$_GET['id']?>"><?}?>
 <h1 class="title"><span><?php echo $_GET["id"]?"修改":"新增";?> <?=$classStr?>資料</span></h1>
 <div class="pidding_5">
  <table cellspacing="0" cellpadding="0" class="Admin_L">
    <tr>
      <th class="T_title" scope="col" width="150"><?=$classStr?>資料</th>
      <th class="T_title" scope="col" colspan="7">&nbsp;</th>
    </tr>
	<?include($_GET['type'].'/'.$className.'.form.php')?>
    <tr class="Ls2">
      <td class="N_title">&nbsp;</td>
      <td class="N_title" colspan="7"><input class="sub2" type="submit" value="送出表單"></td>
    </tr>
  </table>
  </div>
</form>
<?if($info){?>
<script>
editFun(<?=jsonEncode($info)?>);
</script>
<?}?>
