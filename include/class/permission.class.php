<?php
class permission {
	/*
	function check($tab,$action,$uid=null){
		global  $webdb,$admin_folder;
		if(!$admin_folder) return true;
		!$uid && $uid=$_SESSION["ADMIN_ID"];
		$perminfo=$webdb->getList("select pg.* from _sys_group_perm as pg,_sys_permission as p,_sys_admin as a where pg.perm_id=p.id and p.tab='".$tab."' and (pg.admin_id='".$uid."' or (pg.group_id=a.gpid and a.id='".$uid."'))");
		if($perminfo && $perminfo[$action]) return $perminfo[$action];
		else if(is_array($perminfo)){
			foreach($perminfo as $perm){
				if($perm[$action]) return $perm[$action];
			}
		}else return false;
	}
	
	function userPerm($uid){
		global  $webdb;
		return $webdb->getList("select pg.* from _sys_group_perm as pg,_sys_permission as p,_sys_admin as a where pg.admin_id='".$uid."' or (pg.group_id=a.gpid and a.id='".$uid."')");
	}
	
	function getList(){
		global  $webdb;
		return $webdb->getList("select * from _sys_permission");
	}
	
	function errMsg($str='對不起您沒有該操作的權限'){
		jsCtrl::Alert($str);
		exit;
	}
*/
	function check2($tab,$action,$uid=null){
		self::chk($tab,$action);
		global  $webdb,$admin_folder;
		if(!$admin_folder) return true;
		!$uid && $uid=$_SESSION["ADMIN_ID"];
		$perminfo=$webdb->getList("select pg.* from _sys_group_perm as pg,_sys_section as p,_sys_admin as a where pg.perm_id=p.id and p.table_name='".$tab."' and (pg.admin_id='".$uid."' or (pg.group_id=a.gpid and a.id='".$uid."'))");
		if($perminfo && $perminfo[$action]){
			echo "39";
			return $perminfo[$action];
		}
		else if(is_array($perminfo)){
			foreach($perminfo as $perm){
				if($perm[$action]){
					echo "45";
					return $perm[$action];
				}
			}
		}else{
			echo "50";
			echo  $perm[$action];
			return false;
		}
	}
	
	function userPerm($uid){
		global  $webdb;
		return $webdb->getList("select pg.* from _sys_group_perm as pg,_sys_section as p,_sys_admin as a where pg.admin_id='".$uid."' or (pg.group_id=a.gpid and a.id='".$uid."')");
	}
	
	function getList(){
		global  $webdb;
		return $webdb->getList("select * from _sys_section");
	}
	
	function errMsg($str='對不起您沒有該操作的權限'){
		jsCtrl::Alert($str);
		exit;
	}
	function getSection($table_name){
		if(empty($table_name))return false;
		global $webdb;
		$table_name=trim($table_name);
		return $webdb->getList("select id,table_name,field_name,field_value from _sys_section where table_name='".$table_name."'");
	}

	function get_group_id($admin_id){
		if(empty($admin_id)) return false;
		global $webdb;
		return $webdb->getValue("select * from _sys_admin where id=".$admin_id,"gpid");
	}
	function check($table,$action,$admin_id=null){
		global $webdb,$admin_folder;
		if(!$admin_folder) return true;
		if(empty($table)||empty($action))return false;
		$url_parameter=$_GET;
		unset($url_parameter["do"],$url_parameter["type"],$url_parameter["cn"]);
		$permission=self::getSection($table);
		
		$section_id=array();
		//get section id
		if(empty($permission)){
			return false;
		}elseif(count($permission)==1){
			$perm=$permission[0];
			if(empty($perm["field_name"])){
				$section_id[]=$perm["id"];
			}else{
				foreach ($url_parameter as $key=>$up){
					if($key==$perm["field_name"]&&$up=$perm["field_value"]){
						$section_id[]=$perm["id"];
					}
				}
			}
			if(empty($section_id))return false;
			unset($perm);
		}else{
//			print_r($permission);
//			print_r($url_parameter);
			$empty_id="";
			foreach ($permission as $p){
				foreach ($url_parameter as $key=>$up){
					if($key==$p["field_name"]&&$up==$p["field_value"]){
						$section_id[]=$p["id"];
					}
				}
				if($p["field_name"]==""){
					$empty_id=$p["id"];
				}
			}
			if(empty($section_id)){
				$table_field_array=$webdb->getTableField($table);
				$table_field=array();
				foreach ($table_field_array as $tf){
					$table_field[]=$tf["name"];
				}
				
				foreach ($permission as $p){
					foreach ($url_parameter as $key=>$up){
						if($p["field_name"]&&in_array($key,$table_field)&&$key!=$p["field_name"]){
							$rs=$webdb->getValue("select count(*) as cnt from ".$table." where 1 and ".$key."='".$up."' and ".$p["field_name"]."='".$p["field_value"]."'","cnt");
							if($rs)$section_id[]=$p["id"];
						}
					}
				}
				
				if(empty($section_id)&&$empty_id){
					$section_id[]=$empty_id;
				}
			}
		}

		!$admin_id && $admin_id=$_SESSION["ADMIN_ID"];
		if(empty($section_id))return false;
		if(empty($admin_id)) return false;
		$group_id=self::get_group_id($admin_id);

		$allow_perm_ary=$webdb->getList("select perm_id from _sys_group_perm where (admin_id=".$admin_id." or group_id=".$group_id.") and ".$action."=1");
		if(empty($allow_perm_ary)) return false;
		$allow_perm=array();
		
		foreach ($allow_perm_ary as $apa){
			$allow_perm[]=$apa["perm_id"];
		}
		foreach ($section_id as $val){
			if(in_array($val,$allow_perm)){
				return true;
			}
		}
		return false;
	}
}
?>