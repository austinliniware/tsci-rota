<?php
$noLocation=true;
define('ROOT_PATH',dirname(dirname(__FILE__)).'/');

include_once(ROOT_PATH.'WebAdmin/common.inc.php');
if(isset($_GET['out']) && $_GET['out']=='yes'){
	$_SESSION["ADMIN_ID"]="";
	@session_destroy();
	Header("Location: index.php");exit;
}
if($_POST){
if($_POST['username'] && $_POST['password']){
	
	$error="";
	
	if($_SESSION["authnum"]==$_POST["verifycode"]){
		$userClass=new user();
		$reInt=false;
		$reInt=$userClass->check($_POST['username'],$_POST['password']);
		if($reInt>0){
			Header("Location: index.php");exit;
		}else{
			switch($reInt){
				case -1	: $error='帳號錯誤';
				break;
				case -2	: $error='密碼錯誤';
				break;
				case -3	: $error='沒有登錄權限';
				break;
			}
			$_SESSION["ADMIN_ID"]="";
			@session_destroy();
		}
	}else{
		$error='驗證碼錯誤';
	}
}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>馬路科技後臺管理登入</title>
<link href="style/css/login.css" rel="stylesheet" type="text/css" media="all" />
<script type="text/javascript">
<!--
function check(){
if (document.getElementById('username').value=="" || document.getElementById('password').value=="") {
    document.getElementById('username').focus();
    return false;
}
if (document.getElementById('verifycode').value=="") {
    document.getElementById('verifycode').focus();
    return false;
}

return true;
}
//-->
</script>
</head>

<body  onload="document.getElementById('username').focus()">
<div class="box outW">
<div><img src="style/images/88_02.jpg"  /></div>
<div><img src="style/images/88_04.jpg" /></div>
<div><img src="style/images/88_05.jpg"  /></div>
<div id="mianbox">
	 <form action="" method="post" style="margin:0px;padding:0px;" onsubmit="return check();">
<div class="contentbox">
     <div class="titlebox"><span class="titleys01">帳號</span><span class="titleys02"> / ID</span></div>
     <div class="inputbox"><input name="username" id="username" type="text" /></div>
     <div class="titlebox"><span class="titleys01">密碼</span></div>
     <div class="inputbox"><input name="password" id="password" type="password" /></div>
     <div class="titlebox"><span class="titleys01">驗證碼</span></div>
     <div class="inputbox02"><input name="verifycode" id="verifycode" type="text" /><span id="reload-img"><img src="../code.php" width="72" height="19"  id="rand-img" alt="點擊更新驗證碼" title="點擊更新驗證碼" /> </span></div>
     <div class="clearfloat"></div>
     <div ><span class="inputbox03"><input name="login" type="submit" id="login" value="" /></span><span class="inputbox04"><input name="reset" type="reset" value="" /></span></div>
     </form>
    <?php 
    if(!empty($error)){
    ?>
    <div style="clear:both;"></div>
    <div style="margin:30px 10px 20px 60px;color:red;"><?php echo $error;?></div>
    <?php 
    }
    ?>
</div>
</div>
<div><img src="style/images/88_21.jpg" /></div>
<div><img src="style/images/88_22.jpg"  /></div>
<div><img src="style/images/88_23.jpg"  /></div>
<div></div>
</div>
<script type="text/javascript">
<!--
(function(){
var reloadImg = function(dImg) {
  var sOldUrl = dImg.src;
  var sNewUrl = sOldUrl + "?rnd=" + Math.random();
  dImg.src = sNewUrl;
};

var dReloadLink = document.getElementById("reload-img");
var dImg = document.getElementById("rand-img");
  
dReloadLink.onclick = function(e) {
  reloadImg(dImg);
  document.getElementById("verifycode").focus();
  if(e) e.preventDefault();
  return false;
};
})();
//-->
</script>
</body>
</html>
