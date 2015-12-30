<?php
class question extends getList {
	public function __construct(){
	        $this->tableName = '_web_question';
	        $this->key = 'id';
	        $this->wheres = '1';
	        $this->orders = 'id';
	        $this->pageReNum = 15;
	}
	public function add($array){
		$array["date_added"]=date("Y-m-d H:i:s");
		return $this->addData($array);
	}
	public function getList(){
		$this->select="select ".$this->tableName.".*,U.name as UserName from ".$this->tableName." left join `_web_registration` as U on ".$this->tableName.".user_id=U.id";

		$this->groupby = $this->key;

		return $this->getArray();
	}
}
?>