<?php
class medication extends getList {
	public function __construct(){
		$this->tableName = '_web_medication';
		$this->key = 'id';
		$this->wheres = '';
		$this->orders = 'id';
		$this->pageReNum = 10;
	}
	/*public function add($array){
		$data=array();
		$data['aspirin']=(int)$array['aspirin'];
		$data['clopidogrel']=(int)$array['clopidogrel'];
		$data['ticlopidine']=(int)$array['ticlopidine'];
		$data['ticargrelor']=(int)$array['ticargrelor'];
		$data['warfarin']=(int)$array['warfarin'];
		$data['rivaroxaban']=(int)$array['rivaroxaban'];
		$data['inhibitors']=(int)$array['inhibitors'];
		$data['heparin']=(int)$array['heparin'];
		$data['weight_heparin']=(int)$array['weight_heparin'];
		$data['ACE_inhibitor']=(int)$array['ACE_inhibitor'];
		$data['blocker']=(int)$array['blocker'];
		$data['beta_blocker']=(int)$array['beta_blocker'];
		$data['statin']=(int)$array['statin'];
		$data['agent']=(int)$array['agent'];
		$data['antagonist']=(int)$array['antagonist'];
		$data['digoxin']=(int)$array['digoxin'];
		$data['diuretic']=(int)$array['diuretic'];
		$data['IV_agent']=(int)$array['IV_agent'];
		$data['insulin']=(int)$array['insulin'];
		$data['sulfonylurea_agent']=(int)$array['sulfonylurea_agent'];
		$data['metformin']=(int)$array['metformin'];
		$data['glitazone']=(int)$array['glitazone'];
		$data['DPP4_inhibitor']=(int)$array['DPP4_inhibitor'];
		$data['nitrate']=(int)$array['nitrate'];
		$data['patient_id']=$array['patient_id'];
		$data['user_id']=$array['user_id'];
		
		return $this->addData($data);
	}
	public function edit($array, $id){
		$data=array();
		$data['aspirin']=(int)$array['aspirin'];
		$data['clopidogrel']=(int)$array['clopidogrel'];
		$data['ticlopidine']=(int)$array['ticlopidine'];
		$data['ticargrelor']=(int)$array['ticargrelor'];
		$data['warfarin']=(int)$array['warfarin'];
		$data['rivaroxaban']=(int)$array['rivaroxaban'];
		$data['inhibitors']=(int)$array['inhibitors'];
		$data['heparin']=(int)$array['heparin'];
		$data['weight_heparin']=(int)$array['weight_heparin'];
		$data['ACE_inhibitor']=(int)$array['ACE_inhibitor'];
		$data['blocker']=(int)$array['blocker'];
		$data['beta_blocker']=(int)$array['beta_blocker'];
		$data['statin']=(int)$array['statin'];
		$data['agent']=(int)$array['agent'];
		$data['antagonist']=(int)$array['antagonist'];
		$data['digoxin']=(int)$array['digoxin'];
		$data['diuretic']=(int)$array['diuretic'];
		$data['IV_agent']=(int)$array['IV_agent'];
		$data['insulin']=(int)$array['insulin'];
		$data['sulfonylurea_agent']=(int)$array['sulfonylurea_agent'];
		$data['metformin']=(int)$array['metformin'];
		$data['glitazone']=(int)$array['glitazone'];
		$data['DPP4_inhibitor']=(int)$array['DPP4_inhibitor'];
		$data['nitrate']=(int)$array['nitrate'];
		$data['patient_id']=$array['patient_id'];
		$data['user_id']=$array['user_id'];
		
		$this->editData($data, $id);
	}*/
	public function getMedication($patient_id){
		global $webdb;
		$sql="select * from ".$this->tableName." where patient_id='".(int)$patient_id."'";
		return $webdb->getValue($sql);
	}
}
?>
