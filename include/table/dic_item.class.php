<?php
class dic_item extends getList {
		var $errMsg='';
		
        public function __construct(){
                $this->tableName = '_sys_dic_item';
                $this->key = $this->tableName.'.id';
                $this->wheres = '1';
                $this->orders = $this->tableName.'.id';
                $this->pageReNum = 100;
                $this->permCheck=false;
        }
        
        public function setDic($id){
        	$this->setWhere("dicid='".$id."'");
        }
        
        public function getListByDic($dic){
	            $this->fieldList = $this->tableName.'.*';
	            $this->wheres = $this->wheres." and ".$this->tableName.".dicid=dic.id and dic.dickey='".$dic."'";
	            $this->groupby = $this->key;
	        	$this->exTableName = '_sys_dic as dic';
	        	return $this->getArray();
        }
        
        public function add($array){
        	$array['dicval']=0;
        	$id=$this->addData($array);
        	$this->editData(array('dicval'=>$id),$id);
        }
}
?>