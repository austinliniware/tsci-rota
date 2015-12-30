<?php
class upload1 extends getList {
		var $errMsg='';
		
        public function __construct(){
                $this->tableName = 'upload1';
                $this->key = 'id';
                $this->wheres = '1';
                $this->orders = 'id';
                $this->pageReNum = 15;
        }
}
?>