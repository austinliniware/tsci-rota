<?php
date_default_timezone_set('Asia/Taipei');
if ((int)$_GET['item']) $item=(int)$_GET['item'];else $item=1;
define('ROOT_PATH',dirname(dirname(__FILE__)).'/');
include_once(ROOT_PATH.'WebAdmin/common.inc.php');

function key_search($value,$array) {
$num=array_search($value,$array);
if ($num) return $num; else return "";
}

$medication_category = $webdb->getList("select * from _web_medication_category where parent_id=0 and is_show=1 order by sort");
foreach ($medication_category as $rs) {
$medication_ary[$rs['id']]=$rs['name'];
$sub_medication = $webdb->getList("select * from _web_medication_category where parent_id=".$rs["id"]." and is_show=1 order by sort");
foreach ($sub_medication as $sub) {
$submedication_ary[$rs['id']][$sub['id']]=$sub['name'];
}
}

$follow_medication_category = $webdb->getList("select * from _web_follow_medication_category where parent_id=0 and is_show=1 order by sort");
foreach ($follow_medication_category as $rs) {
$follow_medication_ary[$rs['id']]=$rs['name'];
$sub_follow_medication = $webdb->getList("select * from _web_follow_medication_category where parent_id=".$rs["id"]." and is_show=1 order by sort");
foreach ($sub_follow_medication as $sub) {
$follow_submedication_ary[$rs['id']][$sub['id']]=$sub['name'];
}
}
/* ================================================================================================ */
$searchSql="";
/* if($_POST["ntype"]>0) $searchSql.=" and ntype='".$_POST["ntype"]."'";
if($_POST["is_pay"]=="0"||$_POST["is_pay"]=="1") $searchSql.=" and is_pay=".$_POST["is_pay"];
if($_POST["otype"]=="0"||$_POST["otype"]=="1") $searchSql.=" and otype=".$_POST["otype"];
if($_POST["is_deal"]) $searchSql.=" and is_deal='".$_POST["is_deal"]."'";
if($_POST["startdt"]) $searchSql.=" and buy_time > '".$_POST["startdt"]."'";
if($_POST["enddt"]) $searchSql.=" and buy_time < DATE_ADD('".$_POST["enddt"]."', INTERVAL 1 DAY)";
if(trim($_POST['keywords'])!="") $searchSql.=" and (orderNo like '%".$_POST["keywords"]."%' or name like '%".$_POST["keywords"]."%' or re_name like '%".$_POST["keywords"]."%' or re_phone like '%".$_POST["keywords"]."%' or re_addr like '%".$_POST["keywords"]."%')";*/
$list=$webdb->getList("SELECT P1.*,H.`name` AS hospital_name from `_web_patient` as P1 Left Join `_web_hospital` AS H ON P1.hospital_id = H.id where 1".($searchSql?$searchSql:"")." order by id");

/** PHPExcel */
include ROOT_PATH.'include/class/PHPExcel.php';
/** PHPExcel_Writer_Excel2007 */
include ROOT_PATH.'include/class/PHPExcel/Writer/Excel2007.php';
// $cacheMethod = PHPExcel_CachedObjectStorageFactory::cache_to_phpTemp;
// $cacheSettings = array( 'memoryCacheSize' => '300MB');
// PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);
$cacheMethod = PHPExcel_CachedObjectStorageFactory::cache_in_memory_gzip;
if (!PHPExcel_Settings::setCacheStorageMethod($cacheMethod)) {
	die($cacheMethod . " caching method is not available" . EOL);
}

$tag_title = array(1=>"Patient Information","Admission Procedure","Admission Medication","Admission In-Hospital outcome","Follow up Post discharge","6 month Follow up","1 year Follow up","2 year Follow up","End of Registry Form");

$page=0;
$objPHPExcel = new PHPExcel();
$objPHPExcel->getProperties()->setCreator("ACS");

$objPHPExcel->setActiveSheetIndex($page);
// $objPHPExcel->getActiveSheet()->setTitle('Patient Information');
$objPHPExcel->getActiveSheet()->setTitle($tag_title[$item]);
$objPHPExcel->getActiveSheet()->getDefaultColumnDimension()->setWidth(20);
$objPHPExcel->getDefaultStyle()->getFont()->setName('Arial');
$objPHPExcel->getDefaultStyle()->getFont()->setSize(8);
$objPHPExcel->getDefaultStyle()->getAlignment()->setWrapText(true);
$objPHPExcel->getDefaultStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
// 凍結
$objPHPExcel->getActiveSheet()->freezePane('B3');
/* ================================================================================================ */

function showDetail_1($list) {
global $objPHPExcel,$webdb,$medication_ary,$submedication_ary,$follow_medication_ary,$follow_submedication_ary;
include(ROOT_PATH.'conf/common.conf.php');
// 標題
$itemHeader_ary1=array(
 "Demographics"=>
array("Patient No","Hospital","Initial","Admission date","Discharge date","Gender","Date of birth","Ethnicity")
,"Initial Presentation"=>
array("Symptom Onset","Transferred from another hospital","Site Number","Non Study Hospital","Angina Category","Unstable angina","Number of episodes 24hrs before presentation","Killip Class")
,"ED Presentation"=>
array("ED Presentation","Cardiac Arrest","SBP","DBP","Heart rate","First ECG","Interpretation","ST/T Wave Change","Location","Q Waves","Other","Diagnostic ECG","Interpretation","ST/T Wave Change","Location","Q Waves","Other"
,"Cardiac Enzymes Rapid Troponin","Interpretation","Initial CK","Initial CKMB","Initial I or T","Initial Trop","Peak CK","Peak CKMB","Peak I or T","Peak Trop")
,"Admission"=>
array("Symptom Onset","Admission Diagnosis","TIMI risk score","Complications","Arrhythmia Code")
,"Admission Risk stratification"=>
array("Height","Weight","Waist Circumference","Serum Creatinine","Dialysis Dependent","","WBC","Hemoglobin","Glucose Tolerance Test","Result","Sugar AC","2h-PC Sugar","HbA1C"
,"Total Chol","HDL","LDL","Triglyceride","Uric acid","Na","K","Mg","BNP","hs - CRP","NT-proBNP"
,"History of Dyslipidemia","Treated","History of Hypertension","Treated","History of Diabetes","Duration of DM","Diet only","Oral antidiabetic","other","Smoker","Family history","Known CAD","Stable Angina","Ex Test / Functional Study","Angiogram >50% stenosis"
,"Previous MI","Previous PCI","Previous CABG","History of Atrial Fibrillation","Previous Heart Failure","COPD","Obstructive Sleep Apnea","Peripheral arterial disease","Malignancy","Cerebrovascular accident","CVA Type","CVA Timy")
,"Admission Reperfusion"=>
array("Reperfusion Therapy","Fibrinolysis Started","Reperfusion Druge","Fibrinolysis Contraindications","Primary Angioplasty and Stenting","Primary Angioplasty Time","Door to balloon time","Rescue Angioplasty and Stenting","Rescue Angioplasty Time")
);
// $itemHeader_ary=array_merge($itemHeader_ary1, $itemHeader_ary2);
$itemHeader_ary=$itemHeader_ary1;

$x=0;
foreach ($itemHeader_ary as $header => $header_ary) {
foreach ($header_ary as $key => $title) {
if ($key==0) $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, 1, $header); 
$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($x, 2)->getFont()->setBold(true); //設置第一行內容加粗
$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, 2, $title); 
$x++;
}}
// 資料列
$i=3;
foreach($list as $val){
	// P1 Demographics
	$patient_id=$val['id'];
	$x=0;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, $val['id']); $x++;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, $val['hospital_name']); $x++;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, $val['initials']); $x++;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, $val['admission_date']); $x++;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, $val['discharge_date']); $x++;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, key_search($val['gender'],$gender)); $x++;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, $val['birthday']); $x++;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, (array_search($val['ethnicity'],$ethnicity)?array_search($val['ethnicity'],$ethnicity):$val['ethnicity'])); $x++;
	// P2 Initial Presentation
	$val=$webdb->getValue("select * from `_web_initial_presentation` where patient_id=".$patient_id);
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, $val['symptom_onset']); $x++;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, $val['TFAhospital']!=""?$val['TFAhospital']+1:""); $x++;
	// $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, $val['TFAhospital']==1?"Yes":"No"); $x++;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, $val['site_number']); $x++;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, $val['non_study_hospital']); $x++;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, key_search($val['angina_category'],$angina_category)); $x++;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, key_search($val['unstable_angina'],$unstable_angina)); $x++;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, $val['number_of_episodes']); $x++;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, key_search($val['killip_class'],$killip_class)); $x++;
	// P3 ED Presentation
	$val=$webdb->getValue("select * from `_web_ed_presentation` where patient_id=".$patient_id);
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, $val['ED_date']); $x++;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, $val['cardiac_arrest']!=""?$val['cardiac_arrest']+1:""); $x++;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, $val['SBP']); $x++;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, $val['DBP']); $x++;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, $val['heart_rate']); $x++;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, $val['first_ECG']); $x++;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, key_search($val['Fecg'],$Fecg)); $x++;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, key_search($val['F_stt_wave'],$F_stt_wave)); $x++;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, key_search($val['F_location'],$F_location)); $x++;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, key_search($val['F_q_waves'],$F_q_waves)); $x++;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, $val['F_other']); $x++;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, $val['diagnostic_ECG']); $x++;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, key_search($val['Decg'],$Decg)); $x++;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, key_search($val['D_stt_wave'],$F_stt_wave)); $x++;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, key_search($val['D_location'],$F_location)); $x++;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, key_search($val['D_q_waves'],$F_q_waves)); $x++;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, $val['D_other']); $x++;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, $val['cardiacERT_1']); $x++;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, key_search($val['cardiacERT_2'],$cardiacERT_2)); $x++;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, $val['initial_CK']); $x++;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, $val['initial_CKMB']); $x++;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, $val['initial_IT']); $x++;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, $val['initial_Trop']); $x++;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, $val['peak_CK']); $x++;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, $val['peak_CKMB']); $x++;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, $val['peak_IT']); $x++;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, $val['peak_Trop']); $x++;
	// P4 Admission
	$val=$webdb->getValue("select * from `_web_admission` where patient_id=".$patient_id);
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, $val['symptom_onset']); $x++;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, key_search($val['admission_diagnosis'],$admission_diagnosis)); $x++;
	if ($val['admission_diagnosis']=='ST Elevation-Myocardial Infarction') {
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, ''); $x++;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, key_search($val['ST_complications'],$Complications)); $x++;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, $val['STACode']); $x++; }
	else if ($val['admission_diagnosis']=='Non ST elevation myocardial infarction') {
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, $val['TIMI_risk_score']); $x++;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, key_search($val['non_ST_complications'],$Complications)); $x++;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, $val['non_STACode']); $x++; }
	else {
	$x+=3;
	}
	// P5 Admission Risk stratification
	$val=$webdb->getValue("select * from `_web_risk_stratification` where patient_id=".$patient_id);
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, $val['height']); $x++;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, $val['weight']); $x++;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, $val['waist_circumference']); $x++;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, $val['serum_creatinine']); $x++;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, $val['dialysis_dependent']!=""?$val['dialysis_dependent']+1:""); $x++;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, key_search($val['dialysis_dependent_yes'],$dialysis_dependent_yes)); $x++;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, $val['WBC']); $x++;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, $val['hemoglobin']); $x++;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, $val['glucoseTT']!=""?$val['glucoseTT']+1:""); $x++;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, key_search($val['glucoseTT_yes'],$glucoseTT_yes)); $x++;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, $val['sugar_AC']); $x++;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, $val['2h_PC_sugar']); $x++;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, $val['HbA1C']); $x++;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, $val['total_chol']); $x++;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, $val['HDL']); $x++;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, $val['LDL']); $x++;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, $val['triglyceride']); $x++;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, $val['uric_acid']); $x++;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, $val['Na']); $x++;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, $val['K']); $x++;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, $val['Mg']); $x++;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, $val['BNP']); $x++;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, $val['hs_CRP']); $x++;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, $val['NT-proBNP']); $x++;
	
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, $val['h_dyslipidemia']!=""?$val['h_dyslipidemia']+1:""); $x++;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, $val['h_dyslipidemia_treated']!=""?$val['h_dyslipidemia_treated']+1:""); $x++;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, $val['h_hypertension']!=""?$val['h_hypertension']+1:""); $x++;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, $val['h_hypertension_treated']!=""?$val['h_hypertension_treated']+1:""); $x++;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, $val['h_diabetes']!=""?$val['h_diabetes']+1:""); $x++;
	if ($val['h_diabetes']==1) {
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, ($val['hd_DM_unknown']?'unknown':$val['hd_DM'])); $x++;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, $val['diet_only']!=""?$val['diet_only']+1:""); $x++;
	// $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, key_search($val['antidiabetic'],$antidiabetic)); $x++;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, $val['antidiabetic']); $x++;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, $val['antidiabetic_other']); $x++;
	} else $x+=4;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, key_search($val['smoker'],$smoker)); $x++;
	// $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, $val['smoker']); $x++;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, key_search($val['family_history'],$family_history)); $x++;
	// $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, $val['family_history']); $x++;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, $val['known_CAD']!=""?$val['known_CAD']+1:""); $x++;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, $val['stable_angina']!=""?$val['stable_angina']+1:""); $x++;
	// $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, $val['known_CAD']==1?'Yes':'No'); $x++;
	// $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, $val['stable_angina']==1?'Yes':'No'); $x++;
	if ($val['stable_angina']==1) {
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, $val['ex_study']!=""?$val['ex_study']+1:""); $x++;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, $val['stenosis']!=""?$val['stenosis']+1:""); $x++;
	// $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, $val['ex_study']==1?'Yes':'No'); $x++;
	// $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, $val['stenosis']==1?'Yes':'No'); $x++;
	} else $x+=2;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, $val['previous_MI']!=""?$val['previous_MI']+1:""); $x++;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, $val['previous_PCI']!=""?$val['previous_PCI']+1:""); $x++;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, $val['previous_CABG']!=""?$val['previous_CABG']+1:""); $x++;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, $val['fibrillation']!=""?$val['fibrillation']+1:""); $x++;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, $val['PHFailure']!=""?$val['PHFailure']+1:""); $x++;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, $val['COPD']!=""?$val['COPD']+1:""); $x++;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, $val['OSApnea']!=""?$val['OSApnea']+1:""); $x++;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, $val['PA_disease']!=""?$val['PA_disease']+1:""); $x++;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, $val['malignancy']!=""?$val['malignancy']+1:""); $x++;
	// $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, $val['previous_MI']==1?'Yes':'No'); $x++;
	// $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, $val['previous_PCI']==1?'Yes':'No'); $x++;
	// $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, $val['previous_CABG']==1?'Yes':'No'); $x++;
	// $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, $val['fibrillation']==1?'Yes':'No'); $x++;
	// $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, $val['PHFailure']==1?'Yes':'No'); $x++;
	// $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, $val['COPD']==1?'Yes':'No'); $x++;
	// $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, $val['OSApnea']==1?'Yes':'No'); $x++;
	// $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, $val['PA_disease']==1?'Yes':'No'); $x++;
	// $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, $val['malignancy']==1?'Yes':'No'); $x++;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, $val['C_accident']!=""?$val['C_accident']+1:""); $x++;
	// $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, $val['C_accident']==1?'Yes':'No'); $x++;
	if ($val['C_accident']==1) {
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, key_search($val['C_accident_1'],$C_accident_1)); $x++;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, key_search($val['C_accident_2'],$C_accident_2)); $x++;
	// $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, $val['C_accident_1']); $x++;
	// $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, $val['C_accident_2']); $x++;
	} else $x+=2;
	// P6 Admission Reperfusion
	$val=$webdb->getValue("select * from `_web_reperfusion` where patient_id=".$patient_id);
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, $val['therapy']!=""?$val['therapy']+1:""); $x++;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, $val['fibrinolysis_started']); $x++;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, key_search($val['fibrinolysis'],$fibrinolysis)); $x++;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, $val['fibrinolysis_code']); $x++;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, $val['angioplasty_stenting']!=""?$val['angioplasty_stenting']+1:""); $x++;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, $val['angioplasty_s_yes']); $x++;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, $val['door_balloon_time']); $x++;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, $val['rescue_angioplasty']!=""?$val['rescue_angioplasty']+1:""); $x++;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, $val['rescue_angioplasty_time']); $x++;

$i++;
}
	return $objPHPExcel;
}

$itemHeader_ary0=array(""=>array("Patient No","Hospital","Initial"));
// P7
function showDetail_2($list) {
global $objPHPExcel,$webdb,$itemHeader_ary0,$medication_ary,$submedication_ary,$follow_medication_ary,$follow_submedication_ary;
include(ROOT_PATH.'conf/common.conf.php');
// $page++;
// $objPHPExcel->createSheet();
// $objPHPExcel->setActiveSheetIndex($page);
// $objPHPExcel->getActiveSheet()->setTitle('Admission Procedure');
// $objPHPExcel->getActiveSheet()->getDefaultColumnDimension()->setWidth(20);
// $objPHPExcel->getDefaultStyle()->getFont()->setName('Arial');
// $objPHPExcel->getDefaultStyle()->getFont()->setSize(8);
// $objPHPExcel->getDefaultStyle()->getAlignment()->setWrapText(true);
// $objPHPExcel->getDefaultStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
// $objPHPExcel->getActiveSheet()->freezePane('B3');
// 標題
$itemHeader_ary2=array(
 "Admission Procedure"=>
array("In-hospital Procedure Transfer","Diagnostic Cardiac Angiography","Diagnostic Cardiac Angiography Date","Coronaries Stenosis in Territories","Culprit Lesion Stenosis","Culprit Artery Flow","Culprit Artery Territory","Intra Aortic Balloon Pump"
,"Echocardiography","Ejection Fraction","Valvular heart disease","Percutaneous Cardiac Intervention (PCI) Status","I.Percutaneous Cardiac Intervention (PCI) Date","I.Stent Type","I.No. of stent","I.Lesions successfully Treated","II.Percutaneous Cardiac Intervention (PCI) Date","II.Stent Type","II.No. of stent","II.Lesions successfully Treated"
,"Coronary Artery Bypass Grafting (CABG) Status","Coronary Artery Bypass Grafting (CABG) Time","Pacemaker","Pacemaker Time","ICD","ICD Time","Biventricular Pacing","Biventricular Pacing Date","Functional Stress Testing","Functional Stress Testing Date","Inspection methods","Result")
);
$itemHeader_ary=array_merge($itemHeader_ary0, $itemHeader_ary2);
$x=0;
foreach ($itemHeader_ary as $header => $header_ary) {
foreach ($header_ary as $key => $title) {
if ($key==0) $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, 1, $header); 
$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($x, 2)->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, 2, $title); 
$x++;
}}
// 資料列
$i=3;
foreach($list as $val){
	$patient_id=$val['id'];
	$x=0;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, $val['id']); $x++;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, $val['hospital_name']); $x++;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, $val['initials']); $x++;
	// P7 Admission Procedure
	$val=$webdb->getValue("select * from `_web_procedure` where patient_id=".$patient_id);
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, $val['transfer']!=""?$val['transfer']+1:""); $x++;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, $val['angiography']!=""?$val['angiography']+1:""); $x++;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, $val['angiography_date']); $x++;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, $val['territories']); $x++;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, $val['stenosis']); $x++;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, key_search($val['artery_flow'],$artery_flow)); $x++;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, key_search($val['artery_territory'],$artery_territory)); $x++;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, $val['balloon_pump']!=""?$val['balloon_pump']+1:""); $x++;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, $val['performed']!=""?$val['performed']+1:""); $x++;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, key_search($val['incl_TOE__TTE'],$incl_TOE__TTE)); $x++;
	if (trim($val['fraction'])!="") {
	$fraction_txt=array();
	$fraction_ary=explode(",",$val['fraction']);
	foreach ($fraction as $k=>$fa) {
	if (in_array($fa, $fraction_ary)) $fraction_txt[]=$fa.": ".$val['fraction_'.($k+1)];
	}
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, implode("\r\n",$fraction_txt)); $x++;
	}
	else $x++;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, $val['intervention_status']!=""?$val['intervention_status']+1:""); $x++;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, $val['intervention_date']); $x++;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, key_search($val['stent_type'],$stent_type)); $x++;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, $val['os_stent']); $x++;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, $val['ls_treated']!=""?$val['ls_treated']+1:""); $x++;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, $val['intervention_date2']); $x++;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, key_search($val['stent_type2'],$stent_type)); $x++;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, $val['os_stent2']); $x++;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, $val['ls_treated2']!=""?$val['ls_treated2']+1:""); $x++;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, $val['CABG']!=""?$val['CABG']+1:""); $x++;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, $val['CABG_date']); $x++;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, $val['pacemaker']!=""?$val['pacemaker']+1:""); $x++;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, $val['pacemaker_date']); $x++;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, $val['ICD']!=""?$val['ICD']+1:""); $x++;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, $val['ICD_date']); $x++;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, $val['pacing']!=""?$val['pacing']+1:""); $x++;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, $val['pacing_date']); $x++;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, $val['functional_stress']!=""?$val['functional_stress']+1:""); $x++;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, $val['functional_stress_date']); $x++;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, key_search($val['functional_stress_1'],$functional_stress_1)); $x++;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, key_search($val['functional_stress_result'],$functional_stress_result)); $x++;

$i++;
}
	return $objPHPExcel;
}

// P8
function showDetail_3($list) {
global $objPHPExcel,$webdb,$itemHeader_ary0,$medication_ary,$submedication_ary,$follow_medication_ary,$follow_submedication_ary;
include(ROOT_PATH.'conf/common.conf.php');
// $page++;
// $objPHPExcel->createSheet();
// $objPHPExcel->setActiveSheetIndex($page);
// $objPHPExcel->getActiveSheet()->setTitle('Admission Medication');
// $objPHPExcel->getActiveSheet()->getDefaultColumnDimension()->setWidth(20);
// $objPHPExcel->getDefaultStyle()->getFont()->setName('Arial');
// $objPHPExcel->getDefaultStyle()->getFont()->setSize(8);
// $objPHPExcel->getDefaultStyle()->getAlignment()->setWrapText(true);
// $objPHPExcel->getDefaultStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
// $objPHPExcel->getActiveSheet()->freezePane('B3');
// 標題
unset($itemHeader_ary2);
foreach ($medication_ary as $mname) {
$itemHeader_ary2["Admission Medication"][]=$mname;
$itemHeader_ary2["Admission Medication"][]="Dose";
$itemHeader_ary2["Admission Medication"][]="Sub";
}
$itemHeader_ary=array_merge($itemHeader_ary0, $itemHeader_ary2);
$x=0;
foreach ($itemHeader_ary as $header => $header_ary) {
foreach ($header_ary as $key => $title) {
if ($key==0) $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, 1, $header); 
$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($x, 2)->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, 2, $title); 
$x++;
}}
// 資料列
$i=3;
foreach($list as $val){
	$patient_id=$val['id'];
	$x=0;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, $val['id']); $x++;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, $val['hospital_name']); $x++;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, $val['initials']); $x++;
	// P8 Admission Medication
	$val=$webdb->getValue("select * from `_web_medication` where patient_id=".$patient_id);
	$data=array();
	$medication_txt=array();
	$data['medication']=unserialize($val['medication_id']);
	$data['medication_txt']=unserialize($val['medication_txt']);
	$data['sub_medication']=unserialize($val['sub_medication_id']);
	foreach ($medication_ary as $mid => $mname) {
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, $data['medication'][$mid]!=""?$data['medication'][$mid]+1:""); $x++;
	// $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, $data['medication'][$mid]==1?'Yes':'No'); $x++;
	if ($data['medication'][$mid]==1) {
	$txt_sub=array();
	// $txt=$mname.": ".$data['medication_txt'][$mid];
	$txt=$data['medication_txt'][$mid];
	if(is_array($data['sub_medication'][$mid])) {
	foreach ($submedication_ary[$mid] as $smid => $smname) {
	if(in_array($smid,$data['sub_medication'][$mid])) $txt_sub[]=$smname;
	}
	}
	// $medication_txt[]=$txt.(is_array($txt_sub)?"; ".join(",",$txt_sub):"");
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, $txt); $x++;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, (count($txt_sub)>0?join("\r\n",$txt_sub):"")); $x++;
	} else $x+=2;
	}

$i++;
}
	return $objPHPExcel;
}

// P9
function showDetail_4($list) {
global $objPHPExcel,$webdb,$itemHeader_ary0,$medication_ary,$submedication_ary,$follow_medication_ary,$follow_submedication_ary;
include(ROOT_PATH.'conf/common.conf.php');
// $page++;
// $objPHPExcel->createSheet();
// $objPHPExcel->setActiveSheetIndex($page);
// $objPHPExcel->getActiveSheet()->setTitle('Admission In-Hospital outcome');
// $objPHPExcel->getActiveSheet()->getDefaultColumnDimension()->setWidth(20);
// $objPHPExcel->getDefaultStyle()->getFont()->setName('Arial');
// $objPHPExcel->getDefaultStyle()->getFont()->setSize(8);
// $objPHPExcel->getDefaultStyle()->getAlignment()->setWrapText(true);
// $objPHPExcel->getDefaultStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
// $objPHPExcel->getActiveSheet()->freezePane('B3');
// 標題
$itemHeader_ary2=array(
 "Admission In-Hospital outcome"=>
array("Re MI","TIMI Major/Minor Bleed","Type","Site","Blood transfusion","PRBC","FFP","Platelet","Unplanned Revasc","Stroke","","New Onset Cardiogenic Shock","New Onset Ventricular Arrhythmia","","","New Onset AF","Acute Renal Failure","Discharge Diagnosis","Other","Discharge date time")
);
$itemHeader_ary=array_merge($itemHeader_ary0, $itemHeader_ary2);
$x=0;
foreach ($itemHeader_ary as $header => $header_ary) {
foreach ($header_ary as $key => $title) {
if ($key==0) $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, 1, $header); 
$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($x, 2)->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, 2, $title); 
$x++;
}}
// 資料列
$i=3;
foreach($list as $val){
	$patient_id=$val['id'];
	$x=0;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, $val['id']); $x++;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, $val['hospital_name']); $x++;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, $val['initials']); $x++;
	// P9 Admission In-Hospital outcome
	$val=$webdb->getValue("select * from `_web_outcome` where patient_id=".$patient_id);
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, $val['re_MI']!=""?$val['re_MI']+1:""); $x++;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, $val['minor_bleed']!=""?$val['minor_bleed']+1:""); $x++;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, key_search($val['minor_bleed_yes'],$minor_bleed_yes)); $x++;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, $val['minor_bleed_site']); $x++;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, $val['transfusion']!=""?$val['transfusion']+1:""); $x++;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, $val['transfusion_PRBC']); $x++;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, $val['transfusion_FFP']); $x++;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, $val['transfusion_platelet']); $x++;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, $val['revasc']!=""?$val['revasc']+1:""); $x++;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, $val['stroke']!=""?$val['stroke']+1:""); $x++;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, key_search($val['stroke_yes'],$stroke_yes)); $x++;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, $val['shock']!=""?$val['shock']+1:""); $x++;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, $val['arrhythmia']!=""?$val['arrhythmia']+1:""); $x++;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, key_search($val['arrhythmia_yes'],$arrhythmia_yes)); $x++;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, key_search($val['arrhythmia_anytime'],$arrhythmia_anytime)); $x++;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, $val['onset_AF']!=""?$val['onset_AF']+1:""); $x++;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, $val['failure']!=""?$val['failure']+1:""); $x++;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, key_search($val['diagnosis'],$diagnosis)); $x++;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, $val['diagnosis_other']); $x++;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, $val['discharge_date']); $x++;
	/*
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, $val['']); $x++;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, $val['']); $x++;
	*/

$i++;
}
	return $objPHPExcel;
}

// T1
function showDetail_5($list) {
global $objPHPExcel,$webdb,$itemHeader_ary0,$medication_ary,$submedication_ary,$follow_medication_ary,$follow_submedication_ary;
include(ROOT_PATH.'conf/common.conf.php');
// $page++;
// $objPHPExcel->createSheet();
// $objPHPExcel->setActiveSheetIndex($page);
// $objPHPExcel->getActiveSheet()->setTitle('Follow up Post discharge');
// $objPHPExcel->getActiveSheet()->getDefaultColumnDimension()->setWidth(20);
// $objPHPExcel->getDefaultStyle()->getFont()->setName('Arial');
// $objPHPExcel->getDefaultStyle()->getFont()->setSize(8);
// $objPHPExcel->getDefaultStyle()->getAlignment()->setWrapText(true);
// $objPHPExcel->getDefaultStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
// $objPHPExcel->getActiveSheet()->freezePane('B3');
// 標題
$itemHeader_ary2=array(
 "Follow up Post discharge"=>
array("Re-hospitalization","","Reason","Start","End","Repeat Revascularization","Repeat Revascularization Date","PCI","PCI Type","PCI Date","CABG","CABG Date","Biventricular Pacing","Biventricular Pacing Date","ICD","ICD Date","Hb","Sugar AC","2h PC Sugar","HbA1C","BUN","Serum Creatinine","Total cholesterol","HDL","LDL","Triglyceride","Uric acid","Hypoglycemia","sugar level","")
);
foreach ($follow_medication_ary as $mname) {
$itemHeader_ary2["Follow up Post discharge Medication"][]=$mname;
$itemHeader_ary2["Follow up Post discharge Medication"][]="Dose";
$itemHeader_ary2["Follow up Post discharge Medication"][]="Sub";
}
$itemHeader_ary=array_merge($itemHeader_ary0, $itemHeader_ary2);
$x=0;
foreach ($itemHeader_ary as $header => $header_ary) {
foreach ($header_ary as $key => $title) {
if ($key==0) $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, 1, $header); 
$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($x, 2)->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, 2, $title); 
$x++;
}}
// 資料列
$i=3;
foreach($list as $val){
	$patient_id=$val['id'];
	$x=0;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, $val['id']); $x++;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, $val['hospital_name']); $x++;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, $val['initials']); $x++;
	// T1 Follow up Post discharge
	$val=$webdb->getValue("select * from `_web_follow_up_discharge` where patient_id=".$patient_id);
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, $val['re_hospitalization']!=""?$val['re_hospitalization']+1:""); $x++;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, key_search($val['re_hospitalization_yes'],$re_hospitalization_yes)); $x++;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, key_search($val['re_hospitalization_reason'],$re_hospitalization_reason)); $x++;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, $val['re_hospitalization_start']); $x++;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, $val['re_hospitalization_end']); $x++;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, $val['revascularization']!=""?$val['revascularization']+1:""); $x++;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, $val['revascularization_yes']); $x++;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, $val['PCI']!=""?$val['PCI']+1:""); $x++;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, $val['PCI_yes_date']); $x++;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, $val['PCI_yes']); $x++;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, $val['CABG']!=""?$val['CABG']+1:""); $x++;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, $val['CABG_yes']); $x++;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, $val['pacing']!=""?$val['pacing']+1:""); $x++;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, $val['pacing_yes']); $x++;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, $val['ICD']!=""?$val['ICD']+1:""); $x++;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, $val['ICD_yes']); $x++;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, $val['Hb']); $x++;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, $val['sugar_AC']); $x++;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, $val['2h_PC_sugar']); $x++;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, $val['HbA1C']); $x++;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, $val['BUN']); $x++;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, $val['serum_creatinine']); $x++;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, $val['cholesterol']); $x++;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, $val['cholesterol_HDL']); $x++;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, $val['cholesterol_LDL']); $x++;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, $val['triglyceride']); $x++;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, $val['uric_acid']); $x++;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, $val['hypoglycemia']!=""?$val['hypoglycemia']+1:""); $x++;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, $val['hypoglycemia_sugar_level']); $x++;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, $val['hypoglycemia_yes']); $x++;
	$data=array();
	$medication_txt=array();
	$data['medication']=unserialize($val['medication_id']);
	$data['medication_txt']=unserialize($val['medication_txt']);
	$data['sub_medication']=unserialize($val['sub_medication_id']);
	foreach ($follow_medication_ary as $mid => $mname) {
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, $data['medication'][$mid]!=""?$data['medication'][$mid]+1:""); $x++;
	if ($data['medication'][$mid]==1) {
	$txt_sub=array();
	$txt=$data['medication_txt'][$mid];
	if(is_array($data['sub_medication'][$mid])) {
	foreach ($follow_submedication_ary[$mid] as $smid => $smname) {
	if(in_array($smid,$data['sub_medication'][$mid])) $txt_sub[]=$smname;
	}
	}
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, $txt); $x++;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, (count($txt_sub)>0?join("\r\n",$txt_sub):"")); $x++;
	} else $x+=2;
	}

$i++;
}
	return $objPHPExcel;
}

// T2~T4 month Follow up
function showDetail_6($list,$n=1) {
global $objPHPExcel,$webdb,$itemHeader_ary0,$medication_ary,$submedication_ary,$follow_medication_ary,$follow_submedication_ary;
include(ROOT_PATH.'conf/common.conf.php');
$subTitle=array(1=>"6 month Follow up","1 year Follow up","2 year Follow up");
// for ($n=1;$n<=3;$n++) {
// $page++;
// $objPHPExcel->createSheet();
// $objPHPExcel->setActiveSheetIndex($page);
// $objPHPExcel->getActiveSheet()->setTitle($subTitle[$n]);
// $objPHPExcel->getActiveSheet()->getDefaultColumnDimension()->setWidth(20);
// $objPHPExcel->getDefaultStyle()->getFont()->setName('Arial');
// $objPHPExcel->getDefaultStyle()->getFont()->setSize(8);
// $objPHPExcel->getDefaultStyle()->getAlignment()->setWrapText(true);
// $objPHPExcel->getDefaultStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
// $objPHPExcel->getActiveSheet()->freezePane('B3');
// 標題
$itemHeader_ary2=array(
 $subTitle[$n]=>
array("Vital Status","Reason","Re-hospitalization","","Reason","Start","End","Repeat Revascularization","","PCI","","","CABG","","Biventricular Pacing","","ICD","","Hb","Sugar AC","2h PC Sugar","HbA1C","BUN","Serum Creatinine","Total cholesterol","HDL","LDL","Triglyceride","Uric acid","Hypoglycemia","sugar level","")
);
foreach ($follow_medication_ary as $mname) {
$itemHeader_ary2[$subTitle[$n]." Medication"][]=$mname;
$itemHeader_ary2[$subTitle[$n]." Medication"][]="Dose";
$itemHeader_ary2[$subTitle[$n]." Medication"][]="Sub";
}
$itemHeader_ary=array_merge($itemHeader_ary0, $itemHeader_ary2);
$x=0;
foreach ($itemHeader_ary as $header => $header_ary) {
foreach ($header_ary as $key => $title) {
if ($key==0) $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, 1, $header); 
$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($x, 2)->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, 2, $title); 
$x++;
}}
// 資料列
$i=3;
foreach($list as $val){
	$patient_id=$val['id'];
	$x=0;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, $val['id']); $x++;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, $val['hospital_name']); $x++;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, $val['initials']); $x++;
	$val=$webdb->getValue("select * from `_web_month_follow_up` where ntype=".$n." and patient_id=".$patient_id);
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, $val['vital_status']); $x++;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, $val['dead_reason']); $x++;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, $val['re_hospitalization']!=""?$val['re_hospitalization']+1:""); $x++;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, key_search($val['re_hospitalization_yes'],$re_hospitalization_yes)); $x++;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, key_search($val['re_hospitalization_reason'],$re_hospitalization_reason)); $x++;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, $val['re_hospitalization_start']); $x++;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, $val['re_hospitalization_end']); $x++;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, $val['revascularization']!=""?$val['revascularization']+1:""); $x++;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, $val['revascularization_yes']); $x++;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, $val['PCI']!=""?$val['PCI']+1:""); $x++;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, $val['PCI_yes_date']); $x++;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, $val['PCI_yes']); $x++;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, $val['CABG']!=""?$val['CABG']+1:""); $x++;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, $val['CABG_yes']); $x++;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, $val['pacing']!=""?$val['pacing']+1:""); $x++;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, $val['pacing_yes']); $x++;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, $val['ICD']!=""?$val['ICD']+1:""); $x++;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, $val['ICD_yes']); $x++;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, $val['Hb']); $x++;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, $val['sugar_AC']); $x++;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, $val['2h_PC_sugar']); $x++;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, $val['HbA1C']); $x++;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, $val['BUN']); $x++;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, $val['serum_creatinine']); $x++;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, $val['cholesterol']); $x++;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, $val['cholesterol_HDL']); $x++;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, $val['cholesterol_LDL']); $x++;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, $val['triglyceride']); $x++;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, $val['uric_acid']); $x++;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, $val['hypoglycemia']!=""?$val['hypoglycemia']+1:""); $x++;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, $val['hypoglycemia_sugar_level']); $x++;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, $val['hypoglycemia_yes']); $x++;
	$data=array();
	$medication_txt=array();
	$data['medication']=unserialize($val['medication_id']);
	$data['medication_txt']=unserialize($val['medication_txt']);
	$data['sub_medication']=unserialize($val['sub_medication_id']);
	foreach ($follow_medication_ary as $mid => $mname) {
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, $data['medication'][$mid]!=""?$data['medication'][$mid]+1:""); $x++;
	if ($data['medication'][$mid]==1) {
	$txt_sub=array();
	$txt=$data['medication_txt'][$mid];
	if(is_array($data['sub_medication'][$mid])) {
	foreach ($follow_submedication_ary[$mid] as $smid => $smname) {
	if(in_array($smid,$data['sub_medication'][$mid])) $txt_sub[]=$smname;
	}
	}
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, $txt); $x++;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, (count($txt_sub)>0?join("\r\n",$txt_sub):"")); $x++;
	} else $x+=2;
	}

$i++;
}
	return $objPHPExcel;
// }
}

function showDetail_7($list) {
	$objPHPExcel=showDetail_6($list,2);
	return $objPHPExcel;
}

function showDetail_8($list) {
	$objPHPExcel=showDetail_6($list,3);
	return $objPHPExcel;
}
// T5
function showDetail_9($list) {
global $objPHPExcel,$webdb,$itemHeader_ary0,$medication_ary,$submedication_ary,$follow_medication_ary,$follow_submedication_ary;
include(ROOT_PATH.'conf/common.conf.php');
// $page++;
// $objPHPExcel->createSheet();
// $objPHPExcel->setActiveSheetIndex($page);
// $objPHPExcel->getActiveSheet()->setTitle('End of Registry Form');
// $objPHPExcel->getActiveSheet()->getDefaultColumnDimension()->setWidth(20);
// $objPHPExcel->getDefaultStyle()->getFont()->setName('Arial');
// $objPHPExcel->getDefaultStyle()->getFont()->setSize(8);
// $objPHPExcel->getDefaultStyle()->getAlignment()->setWrapText(true);
// $objPHPExcel->getDefaultStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
// $objPHPExcel->getActiveSheet()->freezePane('B3');
// 標題
$itemHeader_ary2=array(
 "End of Registry Form"=>
array("Date the patient was taken off the study","Did the subject complete the study","If no, please give primary reason","Date of death","Other reason")
);
$itemHeader_ary=array_merge($itemHeader_ary0, $itemHeader_ary2);
$x=0;
foreach ($itemHeader_ary as $header => $header_ary) {
foreach ($header_ary as $key => $title) {
if ($key==0) $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, 1, $header); 
$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($x, 2)->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, 2, $title); 
$x++;
}}
// 資料列
$i=3;
foreach($list as $val){
	$patient_id=$val['id'];
	$x=0;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, $val['id']); $x++;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, $val['hospital_name']); $x++;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, $val['initials']); $x++;
	$val=$webdb->getValue("select * from `_web_end_registry_form` where patient_id=".$patient_id);
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, $val['date_study']); $x++;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, $val['complete_study']!=""?$val['complete_study']+1:""); $x++;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, key_search($val['primary_reason'],$primary_reason)); $x++;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, $val['date_death']); $x++;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($x, $i, $val['other_reason']); $x++;

$i++;
}
	return $objPHPExcel;
}


function showDetail($item) {
	global $objPHPExcel,$list;
	switch ($item) {
	case 1: $objPHPExcel=showDetail_1($list); break;
	case 2: $objPHPExcel=showDetail_2($list); break;
	case 3: $objPHPExcel=showDetail_3($list); break;
	case 4: $objPHPExcel=showDetail_4($list); break;
	case 5: $objPHPExcel=showDetail_5($list); break;
	case 6: $objPHPExcel=showDetail_6($list); break;
	case 7: $objPHPExcel=showDetail_7($list); break;
	case 8: $objPHPExcel=showDetail_8($list); break;
	case 9: $objPHPExcel=showDetail_9($list); break;
	}
	return $objPHPExcel;
}
$objPHPExcel=showDetail($item);

// $objPHPExcel->setActiveSheetIndex(0);
/* redirect output to a client web browser (Excel2007) */
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="Export_Patient_'.$item.'.xlsx"');
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
// $objWriter->setUseDiskCaching(true, '../../wimage');
$objWriter->save('php://output');
$objPHPExcel->disconnectWorksheets(); 
exit;
?>