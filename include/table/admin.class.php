<?php
class admin extends getList {
		var $errMsg='';
		
        public function __construct(){
                $this->tableName = '_sys_admin';
                $this->key = $this->tableName.'.id';
                $this->wheres = '1';
                $this->orders = $this->tableName.'.id desc';
                $this->pageReNum = 15;
        }

        public function getList(){
	            $this->fieldList = $this->tableName.'.*,gp.name as gp_name';
	            $this->wheres = $this->wheres." and ".$this->tableName.".gpid=gp.id";
	            $this->groupby = $this->key;
	        	$this->exTableName = '_sys_group as gp';
	        	return $this->getArray();
        }
}
?>