<?php
	$post=array();
	$post['vital_status']=check_input($_POST['vital_status']);
	$post['dead_reason']=check_input($_POST['dead_reason']);
	$post['re_hospitalization']=check_number($_POST['re_hospitalization']);
	$post['re_hospitalization_yes']=check_input($_POST['re_hospitalization_yes']);
	$post['re_hospitalization_reason']=check_input($_POST['re_hospitalization_reason']);
	$post['re_hospitalization_start']=(trim($_POST['re_hospitalization_start'])!=""?date("Y-m-d",strtotime($_POST['re_hospitalization_start'])):"");
	$post['re_hospitalization_end']=(trim($_POST['re_hospitalization_end'])!=""?date("Y-m-d",strtotime($_POST['re_hospitalization_end'])):"");
	$post['revascularization']=check_number($_POST['revascularization']);
	$post['revascularization_yes']=(trim($_POST['revascularization_yes'])!=""?date("Y-m-d",strtotime($_POST['revascularization_yes'])):"");
	$post['PCI']=check_number($_POST['PCI']);
	$post['PCI_yes_date']=(trim($_POST['PCI_yes_date'])!=""?date("Y-m-d",strtotime($_POST['PCI_yes_date'])):"");
	$post['PCI_yes']=check_input($_POST['PCI_yes']);
	$post['CABG']=check_number($_POST['CABG']);
	$post['CABG_yes']=(trim($_POST['CABG_yes'])!=""?date("Y-m-d",strtotime($_POST['CABG_yes'])):"");
	$post['pacing']=check_number($_POST['pacing']);
	$post['pacing_yes']=(trim($_POST['pacing_yes'])!=""?date("Y-m-d",strtotime($_POST['pacing_yes'])):"");
	$post['ICD']=check_number($_POST['ICD']);
	$post['ICD_yes']=(trim($_POST['ICD_yes'])!=""?date("Y-m-d",strtotime($_POST['ICD_yes'])):"");
	$post['Hb']=(is_array($_POST['Hb'])?(implode(".", $_POST['Hb'])!="."?check_number(implode('.', $_POST['Hb']),1):""):"");
	$post['Hb_not_done']=check_number($_POST['Hb_not_done']);
	$post['sugar_AC']=check_number($_POST['sugar_AC'],1);
	$post['2h_PC_sugar']=check_number($_POST['2h_PC_sugar'],1);
	$post['HbA1C']=check_number($_POST['HbA1C'],1);
	$post['BUN']=check_number($_POST['BUN'],1);
	$post['serum_creatinine']=(is_array($_POST['serum_creatinine'])?(implode(".", $_POST['serum_creatinine'])!="."?check_number(implode('.', $_POST['serum_creatinine']),1):""):"");
	$post['BUN_not_done']=check_number($_POST['BUN_not_done']);
	$post['cholesterol']=check_number($_POST['cholesterol'],1);
	$post['cholesterol_HDL']=check_number($_POST['cholesterol_HDL'],1);
	$post['cholesterol_LDL']=check_number($_POST['cholesterol_LDL'],1);
	$post['triglyceride']=check_number($_POST['triglyceride'],1);
	$post['triglyceride_not_done']=check_number($_POST['triglyceride_not_done']);
	$post['uric_acid']=(is_array($_POST['uric_acid'])?(implode(".", $_POST['uric_acid'])!="."?check_number(implode('.', $_POST['uric_acid']),1):""):"");
	$post['uric_acid_not_done']=check_number($_POST['uric_acid_not_done']);
	$post['hypoglycemia']=check_number($_POST['hypoglycemia']);
	$post['hypoglycemia_sugar_level']=check_number($_POST['hypoglycemia_sugar_level'],1);
	$post['hypoglycemia_yes']=check_input($_POST['hypoglycemia_yes']);
	
	if (is_array($_POST['medication'])) {
	$post['medication_id']=serialize($_POST['medication']);
	foreach ($_POST['medication'] as $key=>$val) {
	if ($val==1) {
	$post['medication_txt'][$key]=$_POST['medication_txt'][$key];
	$post['sub_medication_id'][$key]=$_POST['sub_medication'][$key];
	}
	}
	$post['medication_txt']=serialize($post['medication_txt']);
	$post['sub_medication_id']=serialize($post['sub_medication_id']);
	}
	
	if ($customerInfo['group_id']==2) {
	if (check_number($_POST['signature'])) {
	$post['signature']=$_SESSION['customer_id'];
	$post['date_added']=(trim($_POST['date_added'])!=""?date("Y-m-d",strtotime($_POST['date_added'])):"");
	} else {
	$post['signature']="";
	$post['date_added']="";
	}
	}
?>