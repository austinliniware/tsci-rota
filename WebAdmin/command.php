<?php
include('common.inc.php');
/*
 * 提交後的處理頁
 * 其中GET中的type為操作類型,可以讓command對應的command.type,即各個類型的單獨的處理文件中
 * GET中的action為操作,一般包括edit/delete等等
 * GET中的id為需要edit或者delete的記錄的ID
 */
$id=intval($_GET['id']);
$type=$_GET['type'];
$action=$_GET['action'];
if($type){
	if(class_exists($type) && in_array($action,array('info','add','edit','del','sysSet'))){
		$class=new $type;
		switch($action){
			case 'info':
				dis($class->getInfo($id));
				exit;
				break;
			case 'add':
				if(isset($_POST['titlesub'])){
					$_POST['ntype']=$_GET['ntype'];
					$_POST['is_show']=1;
					$id=$class->add($_POST);
				}else {
					$id=$class->add($_POST);
				}
				echo "{id:".intval($id).",ok:'yes'}";
				exit;
				break;
			case 'edit':
				if(isset($_POST['recommend'])){
					if($type=='product_category' or $type=='product'){
						$p_id='product_id';
					}elseif ($type=='solution'){
						$p_id='solution_id';
					}elseif ($type=='service'){
						$p_id='service_id';
					}elseif($type=='service_category' ){
						$p_id='service_id';
					}
					$webdb->query("update ".$class->tableName." set `recommend`='{$_POST['recommend']}' where ".$p_id."=".$id);
				}
				if(isset($_POST['is_show'])){
					if($type=='product_category' or $type=='product'){
						$p_id='product_id';
					}elseif($type=='news_category'){
						$p_id='category_id';
					}elseif($type=='news'){
						$p_id='news_id';
					}elseif($type=='service_category' ){
						$p_id='service_id';
					}if($type=='solution_category' or $type=='solution'){
						$p_id='solution_id';
					}elseif ($type=='service'){
						$p_id='service_id';
					}elseif ($type=='district'){
						$p_id='district_id';
					}elseif ($type=='country'){
						$p_id='country_id';
					}elseif ($type=='market_country'){
						$p_id='market_country_id';
					}elseif ($type=='market_area'){
						$p_id='area_id';
					}elseif ($type=='slider'){
						if($_GET['ntype']==4){
							$p_id='id';
						}else{
							$p_id='slider_id';
						}						
					}else{
						$p_id='id';
					}
					$webdb->query("update ".$class->tableName." set `is_show`='{$_POST['is_show']}' where $p_id=".$id);
				}
				if(isset($_POST['istop'])){
					if($_POST['istop']==1){
					//$check = $webdb->getValue('select istop,is_show from '.$class->tableName.' where istop=1 and is_show=1 and ntype=2 and lang='.$_POST['lang']);
					//echo $check;
					//if($check>=1){
					//exit("{exits}");
					//}else{
						$webdb->query("update ".$class->tableName." set `istop`='{$_POST['istop']}' where news_id=".$id);
					//}
					}else{
						$webdb->query("update ".$class->tableName." set `istop`='{$_POST['istop']}' where news_id=".$id);
					}
				}
				if(isset($_POST['descno'])){
					if($type=='news'){
						$p_id='news_id';
					}else{
						$p_id='id';
					}
					$webdb->query("update ".$class->tableName." set `descno`='{$_POST['descno']}' where ".$p_id."=".$id);
				}
				if(isset($_POST['titlesub'])){
				$webdb->query("update ".$class->tableName." set `titlesub`='{$_POST['titlesub']}' where id=".$id);
				}
				if(isset($_POST['sort'])){
					if($type=='product_category' or $type=='product'){
						$p_id='product_id';
					}if($type=='solution_category' or $type=='solution'){
						$p_id='solution_id';
					}elseif($type=='news_category'){
						$p_id='category_id';
					}elseif($type=='news'){
						$p_id='news_id';
					}elseif($type=='service_category' ){
						$p_id='service_id';
					}elseif ($type=='district'){
						$p_id='district_id';
					}elseif ($type=='country'){
						$p_id='country_id';
					}elseif ($type=='market_country'){
						$p_id='market_country_id';
					}elseif ($type=='market_area'){
						$p_id='area_id';
					}else{
						$p_id='id';
					}
				$webdb->query("update ".$class->tableName." set `sort`='{$_POST['sort']}' where $p_id=".$id);
				}
				break;
			case 'del':
				if(in_array($type,array('service_category','product_category','news_category','news','district','country','market_area','market_country'))){
					$class->del_cat($id);
				}else{
					$class->del($id);
				}
				break;
			case 'sysSet':
				$class->setVal($_GET['skey'],$_POST[$_GET['skey']]);
				break;
		}
	}else{
		include('command.'.$type.'.php');
	}
	echo "{ok:'yes'}";
	exit;
}
?>