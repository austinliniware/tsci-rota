<?php
class risk_stratification extends getList {
	public function __construct(){
		$this->tableName = '_web_risk_stratification';
		$this->key = 'id';
		$this->wheres = '1';
		$this->orders = 'id';
		$this->pageReNum = 15;
	}
	/*public function add($array){
		$data=array();
		$data['height']=floatval($array['height']);
		$data['weight']=floatval(implode(".", $array['weight']));
		$data['waist_circumference']=(int)$array['waist_circumference'];
		$data['serum_creatinine']=floatval(implode(".", $array['serum_creatinine']));
		$data['height_not_done']=(int)$array['height_not_done'];
		$data['weight_not_done']=(int)$array['weight_not_done'];
		$data['waist_c_not_done']=(int)$array['waist_c_not_done'];
		$data['serum_c_not_done']=(int)$array['serum_c_not_done'];
		$data['dialysis_dependent']=(int)$array['dialysis_dependent'];
		$data['dialysis_dependent_yes']=mysql_escape_string($data['dialysis_dependent']?$array['dialysis_dependent_yes']:'');
		$data['WBC']=(int)$array['WBC'];
		$data['WBC_not_done']=(int)$array['WBC_not_done'];
		$data['hemoglobin']=floatval(implode(".", $array['hemoglobin']));
		$data['hemoglobin_not_done']=(int)$array['hemoglobin_not_done'];
		$data['glucoseTT']=(int)$array['glucoseTT'];
		$data['glucoseTT_yes']=mysql_escape_string($data['glucoseTT']?$array['glucoseTT_yes']:'');
		$data['sugar_AC']=(int)$array['sugar_AC'];
		$data['2h_PC_sugar']=(int)$array['2h_PC_sugar'];
		$data['HbA1C']=floatval($array['HbA1C']);
		$data['total_chol']=(int)$array['total_chol'];
		$data['HDL']=(int)$array['HDL'];
		$data['LDL']=floatval(implode(".", $array['LDL']));
		$data['triglyceride']=(int)$array['triglyceride'];
		$data['lipid_profile_not_done']=(int)$array['lipid_profile_not_done'];
		$data['uric_acid']=floatval(implode('.', $array['uric_acid']));
		$data['uric_acid_not_done']=(int)$array['uric_acid_not_done'];
		$data['Na']=floatval(implode('.', $array['Na']));
		$data['Na_not_done']=(int)$array['Na_not_done'];
		$data['K']=floatval(implode('.', $array['K']));
		$data['K_not_done']=(int)$array['K_not_done'];
		$data['Mg']=floatval(implode('.', $array['Mg']));
		$data['Mg_not_done']=(int)$array['Mg_not_done'];
		$data['BNP']=floatval(implode('.', $array['BNP']));
		$data['BNP_not_done']=(int)$array['BNP_not_done'];
		$data['hs_CRP']=floatval(implode('.', $array['hs_CRP']));
		$data['hs_CRP_not_done']=(int)$array['hs_CRP_not_done'];
		$data['h_dyslipidemia']=(int)$array['h_dyslipidemia'];
		$data['h_dyslipidemia_treated']=(int)$array['h_dyslipidemia_treated'];
		$data['h_hypertension']=(int)$array['h_hypertension'];
		$data['h_hypertension_treated']=(int)$array['h_hypertension_treated'];
		$data['h_diabetes']=(int)$array['h_diabetes'];
		if($data['h_diabetes']){
			$data['hd_DM']=floatval(implode('.', $array['hd_DM']));
			$data['hd_DM_unknown']=(int)$array['hd_DM_unknown'];
			$data['diet_only']=(int)$array['diet_only'];
			$data['antidiabetic']=mysql_escape_string($array['antidiabetic']);
		}else{
			$data['hd_DM']='';
			$data['hd_DM_unknown']='';
			$data['diet_only']='';
			$data['antidiabetic']='';
		}
		$data['smoker']=mysql_escape_string($array['smoker']);
		$data['family_history']=mysql_escape_string($array['family_history']);
		$data['known_CAD']=(int)$array['known_CAD'];
		$data['stable_angina']=(int)$array['stable_angina'];
		if($data['stable_angina']){
			$data['ex_study']=(int)$array['ex_study'];
			$data['stenosis']=(int)$array['stenosis'];
		}else{
			$data['ex_study']='';
			$data['stenosis']='';
		}
		$data['previous_MI']=(int)$array['previous_MI'];
		$data['previous_PCI']=(int)$array['previous_PCI'];
		$data['previous_CABG']=(int)$array['previous_CABG'];
		$data['fibrillation']=(int)$array['fibrillation'];
		$data['PHFailure']=(int)$array['PHFailure'];
		$data['COPD']=(int)$array['COPD'];
		$data['OSApnea']=(int)$array['OSApnea'];
		$data['PA_disease']=(int)$array['PA_disease'];
		$data['malignancy']=(int)$array['malignancy'];
		$data['C_accident']=(int)$array['C_accident'];
		$data['C_accident_1']=mysql_escape_string($array['C_accident_1']);
		$data['C_accident_2']=mysql_escape_string($array['C_accident_2']);
		$data['patient_id']=$array['patient_id'];
		$data['user_id']=$array['user_id'];

		return $this->addData($data);
	}
	public function edit($array, $id){
		$data=array();
		$data['height']=floatval($array['height']);
		$data['weight']=floatval(implode(".", $array['weight']));
		$data['waist_circumference']=(int)$array['waist_circumference'];
		$data['serum_creatinine']=floatval(implode(".", $array['serum_creatinine']));
		$data['height_not_done']=(int)$array['height_not_done'];
		$data['weight_not_done']=(int)$array['weight_not_done'];
		$data['waist_c_not_done']=(int)$array['waist_c_not_done'];
		$data['serum_c_not_done']=(int)$array['serum_c_not_done'];
		$data['dialysis_dependent']=(int)$array['dialysis_dependent'];
		$data['dialysis_dependent_yes']=mysql_escape_string($data['dialysis_dependent']?$array['dialysis_dependent_yes']:'');
		$data['WBC']=(int)$array['WBC'];
		$data['WBC_not_done']=(int)$array['WBC_not_done'];
		$data['hemoglobin']=floatval(implode(".", $array['hemoglobin']));
		$data['hemoglobin_not_done']=(int)$array['hemoglobin_not_done'];
		$data['glucoseTT']=(int)$array['glucoseTT'];
		$data['glucoseTT_yes']=mysql_escape_string($data['glucoseTT']?$array['glucoseTT_yes']:'');
		$data['sugar_AC']=(int)$array['sugar_AC'];
		$data['2h_PC_sugar']=(int)$array['2h_PC_sugar'];
		$data['HbA1C']=floatval($array['HbA1C']);
		$data['total_chol']=(int)$array['total_chol'];
		$data['HDL']=(int)$array['HDL'];
		$data['LDL']=floatval(implode(".", $array['LDL']));
		$data['triglyceride']=(int)$array['triglyceride'];
		$data['lipid_profile_not_done']=(int)$array['lipid_profile_not_done'];
		$data['uric_acid']=floatval(implode('.', $array['uric_acid']));
		$data['uric_acid_not_done']=(int)$array['uric_acid_not_done'];
		$data['Na']=floatval(implode('.', $array['Na']));
		$data['Na_not_done']=(int)$array['Na_not_done'];
		$data['K']=floatval(implode('.', $array['K']));
		$data['K_not_done']=(int)$array['K_not_done'];
		$data['Mg']=floatval(implode('.', $array['Mg']));
		$data['Mg_not_done']=(int)$array['Mg_not_done'];
		$data['BNP']=floatval(implode('.', $array['BNP']));
		$data['BNP_not_done']=(int)$array['BNP_not_done'];
		$data['hs_CRP']=floatval(implode('.', $array['hs_CRP']));
		$data['hs_CRP_not_done']=(int)$array['hs_CRP_not_done'];
		$data['h_dyslipidemia']=(int)$array['h_dyslipidemia'];
		$data['h_dyslipidemia_treated']=(int)$array['h_dyslipidemia_treated'];
		$data['h_hypertension']=(int)$array['h_hypertension'];
		$data['h_hypertension_treated']=(int)$array['h_hypertension_treated'];
		$data['h_diabetes']=(int)$array['h_diabetes'];
		if($data['h_diabetes']){
			$data['hd_DM']=floatval(implode('.', $array['hd_DM']));
			$data['hd_DM_unknown']=(int)$array['hd_DM_unknown'];
			$data['diet_only']=(int)$array['diet_only'];
			$data['antidiabetic']=mysql_escape_string($array['antidiabetic']);
		}else{
			$data['hd_DM']='';
			$data['hd_DM_unknown']='';
			$data['diet_only']='';
			$data['antidiabetic']='';
		}
		$data['smoker']=mysql_escape_string($array['smoker']);
		$data['family_history']=mysql_escape_string($array['family_history']);
		$data['known_CAD']=(int)$array['known_CAD'];
		$data['stable_angina']=(int)$array['stable_angina'];
		if($data['stable_angina']){
			$data['ex_study']=(int)$array['ex_study'];
			$data['stenosis']=(int)$array['stenosis'];
		}else{
			$data['ex_study']='';
			$data['stenosis']='';
		}
		$data['previous_MI']=(int)$array['previous_MI'];
		$data['previous_PCI']=(int)$array['previous_PCI'];
		$data['previous_CABG']=(int)$array['previous_CABG'];
		$data['fibrillation']=(int)$array['fibrillation'];
		$data['PHFailure']=(int)$array['PHFailure'];
		$data['COPD']=(int)$array['COPD'];
		$data['OSApnea']=(int)$array['OSApnea'];
		$data['PA_disease']=(int)$array['PA_disease'];
		$data['malignancy']=(int)$array['malignancy'];
		$data['C_accident']=(int)$array['C_accident'];
		$data['C_accident_1']=mysql_escape_string($array['C_accident_1']);
		$data['C_accident_2']=mysql_escape_string($array['C_accident_2']);
		$data['patient_id']=$array['patient_id'];
		$data['user_id']=$array['user_id'];

		$this->editData($data, $id);
	}*/
	public function getRisk($patient_id){
		global $webdb;
		$sql="select * from ".$this->tableName." where patient_id='".(int)$patient_id."'";
		return $webdb->getValue($sql);
	}
}
?>