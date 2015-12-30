<?php
define('ROOT_PATH',dirname(dirname(__FILE__)).'/');
include_once(ROOT_PATH.'WebAdmin/common.inc.php');
$subPage="welcome.php";
if($_GET['do']){
	if($_GET['type']){
		if($_GET['cn']){
			$subPage=$_GET['do'].'.php';
		}else{
			$subPage=$_GET['type'].'/'.$_GET['do'].'.php';
		}
	}else $subPage=$_GET['do'].'.php';
}

if($_SESSION["ADMIN_ID"]=="" && !$noLocation){
   session_destroy();
   go('login.php');
}
global $language_id;
if($_SESSION['langid']){
	$language_id=$_SESSION['langid'];
}
if(!empty($_GET['langid'])){
	$language_id=$_GET['langid'];
	$_SESSION['langid']=$language_id;
}
if(empty($_GET['langid'])){
	$language_id=1;
	$_SESSION['langid']=$language_id;
}
if(empty($_SESSION['langid'])){
	$language_id=1;
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>TAIWAN SOCIETY OF CARDIOLOGY - ACS-DM Registry Case report form 後臺登錄管理系統<?//=$_COOKIE['admin_menu_name']?" - ".unescape($_COOKIE['admin_menu_name']):""?></title>
<link href="style/css/css2.css" rel="stylesheet" type="text/css" media="all" />
<!-- JQuery文件 -->
<script type="text/javascript" src="../include/jscode/jquery.js"></script>
<script type="text/javascript" src="../include/jscode/jquery/jquery.date.js"></script>
<script type="text/javascript" src="../include/jscode/jquery/jquery.select.js"></script>
<script type="text/javascript" src="../include/jscode/calendar.js"></script>
<!-- Cookie文件 -->
<script type="text/javascript" src="../include/jscode/cookie.js"></script>
<!-- 公共JS文件 -->
<script type="text/javascript" src="../comm/comm.js"></script>
<script type="text/javascript" src="index.js"></script>
<link rev="stylesheet" type="text/css" href="style/tree/tree_menu.css" media="all">
<script type="text/javascript" src="style/tree/tree_menu.js"></script>
<!--script type="text/javascript" src="ajax/ajaxfileupload.js" ></script-->

<link rel="stylesheet" type="text/css" href="style/css/admin2.css" />
</head>

<body>
<div class="logobox"><span><A HREF="login.php?out=yes"><input title="登出" class="topbut01" name="logout" type="button" value="" /></a><A HREF="../" target="_blank"><input class="topbut02" name="front" title="前臺主頁" type="button" value="" /></a></span></div>
<div class="centerbox">
 
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td valign="top" width="209px">
        <div><img src="style/images/leftpic_03.jpg" width="209" height="51" /></div>
        <div class="leftbox">
              <div style="min-height:500px;">
                   <div id="leftmenubox">
                   	<?php 
			               // get_group_id_by_admin_id($_SESSION["ADMIN_ID"]);
			               echo createmenu(getMenuData($_SESSION["ADMIN_ID"]),"","admin");
		              	?>
		              	
		              	<div style="margin-left:15px;"><A HREF="login.php?out=yes"><div style="float:left"><img src="style/images/main_r1_c35.gif" width="16" border="0" title="登出"></div><div style="height:40px;float:left;margin-top:13px;">登出</div></A></div>                
                   </div>
             </div>
             <div><img src="style/images/leftpic_10.jpg" width="190" height="5" /></div>
        </div>
        
    </td>
    <td valign="top" class="rightbox">
       <div id="right">
		<div style="clear:both;"></div>
		<div class="content rightmainbox">
			
            <?if($subPage) include($subPage)?></div>
        </div>
      </div>
    </td>
  </tr>
</table>
<?if($altmsg || $altmsg=$_GET['altmsg']){?>
<script>alert('<?php echo $altmsg;?>');</script>
<?}?>
</div>
</body>
</html>