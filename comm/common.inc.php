<?php
//ini_set('display_errors', false);
error_reporting(E_ALL & ~E_NOTICE & ~E_STRICT & ~E_DEPRECATED);
$developer=true;
if(empty($_SESSION)) session_start();

if(empty($charset)) $charset='utf-8';
header('Content-Type: text/html; charset='.$charset);

/** set $rooturl **/
$_dir_path=dirname(dirname(__FILE__));
$_dir_pos=strrpos($_dir_path,"\\");
if(empty($_dir_pos))$_dir_pos = strrpos($_dir_path,"/");

$_dir = substr($_dir_path,$_dir_pos+1);
$pos = strrpos($_SERVER["SCRIPT_NAME"],$_dir);

if($pos){
	$_dir=substr($_SERVER["SCRIPT_NAME"],0,strrpos($_SERVER["SCRIPT_NAME"],$_dir)+strlen($_dir)+1);
}else{
	$_dir='';
}
$rooturl = 'http://'.$_SERVER['HTTP_HOST'].(empty($_dir)?'/':$_dir);

include_once(ROOT_PATH.'conf/db.conf.php');
include_once(ROOT_PATH.'conf/common.conf.php');
include_once(ROOT_PATH.'comm/common.fun.php');
$webdb=new mysql($host);
/*
 * 數據過濾
 */
function data_check($val){
	if(is_array($val)){
		foreach($val as $k=>$v)	$val[$k]=data_check($v);
	}else{
		if(!get_magic_quotes_gpc()){
			$val=addslashes($val);
		}
		$dstr=array('/select/','/insert/','/update/','/delete/','/union/','/into/','/load_file/','/outfile/');
		$val=preg_replace($dstr, '', $val);
	}
	return $val;
}

foreach($_GET as $key => $val) $_GET[$key]=data_check($val);
foreach($_POST as $key => $val) $_POST[$key]=data_check($val);
?>