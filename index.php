<?php
define('ROOT_PATH',dirname(__FILE__).'/');
include ROOT_PATH.'comm/common.inc.php';

if($_GET['action']=='logout'){
	unset($_SESSION);
    session_destroy();
    echo '<script>alert("登出成功!");location.href="index.php";</script>';
	exit;
}
$subPage="index.html";

if($_GET["action"]){
	$_GET["action"] = addslashes(strip_tags(trim($_GET["action"])));
	$subPage=$_GET["action"].".html";
}

$action=isset($_GET['action'])?$_GET['action']:'';
if($action==''){
	$action='index';
}
if ($action!="index") {
if(!isset($_SESSION['customer_id'])){
	redirect("index.php");
}else{
	$_SESSION['customer_id']=(int)$_SESSION['customer_id'];
	if($_SESSION['customer_id']){
		$cusClass=new registration();
		$customerInfo=$cusClass->getCustomer($_SESSION['customer_id']);
	}else{
		$customerInfo=array();
	}
}
if(isset($_GET['patient_id'])){
	$_SESSION['patient_id']=(int)$_GET['patient_id'];
} else if (!in_array($_GET["action"],$action_pages)) {
	// unset($_SESSION['patient_id']);
}
if($_SESSION['patient_id']){
	$patientClass=new patient();
	$patientInfo=$patientClass->getInfo((int)$_SESSION['patient_id']);
	if($patientInfo['birthday']=='0000-00-00'){
		$patientInfo['birthday']='';
	}
	if ($patientInfo['ACS_24']==1&&$patientInfo['2dm']==1&&$patientInfo['20years']==1&&$patientInfo['consent_given']==1&&$patientInfo['ACS_comorbidity']==0&&$patientInfo['study']==0) $Penroll=true;
	else $Penroll=false;
	$lockstatus=explode(",",$patientInfo['lock_status']);
	$qastatus=explode(",",$patientInfo['qa_status']);
	// print_r($qastatus);
}

$noPermission=false;
if ($customerInfo['group_id']<=2) {
if ($customerInfo['hospital_id']!=$patientInfo['hospital_id']) $noPermission=true;
}
$ModifyPermission=true;
if ($customerInfo['group_id']==1) {
// if (in_array($_GET["action"],$action_pages)) {
// }
$actionkey = array_search($_GET["action"], $action_pages);
if ($lockstatus[$actionkey]==1) $ModifyPermission=false;
}
}

if(file_exists(ROOT_PATH.'Tpl/'.$subPage))
include(ROOT_PATH.'Tpl/'.$subPage);
elseif(file_exists(ROOT_PATH.'Tpl/'.$_GET["action"].".php"))
include(ROOT_PATH.'Tpl/'.$_GET["action"].".php");

?>