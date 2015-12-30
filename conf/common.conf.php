<?php

$action_pages=array('patient_1','patient_2','patient_3','patient_4','patient_5','patient_6','patient_7','patient_8','patient_9','patient_10','patient_11','patient_12','patient_13','patient_14');
$action_dbs=array('_rota_1patient_source','_ffr_2procedure','_ffr_4firstssla','_ffr_3firstsslb','_ffr_5secssla','_ffr_6secsslb','_ffr_7thirdssla','_ffr_8thirdsslb','_ffr_9biothers','_ffr_10bilesions','_ffr_11crhospital','_ffr_12cropdfu','_ffr_13presswave','end_registry_form');
// P1
//$hospital_id=array(1=>'Previous CAD','Previous MI','Previous ischemic stroke','Previous hemorrhagic stroke','Hypertension','Diabetes','Hyperlipidemia','ESRD');
$a_diagnosis=array(1=>'stable angina','unstable angina','NSTEMI','STEMI');
$medical_history=array(1=>'Hypertension','DM','current smoker','Ex-smoker','CAD','previous MI','previous PCI','previous CABG','PAOD','COPD','chronic heart failure','old CVA','洗腎');
$pre_medications=array(1=>'Dihydropyridine CCB','non-dihydropyridine CCB','ACEI','ARB','Digoxin','α-blocker','β-Blocker','Loop diuretics','Thiazide','Spironolactone','Statin','Warfarin','NOAC','Aspirin','Plavix','Ticagrelor','Cilostazol','Nitrate','Apresoline','Metformin','Acarbose','Insulin','Sulfaurea','DPP4 inhibitor','GLP-1 analogue','TZD','Amiodarone','Dronedarone');
$ecg=array(1=>'SR','AFib','Atrial flutter','LBBB','RBBB','Pacing rhythm');
$gender=array(1=>'Male','Female');

// P2 Procedure
$procedure=array(1=>'Diagnostic','PCI','Radial','Femoral','Brachial','Right','Left');
$equipment =array(1=>'Analyzer Xpress','Ilumien','Quantien','Integrated');
$pressurewire=array(1=>'Certus','Aeris2');
// P3 firstssla
$first_prepci=array(1=>'Focal lesion','Tandem lesion','Long lesion','ISR','Donated vessel','NTG','Isoket');


// P4 firstsslb
$fpontg=array(1=>'NTG');
$fpoisoket=array(1=>'Isoket');
$fstent_placement=array(1=>'Angioplasty only','Angioplasty + Stent');
$fstent_type=array(1=>'BMS','DES','BVS');
$fsnumber=array(1=>'1','2','Others','Overlaps','Separate');
// P5 Admission Risk stratification
$sec_prepci=array(1=>'Focal lesion','Tandem lesion','Long lesion','ISR','Donated vessel','NTG','Isoket');
// P6 Admission Reperfusion
$secpontg=array(1=>'NTG');
$secpoisoket=array(1=>'Isoket');
$secstent_placement=array(1=>'Angioplasty only','Angioplasty + Stent');
$secstent_type=array(1=>'BMS','DES','BVS');
$secsnumber=array(1=>'1','2','Others','Overlaps','Separate');
// P7 Admission Procedure
$third_prepci=array(1=>'Focal lesion','Tandem lesion','Long lesion','ISR','Donated vessel','NTG','Isoket');

//P8
$thirdpontg=array(1=>'NTG');
$thirdpoisoket=array(1=>'Isoket');
$thirdstent_placement=array(1=>'Angioplasty only','Angioplasty + Stent');
$thirdstent_type=array(1=>'BMS','DES','BVS');
$thirdsnumber=array(1=>'1','2','Others','Overlaps','Separate');
/*$medication_type=array(1=>'Aspirin','Clopidogrel','Ticlopidine','Ticargrelor','Warfarin','Dabigatran or Rivaroxaban',
						  'GP IIb/IIIa Inhibitors','Unfractionated Heparin','Low Molecular Weight Heparin','ACE Inhibitor',
						  'Angiotensin lI Receptor Blocker','Oral Beta Blocker','Statin','Other Lipid Lowering Agent',
						  'Ca++ Antagonist','Digoxin','Diuretic','IV Inotropic Agent','Insulin','Sulfonylurea Agent',
						  'Metformin','Glitazone','DPP4-inhibitor','Nitrate');
*/
// P9 others Bifurcation lesions
$nbpacing=array(1=>'Yes','No');
$nad_manufacturer=array(1=>'Sanofi Aventis','Generic');
$npontg=array(1=>'NTG');
$npoisoket=array(1=>'Isoket');
$nplm=array(1=>'1_1_1','1_1_0','1_0_1','0_1_1','1_0_0','0_1_0','0_0_1');
$nnonplm=array(1=>'1_1_1','1_1_0','1_0_1','0_1_1','1_0_0','0_1_0','0_0_1');
// P10 Bifurcation lesions

$bilpolm=array(1=>'1_1_1','1_1_0','1_0_1','0_1_1','1_0_0','0_1_0','0_0_1');
$bilnonpolm=array(1=>'1_1_1','1_1_0','1_0_1','0_1_1','1_0_0','0_1_0','0_0_1');
$bilpontg=array(1=>'NTG');
$bilpoisoket=array(1=>'Isoket');
$bilstent_placement=array(1=>'Angioplasty only','Angioplasty + Stent');
$bilstent_type=array(1=>'BMS','DES','BVS');
$bilnumber=array(1=>'One stent','Two stents');
$billocation=array(1=>'Main','Branch');
$biltechnique=array(1=>'Crush','Culotte','Kissing','T-stenting','V-stenting');

// P11 Clinical Result (in-hospital) crhospital
 
$crhpci_successful=array(1=>'Yes','No');
$crhpw_broken=array(1=>'Yes','No');
$crhpp_complication=array(1=>'Yes','No');
$crhdeath=array(1=>'Yes','No');
$crhcardiac_death=array(1=>'Yes','No');
$crhmi=array(1=>'Yes','No');
$crhmi_type=array(1=>'Q-wave','Non-Q-wave');
$crhcardiogenic_shock=array(1=>'Yes','No');
$crhemergent_cabg=array(1=>'Yes','No');
$crhcardiac_tamponade=array(1=>'Yes','No');
$crhcin=array(1=>'Yes','No');
$crhno_reflow=array(1=>'Yes','No');
$crhdissection=array(1=>'Yes','No');
$crhdffr_change=array(1=>'Yes','No');

// P12 Clinical Result (OPD FU to one-year)
               
$crodeath=array(1=>'Yes','No');
$crocardiac_death=array(1=>'Yes','No');
$cromi=array(1=>'Yes','No');
$cromi_type=array(1=>'Q-wave','Non-Q-wave');
$crotlf=array(1=>'Yes','No');
$crotvf=array(1=>'Yes','No');
$crotlr=array(1=>'Yes','No');
$crotvr=array(1=>'Yes','No');
$crore_hospitalization=array(1=>'Yes','No');
$crostroke=array(1=>'Yes','No');
$crostent_thrombosis=array(1=>'No','Sub-acute','Late','Very late');
$crolast_contact=array(1=>'Chart','Phone call');
$crosecondary_ep=array(1=>'50_70');
$crosecondary_ep_s=array(1=>'Performed PCI','Free to PCI');
$crosecondary_ep1=array(1=>'70_90');
$crosecondary_ep1_s=array(1=>'Performed PCI','Free to PCI');


// T1~T4 Follow up
$re_hospitalization_yes=array(1=>'planned','unplanned');
$re_hospitalization_reason=array(1=>'Cardiac','Non-Cardiac','Unknown');
$hypoglycemia_yes=array('Mild','Moderate','severe');
// T5 End of Registry Form
$primary_reason=array(1=>'Subject withdrew','Lost to follow up','Protocol violation','Death','Other reason(s)');
?>