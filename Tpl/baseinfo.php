<?php 
// 儲存狀態
	$baseinfo=array();
	$status=explode(",",$patientInfo['finish_status']);
	// $empty_status=array_filter($status);
	// if (empty($empty_status)) for($i=0;$i<$actionkey;$i++) $status[$i]=0;
	for($i=0;$i<$actionkey;$i++) if (empty($status[$i])) $status[$i]=0;
	$status[$actionkey]=$_POST['finish'];
	$baseinfo['finish_status']=join(",",$status);

	// 簽署
	if (check_number($_POST['signature'])) {
	$lockstatus=explode(",",$patientInfo['lock_status']);
	if ($actionkey==13) {
	// if (check_number($_POST['signature'])) {
	for($i=0;$i<=$actionkey;$i++) $lockstatus[$i]=1;
	// } else {
	// for($i=0;$i<=$actionkey;$i++) $lockstatus[$i]=0;
	// }
	$baseinfo['lock_status']=join(",",$lockstatus);
	}
	
	if ($actionkey>=14) {
	// $empty_lockstatus=array_filter($lockstatus);
	// if (empty($empty_lockstatus)) for($i=0;$i<$actionkey;$i++) $lockstatus[$i]=0;
	for($i=0;$i<$actionkey;$i++) if (empty($lockstatus[$i])) $lockstatus[$i]=0;
	$lockstatus[$actionkey]=1;
	$baseinfo['lock_status']=join(",",$lockstatus);
	}
	}
	$class=new patient();
	$class->edit($baseinfo, $_SESSION['patient_id']);
?>