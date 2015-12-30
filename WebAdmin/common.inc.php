<?php
if(!defined('ROOT_PATH'))define('ROOT_PATH',dirname(dirname(__FILE__)).'/');
include(ROOT_PATH.'comm/common.inc.php');
/*
 * 用戶登錄信息
 */
session_start();
if($_SESSION["ADMIN_ID"]=="" && !$noLocation){
   session_destroy();
   go('login.php');
}
$admin_folder=true;
?>