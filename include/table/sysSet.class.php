<?php
class sysSet {
	
	public function getAll(){
		global $webdb;
		return $webdb->getList("select * from sys_set");
	}
	
	public function setVal($key,$val){
		global $webdb;
		$webdb->query("update sys_set set sval='".$val."' where skey='".$key."';");
	}

	public function getVal($key){
		global $webdb;
		return $webdb->getValue("select sval from sys_set where skey='".$key."';",'sval');
	}
}
?>