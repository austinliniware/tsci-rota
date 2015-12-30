<?php
function bbsUserInfo($id,$field=null){
	global $webdb;
	$user=$webdb->getValue("select * from cdb_members where uid='".$id."';");
	if(!$user) return false;
		else if(!$field) return $user;
			else return $user[$field];
}
?>