<?php
class procedure extends getList {
	public function __construct(){
		$this->tableName = '_web_procedure';
		$this->key = 'id';
		$this->wheres = '';
		$this->orders = 'id';
		$this->pageReNum = 10;
	}
	/*public function add($array){
		$data=array();
		$data['transfer']=(int)$array['transfer'];
		$data['angiography']=(int)$array['angiography'];
		$data['angiography_date']=date("Y-m-d H:i:s",strtotime($array['angiography_date']['date'].' '.$array['angiography_date']['h'].':'.$array['angiography_date']['m']));
		$data['territories']=mysql_escape_string($array['territories']);
		$data['stenosis']=floatval($array['stenosis']);
		$data['artery_flow']=mysql_escape_string($array['artery_flow']);
		$data['artery_territory']=mysql_escape_string($array['artery_territory']);
		$data['balloon_pump']=(int)$array['balloon_pump'];
		$data['performed']=(int)$array['performed'];
		$data['incl_TOE__TTE']=mysql_escape_string($array['incl_TOE__TTE']);
		$data['fraction']=mysql_escape_string(implode(",", $array['fraction']));
		$data['fraction_1']=mysql_escape_string($array['fraction_1']);
		$data['fraction_2']=mysql_escape_string($array['fraction_2']);
		$data['fraction_3']=mysql_escape_string($array['fraction_3']);
		$data['fraction_4']=mysql_escape_string($array['fraction_4']);
		$data['intervention_status']=(int)$array['intervention_status'];
		$data['intervention_date']=date("Y-m-d H:i:s",strtotime($array['intervention_date']['date'].' '.$array['intervention_date']['h'].':'.$array['intervention_date']['m']));
		$data['stent_type']=mysql_escape_string($array['stent_type']);
		$data['os_stent']=(int)$array['os_stent'];
		$data['ls_treated']=(int)$array['ls_treated'];
		$data['CABG']=(int)$array['CABG'];
		$data['CABG_date']=date("Y-m-d H:i:s",strtotime($array['CABG_date']['date'].' '.$array['CABG_date']['h'].':'.$array['CABG_date']['m']));
		$data['pacemaker_date']=date("Y-m-d H:i:s",strtotime($array['pacemaker_date']['date'].' '.$array['pacemaker_date']['h'].':'.$array['pacemaker_date']['m']));
		$data['ICD']=(int)$array['ICD'];
		$data['ICD_date']=date("Y-m-d H:i:s",strtotime($array['ICD_date']['date'].' '.$array['ICD_date']['h'].':'.$array['ICD_date']['m']));
		$data['pacing']=(int)$array['pacing'];
		$data['pacing_date']=date("Y-m-d H:i:s",strtotime($array['pacing_date']['date'].' '.$array['pacing_date']['h'].':'.$array['pacing_date']['m']));
		$data['functional_stress']=(int)$array['functional_stress'];
		$data['functional_stress_date']=date("Y-m-d H:i:s",strtotime($array['functional_stress_date']['date'].' '.$array['functional_stress_date']['h'].':'.$array['functional_stress_date']['m']));
		$data['functional_stress_1']=mysql_escape_string($array['functional_stress_1']);
		$data['functional_stress_result']=mysql_escape_string($array['functional_stress_result']);
		$data['patient_id']=$array['patient_id'];
		$data['user_id']=$array['user_id'];
		
		return $this->addData($data);
	}
	public function edit($array, $id){
		$data=array();
		$data['transfer']=(int)$array['transfer'];
		$data['angiography']=(int)$array['angiography'];
		$data['angiography_date']=date("Y-m-d H:i:s",strtotime($array['angiography_date']['date'].' '.$array['angiography_date']['h'].':'.$array['angiography_date']['m']));
		$data['territories']=mysql_escape_string($array['territories']);
		$data['stenosis']=floatval($array['stenosis']);
		$data['artery_flow']=mysql_escape_string($array['artery_flow']);
		$data['artery_territory']=mysql_escape_string($array['artery_territory']);
		$data['balloon_pump']=(int)$array['balloon_pump'];
		$data['performed']=(int)$array['performed'];
		$data['incl_TOE__TTE']=mysql_escape_string($array['incl_TOE__TTE']);
		$data['fraction']=mysql_escape_string(implode(",", $array['fraction']));
		$data['fraction_1']=mysql_escape_string($array['fraction_1']);
		$data['fraction_2']=mysql_escape_string($array['fraction_2']);
		$data['fraction_3']=mysql_escape_string($array['fraction_3']);
		$data['fraction_4']=mysql_escape_string($array['fraction_4']);
		$data['intervention_status']=(int)$array['intervention_status'];
		$data['intervention_date']=date("Y-m-d H:i:s",strtotime($array['intervention_date']['date'].' '.$array['intervention_date']['h'].':'.$array['intervention_date']['m']));
		$data['stent_type']=mysql_escape_string($array['stent_type']);
		$data['os_stent']=(int)$array['os_stent'];
		$data['ls_treated']=(int)$array['ls_treated'];
		$data['CABG']=(int)$array['CABG'];
		$data['CABG_date']=date("Y-m-d H:i:s",strtotime($array['CABG_date']['date'].' '.$array['CABG_date']['h'].':'.$array['CABG_date']['m']));
		$data['pacemaker_date']=date("Y-m-d H:i:s",strtotime($array['pacemaker_date']['date'].' '.$array['pacemaker_date']['h'].':'.$array['pacemaker_date']['m']));
		$data['ICD']=(int)$array['ICD'];
		$data['ICD_date']=date("Y-m-d H:i:s",strtotime($array['ICD_date']['date'].' '.$array['ICD_date']['h'].':'.$array['ICD_date']['m']));
		$data['pacing']=(int)$array['pacing'];
		$data['pacing_date']=date("Y-m-d H:i:s",strtotime($array['pacing_date']['date'].' '.$array['pacing_date']['h'].':'.$array['pacing_date']['m']));
		$data['functional_stress']=(int)$array['functional_stress'];
		$data['functional_stress_date']=date("Y-m-d H:i:s",strtotime($array['functional_stress_date']['date'].' '.$array['functional_stress_date']['h'].':'.$array['functional_stress_date']['m']));
		$data['functional_stress_1']=mysql_escape_string($array['functional_stress_1']);
		$data['functional_stress_result']=mysql_escape_string($array['functional_stress_result']);
		$data['patient_id']=$array['patient_id'];
		$data['user_id']=$array['user_id'];
		
		$this->editData($data, $id);
	}*/
	public function getProcedure($patient_id){
		global $webdb;
		$sql="select * from ".$this->tableName." where patient_id='".(int)$patient_id."'";
		return $webdb->getValue($sql);
	}
}
?>
