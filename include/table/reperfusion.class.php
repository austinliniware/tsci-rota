<?php
class reperfusion extends getList {
	public function __construct(){
		$this->tableName = '_web_reperfusion';
		$this->key = 'id';
		$this->wheres = '';
		$this->orders = 'id';
		$this->pageReNum = 10;
	}
	/*public function add($array){
		$data=array();
		$data['therapy']=(int)$array['therapy'];
		$data['fibrinolysis_started']=date("Y-m-d H:i:s",strtotime($array['fibrinolysis_started']['date'].' '.$array['fibrinolysis_started']['h'].':'.$array['fibrinolysis_started']['m']));
		$data['fibrinolysis']=mysql_escape_string($array['fibrinolysis']);
		$data['fibrinolysis_code']=mysql_escape_string($array['fibrinolysis_code']);
		$data['angioplasty_stenting']=(int)$array['angioplasty_stenting'];
		if($data['angioplasty_stenting']){
			$data['angioplasty_s_yes']=date("Y-m-d H:i:s",strtotime($array['angioplasty_s_yes']['date'].' '.$array['angioplasty_s_yes']['h'].':'.$array['angioplasty_s_yes']['m']));
			$data['door_balloon_time']=(int)$array['door_balloon_time'];
		}else{
			$data['angioplasty_s_yes']='';
			$data['door_balloon_time']='';
		}

		$data['rescue_angioplasty']=(int)$array['rescue_angioplasty'];
		if($data['rescue_angioplasty']){
			$data['rescue_angioplasty_time']=date("Y-m-d H:i:s",strtotime($array['rescue_angioplasty_time']['date'].' '.$array['rescue_angioplasty_time']['h'].':'.$array['rescue_angioplasty_time']['m']));
		}else{
			$data['rescue_angioplasty_time']='';
		}

		$data['patient_id']=$array['patient_id'];
		$data['user_id']=$array['user_id'];

		return $this->addData($data);
	}
	public function edit($array, $id){
		$data=array();
		$data['therapy']=(int)$array['therapy'];
		$data['fibrinolysis_started']=date("Y-m-d H:i:s",strtotime($array['fibrinolysis_started']['date'].' '.$array['fibrinolysis_started']['h'].':'.$array['fibrinolysis_started']['m']));
		$data['fibrinolysis']=mysql_escape_string($array['fibrinolysis']);
		$data['fibrinolysis_code']=mysql_escape_string($array['fibrinolysis_code']);
		$data['angioplasty_stenting']=(int)$array['angioplasty_stenting'];
		if($data['angioplasty_stenting']){
			$data['angioplasty_s_yes']=date("Y-m-d H:i:s",strtotime($array['angioplasty_s_yes']['date'].' '.$array['angioplasty_s_yes']['h'].':'.$array['angioplasty_s_yes']['m']));
			$data['door_balloon_time']=(int)$array['door_balloon_time'];
		}else{
			$data['angioplasty_s_yes']='';
			$data['door_balloon_time']='';
		}

		$data['rescue_angioplasty']=(int)$array['rescue_angioplasty'];
		if($data['rescue_angioplasty']){
			$data['rescue_angioplasty_time']=date("Y-m-d H:i:s",strtotime($array['rescue_angioplasty_time']['date'].' '.$array['rescue_angioplasty_time']['h'].':'.$array['rescue_angioplasty_time']['m']));
		}else{
			$data['rescue_angioplasty_time']='';
		}
		$data['patient_id']=$array['patient_id'];
		$data['user_id']=$array['user_id'];

		$this->editData($data, $id);
	}*/
	public function getReperfusion($patient_id){
		global $webdb;
		$sql="select * from ".$this->tableName." where patient_id='".(int)$patient_id."'";
		return $webdb->getValue($sql);
	}
}
?>
