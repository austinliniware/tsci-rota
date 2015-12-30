<?php
class follow_medication_category extends getList {
	public function __construct(){
	        $this->tableName = '_web_follow_medication_category';
	        $this->key = 'id';
	        $this->wheres = '1';
	        $this->orders = 'sort asc,id';
	        $this->pageReNum = 1000;
	        // $this->permCheck=false;
	}
	public function del($id){
		global $webdb;
		$webdb->query("delete from ".$this->tableName." where parent_id='".$id."'");
		$this->delete($id);
	}
	
}
?>
