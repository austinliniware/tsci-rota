<?php
$className=$_GET['cn'];
// $classStr=$_type[$className]['name'];
// $classStr=unescape($_COOKIE['admin_menu_name']);
include('header_title.php');
$class=new $className();
if($className=='product'){
	$class->setWhere('lang=1');
}
$class->setKw($_GET);
$class->p=$_GET['p'];
if($_GET['order']) $class->setOrder($_GET['order']);
$list=$class->getList();
$pageCtrl=$class->getPageInfoHTML();
?>
 <h1 class="title"><span><?=$classStr?$classStr." 列表":"列表"?></span></h1>
 <div class="pidding_5">
  <?include($_GET['type'].'/'.$className.'.list.php');?>
  <div class="news-viewpage"><?=$pageCtrl?></div>
 </div>
<script>
function searchFun(){
	var url=$('#searchForm').attr('action');
	$('#searchForm').find(':input[name]').each(function (){
		if($(this).val()){
			url+='&'+$(this).attr('name')+'='+$(this).val();
		}
	});
	window.location.href=url;
	return false;
}
</script>
<script>
$('input[postType]').blur(function (){
	var param={};
	param[$(this).attr('name')]=$(this).val();
	$.post('command.php?action=edit&type='+$(this).attr('postType')+'&id='+$(this).attr('postId'),param,function (){ })
})
</script>