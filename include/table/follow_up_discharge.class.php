<?php
class follow_up_discharge extends getList {
	public function __construct(){
		$this->tableName = '_web_follow_up_discharge';
		$this->key = 'id';
		$this->wheres = '';
		$this->orders = 'id';
		$this->pageReNum = 10;
	}
	/*public function add($array){
		$data=array();
		$data['re_hospitalization']=(int)$array['re_hospitalization'];
		$data['re_hospitalization_yes']=mysql_escape_string($array['re_hospitalization_yes']);
		$data['re_hospitalization_reason']=mysql_escape_string($array['re_hospitalization_reason']);
		$data['re_hospitalization_end']=date("Y-m-d",strtotime($array['re_hospitalization_end']));
		$data['re_hospitalization_start']=date("Y-m-d",strtotime($array['re_hospitalization_start']));
		$data['revascularization']=(int)$array['revascularization'];
		$data['revascularization_yes']=date("Y-m-d",strtotime($array['revascularization_yes']));
		$data['PCI']=(int)$array['PCI'];
		$data['PCI_yes_date']=date("Y-m-d",strtotime($array['PCI_yes_date']));
		$data['PCI_yes']=mysql_escape_string($array['PCI_yes']);
		$data['CABG']=(int)$array['CABG'];
		$data['CABG_yes']=date("Y-m-d",strtotime($array['CABG_yes']));
		$data['pacing']=(int)$array['pacing'];
		$data['pacing_yes']=date("Y-m-d",strtotime($array['pacing_yes']));
		$data['ICD']=(int)$array['ICD'];
		$data['ICD_yes']=date("Y-m-d",strtotime($array['ICD_yes']));
		$data['Hb']=floatval(implode('.', $array['Hb']));
		$data['Hb_not_done']=(int)$array['Hb_not_done'];
		$data['sugar_AC']=floatval($array['sugar_AC']);
		$data['2h_PC_sugar']=floatval($array['2h_PC_sugar']);
		$data['HbA1C']=floatval($array['HbA1C']);
		$data['BUN']=floatval($array['BUN']);
		$data['serum_creatinine']=floatval(implode('.', $array['serum_creatinine']));
		$data['BUN_not_done']=(int)$array['BUN_not_done'];
		$data['cholesterol']=floatval($array['cholesterol']);
		$data['cholesterol_HDL']=floatval($array['cholesterol_HDL']);
		$data['cholesterol_LDL']=floatval($array['cholesterol_LDL']);
		$data['triglyeride']=floatval($array['triglyeride']);
		$data['triglyeride_not_done']=intval($array['triglyeride_not_done']);
		$data['uric_acid']=floatval(implode('.', $array['uric_acid']));
		$data['uric_acid_not_done']=(int)$array['uric_acid_not_done'];
		$data['hypoglycemia']=(int)$array['hypoglycemia'];
		$data['hypoglycemia_sugar_level']=floatval($array['hypoglycemia_sugar_level']);
		$data['hypoglycemia_yes']=mysql_escape_string($array['hypoglycemia_yes']);
		$data['aspirin']=(int)$array['aspirin'];
		$data['clopidogrel']=(int)$array['clopidogrel'];
		$data['ticargrelor']=(int)$array['ticargrelor'];
		$data['antiplatelet']=(int)$array['antiplatelet'];
		$data['ACEi_ARB']=(int)$array['ACEi_ARB'];
		$data['beta_blocker']=(int)$array['beta_blocker'];
		$data['statin']=(int)$array['statin'];
		$data['lowering']=(int)$array['lowering'];
		$data['ca_blocker']=(int)$array['ca_blocker'];
		$data['warfarin']=(int)$array['warfarin'];
		$data['anticoagulant']=(int)$array['anticoagulant'];
		$data['digoxin']=(int)$array['digoxin'];
		$data['diuretic']=(int)$array['diuretic'];
		$data['nitrate']=(int)$array['nitrate'];
		$data['PPI']=(int)$array['PPI'];
		$data['agent_sulfonyluria']=(int)$array['agent_sulfonyluria'];
		$data['agent_metformin']=(int)$array['agent_metformin'];
		$data['agent_acabose']=(int)$array['agent_acabose'];
		$data['agent_giltazone']=(int)$array['agent_giltazone'];
		$data['DPP4_inhibitor']=(int)$array['DPP4_inhibitor'];
		$data['agent_insulin']=(int)$array['agent_insulin'];
		$data['signature']=(int)$array['signature'];
		$data['date_added']=date("Y-m-d",strtotime($array['date_added']));
		$data['patient_id']=$array['patient_id'];
		$data['user_id']=$array['user_id'];
		
		return $this->addData($data);
	}
	public function edit($array, $id){
		$data=array();
		$data['re_hospitalization']=(int)$array['re_hospitalization'];
		$data['re_hospitalization_yes']=mysql_escape_string($array['re_hospitalization_yes']);
		$data['re_hospitalization_reason']=mysql_escape_string($array['re_hospitalization_reason']);
		$data['re_hospitalization_end']=date("Y-m-d",strtotime($array['re_hospitalization_end']));
		$data['re_hospitalization_start']=date("Y-m-d",strtotime($array['re_hospitalization_start']));
		$data['revascularization']=(int)$array['revascularization'];
		$data['revascularization_yes']=date("Y-m-d",strtotime($array['revascularization_yes']));
		$data['PCI']=(int)$array['PCI'];
		$data['PCI_yes_date']=date("Y-m-d",strtotime($array['PCI_yes_date']));
		$data['PCI_yes']=mysql_escape_string($array['PCI_yes']);
		$data['CABG']=(int)$array['CABG'];
		$data['CABG_yes']=date("Y-m-d",strtotime($array['CABG_yes']));
		$data['pacing']=(int)$array['pacing'];
		$data['pacing_yes']=date("Y-m-d",strtotime($array['pacing_yes']));
		$data['ICD']=(int)$array['ICD'];
		$data['ICD_yes']=date("Y-m-d",strtotime($array['ICD_yes']));
		$data['Hb']=floatval(implode('.', $array['Hb']));
		$data['Hb_not_done']=(int)$array['Hb_not_done'];
		$data['sugar_AC']=floatval($array['sugar_AC']);
		$data['2h_PC_sugar']=floatval($array['2h_PC_sugar']);
		$data['HbA1C']=floatval($array['HbA1C']);
		$data['BUN']=floatval($array['BUN']);
		$data['serum_creatinine']=floatval(implode('.', $array['serum_creatinine']));
		$data['BUN_not_done']=(int)$array['BUN_not_done'];
		$data['cholesterol']=floatval($array['cholesterol']);
		$data['cholesterol_HDL']=floatval($array['cholesterol_HDL']);
		$data['cholesterol_LDL']=floatval($array['cholesterol_LDL']);
		$data['triglyeride']=floatval($array['triglyeride']);
		$data['triglyeride_not_done']=intval($array['triglyeride_not_done']);
		$data['uric_acid']=floatval(implode('.', $array['uric_acid']));
		$data['uric_acid_not_done']=(int)$array['uric_acid_not_done'];
		$data['hypoglycemia']=(int)$array['hypoglycemia'];
		$data['hypoglycemia_sugar_level']=floatval($array['hypoglycemia_sugar_level']);
		$data['hypoglycemia_yes']=mysql_escape_string($array['hypoglycemia_yes']);
		$data['aspirin']=(int)$array['aspirin'];
		$data['clopidogrel']=(int)$array['clopidogrel'];
		$data['ticargrelor']=(int)$array['ticargrelor'];
		$data['antiplatelet']=(int)$array['antiplatelet'];
		$data['ACEi_ARB']=(int)$array['ACEi_ARB'];
		$data['beta_blocker']=(int)$array['beta_blocker'];
		$data['statin']=(int)$array['statin'];
		$data['lowering']=(int)$array['lowering'];
		$data['ca_blocker']=(int)$array['ca_blocker'];
		$data['warfarin']=(int)$array['warfarin'];
		$data['anticoagulant']=(int)$array['anticoagulant'];
		$data['digoxin']=(int)$array['digoxin'];
		$data['diuretic']=(int)$array['diuretic'];
		$data['nitrate']=(int)$array['nitrate'];
		$data['PPI']=(int)$array['PPI'];
		$data['agent_sulfonyluria']=(int)$array['agent_sulfonyluria'];
		$data['agent_metformin']=(int)$array['agent_metformin'];
		$data['agent_acabose']=(int)$array['agent_acabose'];
		$data['agent_giltazone']=(int)$array['agent_giltazone'];
		$data['DPP4_inhibitor']=(int)$array['DPP4_inhibitor'];
		$data['agent_insulin']=(int)$array['agent_insulin'];
		$data['signature']=(int)$array['signature'];
		$data['date_added']=date("Y-m-d",strtotime($array['date_added']));
		$data['patient_id']=$array['patient_id'];
		$data['user_id']=$array['user_id'];
		
		$this->editData($data, $id);
	}*/
	public function getFud($patient_id){
		global $webdb;
		$sql="select * from ".$this->tableName." where patient_id='".(int)$patient_id."'";
		return $webdb->getValue($sql);
	}
}
?>
