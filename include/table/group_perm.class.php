<?php
class group_perm extends getList {
		var $errMsg='';
		
        public function __construct(){
                $this->tableName = '_sys_group_perm';
                $this->key = 'id';
                $this->wheres = '1';
                $this->orders = 'id';
                $this->pageReNum = 15;
        }
        
        public function getGroupPerm($gpid){
        	$this->setWhere($this->tableName.".group_id='".$gpid."'");
        	return $this->getArray();
        }
        
        public function getAdminPerm($uid){
        	$this->setWhere($this->tableName.".admin_id='".$uid."'");
        	return $this->getArray();
        }
        
        public function update($id,$idField,$pary){
        	global $webdb;
			if(permission::check('_sys_group_perm','e_tag')){
				$sql="delete from _sys_group_perm where ".$idField."='".$id."';";
				$webdb->query($sql);
				foreach($pary as $perm_id => $data){
					$data[$idField]=$id;
					$data['perm_id']=$perm_id;
					$webdb->insert($data,'_sys_group_perm');
				}
			}
        }
        
       public function update_permission($array){
      		global $webdb;
      		if($array["group_id"]){
      			$id_category="group_id";
      		}else{
      			$id_category="admin_id";
      		}
      		$id=$array[$id_category];
      		
      		$pary=$array['perm'];
			if(permission::check('_sys_group_perm','e_tag')){
				$sql="delete from _sys_group_perm where ".$id_category."='".$id."';";
				$webdb->query($sql);
//				foreach($pary as $perm_id => $data){
//					if(in_array($perm_id,$array['perm_id'])){
//						$data['group_id']=$id;
//						$data['perm_id']=$perm_id;
//						$webdb->insert($data,'_sys_group_perm');
//					}
//				}
				foreach ($array['perm_id'] as $perm_id){
					$data=array();
					if($pary[$perm_id])$data=$pary[$perm_id];
					$data[$id_category]=$id;
					$data['perm_id']=$perm_id;
					$webdb->insert($data,'_sys_group_perm');
					unset($data);
				}
			}
       }
}
?>