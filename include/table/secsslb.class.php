<?php
class secsslb extends getList {
	public function __construct(){
	        $this->tableName = '_ffr_6secsslb';
	        $this->key = 'id';
	        $this->wheres = '1';
	        $this->orders = 'id';
	        $this->pageReNum = 15;
	}
	public function getsecsslb($patient_id){
		global $webdb;
		$sql="select * from ".$this->tableName." where patient_id='".(int)$patient_id."'";
		return $webdb->getValue($sql);
	}
}
?>
