<?php
class outcome extends getList {
	public function __construct(){
		$this->tableName = '_web_outcome';
		$this->key = 'id';
		$this->wheres = '';
		$this->orders = 'id';
		$this->pageReNum = 10;
	}
	/*public function add($array){
		$data=array();
		$data['re_MI']=(int)$array['re_MI'];
		$data['clopidogrel']=(int)$array['clopidogrel'];
		$data['minor_bleed']=(int)$array['minor_bleed'];
		$data['minor_bleed_yes']=(int)$array['minor_bleed_yes'];
		$data['minor_bleed_site']=mysql_escape_string($array['minor_bleed_site']);
		$data['transfusion']=(int)$array['transfusion'];
		$data['transfusion_PRBC']=floatval($array['transfusion_PRBC']);
		$data['transfusion_FFP']=floatval($array['transfusion_FFP']);
		$data['transfusion_platelet']=floatval($array['transfusion_platelet']);
		$data['revasc']=(int)$array['revasc'];
		$data['stroke']=(int)$array['stroke'];
		$data['stroke_yes']=mysql_escape_string($array['stroke_yes']);
		$data['shock']=(int)$array['shock'];
		$data['arrhythmia']=(int)$array['arrhythmia'];
		$data['arrhythmia_yes']=mysql_escape_string($array['arrhythmia_yes']);
		$data['arrhythmia_anytime']=mysql_escape_string($array['arrhythmia_anytime']);
		$data['onset_AF']=(int)$array['onset_AF'];
		$data['failure']=(int)$array['failure'];
		$data['diagnosis']=mysql_escape_string($array['diagnosis']);
		$data['diagnosis_other']=mysql_escape_string($array['diagnosis_other']);
		$data['discharge_date']=date("Y-m-d H:i:s",strtotime($array['discharge_date']['date'].' '.$array['discharge_date']['h'].':'.$array['discharge_date']['m']));
		$data['signature']=(int)$array['signature'];
		$data['date_added']=date("Y-m-d",strtotime($array['date_added']));
		$data['patient_id']=$array['patient_id'];
		$data['user_id']=$array['user_id'];
		
		return $this->addData($data);
	}
	public function edit($array, $id){
		$data=array();
		$data['re_MI']=(int)$array['re_MI'];
		$data['clopidogrel']=(int)$array['clopidogrel'];
		$data['minor_bleed']=(int)$array['minor_bleed'];
		$data['minor_bleed_yes']=(int)$array['minor_bleed_yes'];
		$data['minor_bleed_site']=mysql_escape_string($array['minor_bleed_site']);
		$data['transfusion']=(int)$array['transfusion'];
		$data['transfusion_PRBC']=floatval($array['transfusion_PRBC']);
		$data['transfusion_FFP']=floatval($array['transfusion_FFP']);
		$data['transfusion_platelet']=floatval($array['transfusion_platelet']);
		$data['revasc']=(int)$array['revasc'];
		$data['stroke']=(int)$array['stroke'];
		$data['stroke_yes']=mysql_escape_string($array['stroke_yes']);
		$data['shock']=(int)$array['shock'];
		$data['arrhythmia']=(int)$array['arrhythmia'];
		$data['arrhythmia_yes']=mysql_escape_string($array['arrhythmia_yes']);
		$data['arrhythmia_anytime']=mysql_escape_string($array['arrhythmia_anytime']);
		$data['onset_AF']=(int)$array['onset_AF'];
		$data['failure']=(int)$array['failure'];
		$data['diagnosis']=mysql_escape_string($array['diagnosis']);
		$data['diagnosis_other']=mysql_escape_string($array['diagnosis_other']);
		$data['discharge_date']=date("Y-m-d H:i:s",strtotime($array['discharge_date']['date'].' '.$array['discharge_date']['h'].':'.$array['discharge_date']['m']));
		$data['signature']=(int)$array['signature'];
		$data['date_added']=date("Y-m-d",strtotime($array['date_added']));
		$data['patient_id']=$array['patient_id'];
		$data['user_id']=$array['user_id'];
		
		$this->editData($data, $id);
	}*/
	public function getOutcome($patient_id){
		global $webdb;
		$sql="select * from ".$this->tableName." where patient_id='".(int)$patient_id."'";
		return $webdb->getValue($sql);
	}
}
?>
