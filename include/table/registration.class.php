<?php
class registration extends getList {
	public function __construct(){
	        $this->tableName = '_web_registration';
	        $this->key = 'id';
	        $this->wheres = "ntype=1";
	        $this->orders = 'add_time desc';
	        $this->pageReNum = 15;
	}
	public function setKw($array){
		if($array["keywords"]){
			$this->setWhere("name like '%".$array["keywords"]."%' or tel like '%".$array["keywords"]."%'  or mobile like '%".$array["keywords"]."%'  or email like '%".$array["keywords"]."%'");
		}
		if($array["group_id"]){
			$this->setWhere("group_id=".$array["group_id"]);
		}
		if($array["hospital_id"]){
			$this->setWhere("hospital_id=".$array["hospital_id"]);
		}
		// if($array["is_deal"]=="0"||$array["is_deal"]==1){
			// $this->setWhere("is_deal=".$array["is_deal"]);
		// }
	}
	public function getCustomers($data=array()){
		global $webdb;
		$sql="select r.*,rg.name as group_name,h.site_no as site_no,h.name as hospital_name from ".$this->tableName." r left join _web_registration_group rg on (r.group_id=rg.id) left join _web_hospital h on (h.id=r.hospital_id) where r.ntype='1' order by add_time desc";
		//echo $sql;
		return $webdb->getList($sql);
	}
	public function getCustomer($customer_id){
		global $webdb;
		$sql="select r.*,rg.name as group_name,h.site_no as site_no,h.name as hospital_name from ".$this->tableName." r left join _web_registration_group rg on (r.group_id=rg.id) left join _web_hospital h on (h.id=r.hospital_id) where r.id='".(int)$customer_id."' order by add_time desc";
		//echo $sql;
		return $webdb->getValue($sql);
	}
	public function updateGroup($id,$group_id){
		global $webdb;
		$webdb->query("UPDATE ".$this->tableName." SET group_id = '" . int($group_id) . "'  WHERE id = '" . (int)$id . "'");
	}
	public function updateInfo($id,$array){
		global $webdb;
		if(empty($array))return ;
		$img=upload::img('imgfile',false,80,60);
		$str='';
		if(!empty($img)){
			$array['imgurl']=$img['url'];
			$str.="imgurl='".$array['imgurl']."',";
		}
		$webdb->query("UPDATE ".$this->tableName." SET ".$str."name = '" . check_input($array['name']). "',mobile = '" . check_input($array['mobile']). "',sponsor = '" . check_input($array['sponsor']). "'  WHERE id = '" . (int)$id . "'");
	}
	/*public function checkEpaper($email){
		global $webdb;
		$info=$webdb->getValue("select count(email) as total from `_web_epaper` where email='".check_input($email)."'",'total');
		return $info>0?true:false;
	}*/
	
	public function add($array){
		global $webdb;
		$array["ip"]=getip();
		$img=upload::img('signature',false);
		if (!empty($img)) {
			$array['imgurl']=$img['url'];
		}
		// if(isset($array['birthday_y']) && isset($array['birthday_m'])&&isset($array['birthday_d'])){
			// $array['birthday']=date("Y-m-d",strtotime($array['birthday_y'].'/'.$array['birthday_m'].'/'.$array['birthday_d']));
		// }
		$array["add_time"]=date("Y-m-d H:i:s");
		$array['password']=md5($array['password']);
		/*if(isset($array['is_epaper'])){
			if($array['is_epaper']==1){
				$epaper=array();
				$epaper['email']=$array['email'];
				$epaper['is_show']=1;
				$epaper['date_added']=date("Y-m-d H:i:s");
				
				if(!$this->checkEpaper($array['email'])){
					$webdb->insert($epaper, "`_web_epaper` ");
				}else{
					$webdb->update($epaper, "_web_epaper", "email='".$array['email']."'");
				}
			}
		}*/
		return $this->addData($array);
	}
	public function edit($array,$id){
		// if($array['remark'])$array['is_deal'] = 1;
		$img=upload::img('signature',false);
		if (!empty($img)) {
			$oldInfo=$this->getInfo($id);
			if($oldInfo['imgurl'])unlink(ROOT_PATH.$oldInfo['imgurl']);
			$array['imgurl']=$img['url'];
		}
		// if($array["is_deal"]==1){
			// $array["deal_time"]=date("Y-m-d H:i:s");
		// }else{
			// $array["deal_time"]='null';
		// }
		if($array['password']) $array['password']=md5($array['password']);
		else unset($array['password']);
		$this->editData($array,$id);
	}
	public function updatePass($id,$newPass){
		global $webdb;
		$webdb->query("UPDATE ".$this->tableName." SET password = '" . md5($newPass) . "'  WHERE id = '" . (int)$id . "'");
	}
	public function checkEmail($email,$ntype='1'){
		global $webdb;
		$info=$webdb->getValue("select count(email) as total from ".$this->tableName." where email='".$email."' and ntype='".$ntype."'",'total');
		
		return $info>0?true:false;
	}
	public function checkPass($pass,$customer_id){
		global $webdb;
		$info=$webdb->getValue("select count(id) as total from ".$this->tableName." where password='".md5($pass)."' and id='".(int)$customer_id."'",'total');
		
		return $info>0?true:false;
	}
	public function login($email, $password) {
		global $webdb;
		$id = $webdb->getValue("SELECT id FROM ".$this->tableName." WHERE email= '" . $email. "' AND password = '" .(md5($password)). "' and is_show=1",'id');
		
		if ($id) {
			$_SESSION['customer_id'] = $id;
			
			$webdb->query("UPDATE ".$this->tableName." SET  last_login_time=login_time,login_time = NOW(),ip = '" . check_input($_SERVER['REMOTE_ADDR']) . "'  WHERE id = '" . (int)$id . "'");
			return true;
		} else {
			return false;
		}
	}
	public function checkUserId($user_id,$ntype=''){
		global $webdb;
		$res=$webdb->getList("select count(*) as total from ".$this->tableName." where user_id='".$user_id."' and ntype='".$ntype."'");
		if($res[0]['total']>0){
			return true;
		}else{
			return false;
		}
	}
	public function googleLogin($data=array()){
		global $webdb;
		if(isset($data['email'])){
			if(!$this->checkEmail($data['email'],'google')){
				$_SESSION['customer_id']=$this->add($data);
			}else{
				$res=$webdb->getValue("SELECT id FROM ".$this->tableName." WHERE email= '" . check_input($data['email']) . "'");
				$_SESSION['customer_id']=$res['id'];
			}
		}
	}
	public function facebookLogin($data=array()){
		global $webdb;
		if(!$this->checkUserId($data['user_id'],'facebook')){
			$_SESSION['customer_id']=$this->add($data);
		}else{
			$res=$webdb->getValue("SELECT id FROM ".$this->tableName." WHERE user_id= '" . check_input($data['user_id']) . "' and ntype='facebook'");
			$_SESSION['customer_id']=$res['id'];
		}
	}
	public function logout() {
		unset($_SESSION['customer_id']);
	}
}
?>