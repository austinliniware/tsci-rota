<?php
class CLASSNAME extends getList {
		var $errMsg='';
		
        public function __construct(){
                $this->tableName = 'CLASSNAME';
                $this->key = 'id';
                $this->wheres = '1';
                $this->orders = 'id';
                $this->pageReNum = 15;
        }
}
?>