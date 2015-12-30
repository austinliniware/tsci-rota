<?php
/*
 * 使用說明
 */
// 參數說明(發送到, 郵件主題, 郵件內容, 用戶名, 附加信息)
//smtp_mail('yourmail@cgsir.com', '歡迎來到cgsir.com！', 'NULL', 'cgsir.com', 'username');


/*
 * 開始
 * phpmailer類路徑
 */
//$email_class_path=str_replace('\\function\\','\\class\\phpmailer\\',dirname(__FILE__).'\\');
//$email_class_path=str_replace('/function/','/class/phpmailer/',dirname(__FILE__).'/');
$email_class_path=dirname(__FILE__).'/../class/phpmailer/';
require($email_class_path."class.phpmailer.php");  
require_once(ROOT_PATH.'conf/email.conf.php');


	
function smtp_mail ( $sendto_email, $subject, $body, $user_name='', $extra_hdrs=null) {
	global $_send_email;
	$conf = $_send_email;
	
	$smtp = $conf['smtp'];
	$user = $conf['user'];
	$pass = $conf['pass'];
	
	$host = $conf['host'];
	$form = $conf['from'];
	$name = $conf['name'];
	
	$mail = new PHPMailer(); 
	$mail->IsSMTP(); // send via SMTP 
	
	$mail->Host = $smtp; // SMTP servers 
	
	$mail->SMTPAuth = true; // turn on SMTP authentication 
	
	$mail->Username = $user; // SMTP username 注意：普通郵件認證不需要加 @域名 
	
	$mail->Password = $pass; // SMTP password 
	
	$mail->From = $form; // 發件人郵箱
	$mail->FromName = $name; // 發件人 
	
	$mail->CharSet = "utf-8"; // 這裡指定字符集！
	
	$mail->Encoding = "base64"; 
	$mail->AddAddress($sendto_email,"username"); // 收件人郵箱和姓名 www~phperz~com 
	
	$mail->AddReplyTo($form,$host); 
	//$mail->WordWrap = 50; // set word wrap 
	
	//$mail->AddAttachment("/var/tmp/file.tar.gz"); // attachment 
	
	//$mail->AddAttachment("/tmp/image.jpg", "new.jpg"); 
	
	// 郵件主題
	$mail->Subject = $subject;
	//是否html
	$mail->IsHTML(true); // send as HTML 
	// 郵件內容 
//	$mail->Body = 	'
//					<html><head>
//					<meta http-equiv="Content-Language" content="zh-cn">
//					<meta http-equiv="Content-Type" content="text/html; charset=utf-8"> 
//					</head>
//					<body>
//					        歡迎來到<a href="http://www.lele3.com">http://www.lele3.com</a> <br /><br />
//					感謝您註冊為本站會員！<br /><br />
//					</body>
//					</html>
//					'; 
	$mail->Body = $body;
	
	$mail->AltBody ="text/html"; 
	if(!$mail->Send()){ 
	  ///$msg= "郵件發送有誤 <p>"; 
	  ///$msg= "郵件錯誤信息: " . $mail->ErrorInfo; 
	} else {
	  //$msg= "$user_name 郵件發送成功!<br />"; 
	}
	return $msg; 
} 

function send_mail_pass($sendto_email, $newpass){
	$body='您的新密碼是:'.$newpass;
	$subject='您修改了密碼';
	return smtp_mail($sendto_email, $subject, $body);
}

?>