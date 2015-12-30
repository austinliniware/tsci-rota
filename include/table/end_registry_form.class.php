<?php
class end_registry_form extends getList {
	public function __construct(){
		$this->tableName = '_web_end_registry_form';
		$this->key = 'id';
		$this->wheres = '';
		$this->orders = 'id';
		$this->pageReNum = 10;
	}
	/*public function add($array){
		$data=array();
		$data['date_study']=date("Y-m-d",strtotime($array['date_study']));
		$data['complete_study']=(int)$array['complete_study'];
		$data['primary_reason']=mysql_escape_string($array['primary_reason']);
		$data['date_death']=date("Y-m-d",strtotime($array['date_death']));
		$data['other_reason']=mysql_escape_string($array['other_reason']);
		$data['signature']=(int)$array['signature'];
		$data['date_added']=date("Y-m-d",strtotime($array['date_added']));
		$data['patient_id']=$array['patient_id'];
		$data['user_id']=$array['user_id'];
		
		return $this->addData($data);
	}
	public function edit($array, $id){
		$data=array();
		$data['date_study']=date("Y-m-d",strtotime($array['date_study']));
		$data['complete_study']=(int)$array['complete_study'];
		$data['primary_reason']=mysql_escape_string($array['primary_reason']);
		$data['date_death']=date("Y-m-d",strtotime($array['date_death']));
		$data['other_reason']=mysql_escape_string($array['other_reason']);
		$data['signature']=(int)$array['signature'];
		$data['date_added']=date("Y-m-d",strtotime($array['date_added']));
		$data['patient_id']=$array['patient_id'];
		$data['user_id']=$array['user_id'];
		
		$this->editData($data, $id);
	}*/
	public function getErf($patient_id){
		global $webdb;
		$sql="select * from ".$this->tableName." where patient_id='".(int)$patient_id."'";
		return $webdb->getValue($sql);
	}
}
?>
