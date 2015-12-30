<?php
if($_POST){
	$group=new group();
	if($_POST['id']){
		$group->editData($_POST,$_POST['id']);
		go(urlkill('altmsg').'&altmsg='.urlencode('群組修改成功'));
	}else{
		unset($_POST['id']);
		$group->addData($_POST);
		go(urlkill('altmsg').'&altmsg='.urlencode('添加群組成功'));
	}
}
$group=new group();
$groupary=$group->getArray();
$pageCtrl=$group->getPageInfoHTML();
$pageInfo=$group->getPageInfoHTML(1);
?>
 <h1 class="title"><span>群組管理</span></h1>
 <div class="pidding_5">
  <div class="search">
  <form id="editForm" method="post" onsubmit="return checkForm(this)">
	<span>
		<input type="hidden" name="id">
		<input size="20" name="name">
		<input id="submitBtn" type="submit" value="添加">
		<input id="resetBtn" style="display: none;" type="reset" onclick="$(this).hide();$('#submitBtn').val('添加');" value="取消">
	</span>
  </form>
  </div>
	<script>
	function checkForm(form){
		var msg='';
		if(form.name.value=='') msg='請輸入群組名稱';
		if(msg){
			alert(msg);
			return false;
		}else return true;
	}
	</script>
  <table cellspacing="0" cellpadding="0" class="Admin_L">
    <tr>
      <th scope="col" class="T_title">群組名</th>
      <th scope="col" width="70" style="text-align: center;">操作</th>
    </tr>
    <?foreach($groupary as $gp){?>
    <tr class="Ls2">
      <td class="N_title"><?=$gp['name']?></td>
      <td class="E_bd"><a href="javascript:;" onclick='editAccFun(<?=jsonEncode($gp)?>)'>編輯</a><?if($gp['id']>100){?> | <a href="javascript:;" onclick="delFun('group','<?=$gp['id']?>')">刪除</a><?}?></td>
    </tr>
    <?}?>
  </table>
  <div class="news-viewpage"><?=$pageCtrl?> <?=$pageInfo?></div>
  </div>
	<script>
	function editAccFun(obj){
		$('#resetBtn').show();
		$('#submitBtn').val('修改');
		editFun(obj);
	}
	</script>
	