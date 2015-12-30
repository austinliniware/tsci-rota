<?php
define('ROOT_PATH',dirname(dirname(__FILE__)).'/');
include ROOT_PATH.'comm/common.inc.php';

if(isset($_SESSION['customer_id'])){
if($_POST["id"]){
$actionkey=array_search($_POST["action"], $action_pages);
// 取消簽署
if($_POST["do"]=="cancel"){
	$class=new patient();
	$info=$class->getInfo((int)$_POST["id"]);
	$baseinfo=array();
	$lockstatus=explode(",",$info['lock_status']);
	// $empty_lockstatus=array_filter($lockstatus);
	// if (empty($empty_lockstatus)) for($i=0;$i<$actionkey;$i++) $lockstatus[$i]=0;
	if ($actionkey==13) for($i=0;$i<$actionkey;$i++) $lockstatus[$i]=0;
	elseif ($actionkey>=14) for($i=0;$i<$actionkey;$i++) if (empty($lockstatus[$i])) $lockstatus[$i]=0;
	$lockstatus[$actionkey]=0;
	$baseinfo['lock_status']=join(",",$lockstatus);
	
	$class->edit($baseinfo, $_POST["id"]);
	
	// $post['signature']="";
	// $post['date_added']="";
	if($_POST["action"]=="patient_9") {
	// $class =new outcome();
	// $class->edit($post, $_POST['id']);
	$webdb->query("update `_web_outcome` set signature=null,date_added=null where patient_id='".$_POST['id']."';");
	}
	else {
	$tabName = $action_dbs[$actionkey];
	// $class=new $className();
	// $class->edit($post, $_POST['id']);
	//$webdb->query("update `_web_".$tabName."` set signature=null,date_added=null where patient_id='".$_POST['id']."';");
	$webdb->query("update `".$tabName."` set signature=null,date_added=null where patient_id='".$_POST['id']."';");
	}
	// echo $action_dbs[$actionkey];
}
// 結束提問狀態
if($_POST["do"]=="finish"){
	$class=new patient();
	$info=$class->getInfo((int)$_POST["id"]);
	$baseinfo=array();
	$status=explode(",",$info['qa_status']);
	for($i=0;$i<$actionkey;$i++) if (empty($status[$i])) $status[$i]=0;
	$status[$actionkey]=0;
	$baseinfo['qa_status']=join(",",$status);
	
	$class->edit($baseinfo, $_POST["id"]);
}
// 提問狀態
if($_POST["do"]=="qa"){
	$post['patient_id']=$_POST["id"];
	$post['user_id']=$_SESSION['customer_id'];
	$post['actionkey']=$actionkey;
	$post['content']=$_POST["content"];
	$class=new question();
	$qid=$class->add($post);
	
	if ($qid) {
	$class=new patient();
	$info=$class->getInfo((int)$_POST["id"]);
	$baseinfo=array();
	$status=explode(",",$info['qa_status']);
	for($i=0;$i<$actionkey;$i++) if (empty($status[$i])) $status[$i]=0;
	$status[$actionkey]=1;
	$baseinfo['qa_status']=join(",",$status);
	
	$class->edit($baseinfo, $_POST["id"]);
	// echo $baseinfo['qa_status'];
	
	// mail通知
	$cusClass=new registration();
	$customerInfo=$cusClass->getCustomer($_SESSION['customer_id']);
	if ($customerInfo['group_id']>=2) { //提問
		$createInfo=$cusClass->getCustomer($info['create_userid']);
		$mail_to=$createInfo["email"];
		$mail_name=$createInfo["name"];
		$msg="有人對 Patient No. ".$_POST['id']." 提出疑問<p>".$_POST["content"];
	} else { //回覆
		$sql="select q.user_id from _web_question q left join _web_registration_group rg on (q.user_id=rg.id) where rg.group_id>=2 and q.id!=".$qid." and q.user_id!=".$_SESSION['customer_id']." and q.actionkey='".(int)$actionkey."' order by q.add_time desc limit 1";
		$lastInfo=$webdb->getValue($sql);
		// $sql="select r.email,r.name from _web_registration r left join _web_registration_group rg on (r.group_id=rg.id) where r.id='".(int)$customer_id."' order by add_time desc";
		// $replyInfo=$webdb->getValue($sql);
		$replyInfo=$cusClass->getCustomer($lastInfo['user_id']);
		$mail_to=$replyInfo["email"];
		$mail_name=$replyInfo["name"];
		$msg="Patient No. ".$_POST['id']." 已回覆<p>".$_POST["content"];
	}
	$form="2008trevor@gmail.com,";
	$name="TAIWAN SOCIETY OF CARDIOLOGY - ACS-DM Registry Case report form";
	$subject="ACS-DM Registry Case report form reminder";
	// $msg=$rooturl."";
	$mail = new PHPMailer(); 
	$mail->CharSet = "utf-8"; // 這裡指定字符集！
	$mail->Encoding = "base64"; 
	$mail->IsHTML(true);
	$mail->From = $form; // 發件人郵箱
	$mail->FromName = $name; // 發件人 
	// $mail->Sender = $form; // 發件人 
	$mail->AddReplyTo($form,$name); 
	$mail->AltBody ="text/html"; 
	$mail->Subject = $subject." - Patient No. ".$_POST['id'];
	$mail->Body = $msg;
	// if ($customerInfo['group_id']==1) {$mail->AddBCC("kiyr33@gmail.com","邵巧萱");$mail->AddBCC("kcchen63@gmail.com","陳冠群"); }
	// $mail->AddAddress($mail_to,$mail_name); 
	// if(!$mail->Send()) $errmsg= "郵件錯誤信息: " . $mail->ErrorInfo;  else $errmsg="";
	$mail->ClearAddresses();
	}
}
}
}
?>