<?php
class registration_group extends getList {
	public function __construct(){
	        $this->tableName = '_web_registration_group';
	        $this->key = 'id';
	        $this->wheres = "1";
	        $this->orders = 'sort';
	        $this->pageReNum = 15;
	}
	public function setKw($array){
		if($array["keywords"]){
			$this->setWhere("name like '%".$array["keywords"]."%'");
		}
	}
}
?>