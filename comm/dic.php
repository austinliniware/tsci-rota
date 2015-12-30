<?php
include ('../common.inc.php');
if(file_exists('dic.'.$_GET['type'].'.php')){
	include('dic.'.$_GET['type'].'.php');
}else if(class_exists($_GET['type'])){
	$class=new $_GET['type'];
	$class->setKw($_GET);
	echo aryOption($class->getList());
}
?>