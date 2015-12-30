<?php
class section extends getList {
		
     public function __construct(){
         $this->tableName = '_sys_section';
         $this->key = 'id';
         $this->wheres = "1";
         $this->orders = 'sort,id asc';
         $this->permCheck=false;
         $this->pageReNum = 100;
     }
     
     public function edit($array,$id){
     	$array["Slist"]=empty($array["Slist"])?0:$array["Slist"];
     	$array["Sadd"]=empty($array["Sadd"])?0:$array["Sadd"];
     	$array["Sedit"]=empty($array["Sedit"])?0:$array["Sedit"];
     	$array["Sdelete"]=empty($array["Sdelete"])?0:$array["Sdelete"];
     	$this->editData($array,$id);
     }
     
     public function add($array){
     	$array["Slist"]=empty($array["Slist"])?0:$array["Slist"];
     	$array["Sadd"]=empty($array["Sadd"])?0:$array["Sadd"];
     	$array["Sedit"]=empty($array["Sedit"])?0:$array["Sedit"];
     	$array["Sdelete"]=empty($array["Sdelete"])?0:$array["Sdelete"];
     	$this->addData($array);
     }
     
     public function del($id){
     	$this->delete($id);
     }
}
?>