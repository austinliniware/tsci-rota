<?php
if($_POST){
	$admin=new admin();
	if($_POST['login_pass']) $_POST['login_pass']=md5($_POST['login_pass']);
		else unset($_POST['login_pass']);
	if($_POST['id']){
		$admin->editData($_POST,$_POST['id']);
		$altmsg='修改用戶成功';
	}else{
		$admin->addData($_POST);
		go(urlkill('altmsg').'&altmsg='.urlencode('增加用戶成功'));
	}
}
!$userid && $userid=$_GET['id'];
if($userid){
	$admin=new admin();
	$uinfo=$admin->getInfo($userid);
}
?>
<form method="post" onsubmit="return checkForm(this);">
	<?if($userid){?><input type="hidden" name="id" value="<?=$userid?>"><?}?>
 <h1 class="title"><span>修改用戶資料</span></h1>
 <div class="pidding_5">
  <table cellspacing="0" cellpadding="0" class="Admin_L">
    <tr>
      <th class="T_title" scope="col" width="100">用戶資料</th>
      <th class="T_title" scope="col">&nbsp;</th>
    </tr>
    <tr>
      <td class="N_title">帳號：</td><td class="N_title">
        <input name="login_name" value="<?=$uinfo['login_name']?>" class="N_input">
      </td>
    </tr>
    <tr class="Ls2">
      <td class="N_title">密碼：</td><td class="N_title">
        <input type="password" name="login_pass" value="" class="N_input">
      </td>
    </tr>
    <tr>
      <td class="N_title">確認密碼：</td><td class="N_title">
        <input type="password" name="password" value="" class="N_input">
      </td>
    </tr>
    <tr class="Ls2">
      <td class="N_title">群組：</td><td class="N_title">
				<select name="gpid">
				<?
				$group=new group();
				$group->setLimit(0,1000);
				$group=$group->getArray();
				foreach($group as $g){
					$selected=($g['id']==$uinfo['gpid'])?'selected':'';
					echo '<option '.$selected.' value="'.$g['id'].'">'.$g['name'].'</option>';
				}
				?>
				</select>
      </td>
    </tr>
    <tr>
      <td class="N_title">真實姓名：</td><td class="N_title">
        <input name="real_name" value="<?=$uinfo['real_name']?>" class="N_input">
    </tr>
    <tr class="Ls2">
      <td class="N_title"></td>
      <td class="N_title"><input class="sub2" type="submit" value="送出"></td>
    </tr>
  </table>
  </div>
</form>
<script>
function checkForm(form){
	var msg='';
	if(form.login_name.value=='') msg+='請輸入該用戶的登錄名\r\n';
	if(form.real_name.value=='') msg+='請輸入該用戶的真實姓名\r\n';
	<?if(!$userid){?>if(form.login_pass.value=='') msg+='請輸入該用戶的密碼\r\n';<?}?>
	if(form.login_pass.value!=form.password.value) msg+='兩次輸入的密碼需要一致\r\n';
	if(msg){
		alert(msg);
		return false;
	}else return true;
}
</script>