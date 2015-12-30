<?php
class dic extends getList {
		var $errMsg='';
		
        public function __construct(){
                $this->tableName = '_sys_dic';
                $this->key = 'id';
                $this->wheres = '1';
                $this->orders = 'id';
                $this->pageReNum = 15;
        }
}
?>