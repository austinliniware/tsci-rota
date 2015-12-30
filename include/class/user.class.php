<?php
class user{
	var $db_table = "_sys_admin";
	var $idField = "id";
	var $nameFiele = "login_name";
	var $passFiele = "login_pass";
	var $session_id_name = "ADMIN_ID";
	
	function check($uname,$upass){
		$userAry=$this->getUser(null,$uname);
		if($userAry[$this->idField]>0){
			if(md5($upass)==$userAry[$this->passFiele]){
				$uid=$this->register($userAry);
				return $uid;
			}else return -2;
		}else return -1;
	}
	
	function getUser($uid,$uname=false,$field=null){
		global $webdb;
		if($uid){
			$sql="select * from ".$this->db_table." where ".$this->idField." = '".$uid."'; ";
		}elseif($uname){
			$sql="select * from ".$this->db_table." where ".$this->nameFiele." = '".$uname."'; ";
		}
		$reAry=$webdb->getValue($sql);
		if($field){
			return $reAry[$field];
		}else{
			return $reAry;
		}
	}
	
	function register($uAry){
		$ADMIN_ID=$uAry[$this->idField];
		//@session_register($this->session_id_name);
		$_SESSION[$this->session_id_name]=$ADMIN_ID;
		return $ADMIN_ID;
	}
}

?>