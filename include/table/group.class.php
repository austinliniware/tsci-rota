<?php
class group extends getList {
		
        public function __construct(){
                $this->tableName = '_sys_group';
                $this->key = 'id';
                $this->wheres = '1';
                $this->orders = 'id';
                $this->pageReNum = 15;
        }
}
?>