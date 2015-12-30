<?php
class presswave extends getList {
	public function __construct(){
	        $this->tableName = '_ffr_13presswave';
	        $this->key = 'id';
	        $this->wheres = '1';
	        $this->orders = 'id';
	        $this->pageReNum = 15;
	}
	public function getpresswave($patient_id){
		global $webdb;
		$sql="select * from ".$this->tableName." where patient_id='".(int)$patient_id."'";
		return $webdb->getValue($sql);
	}

public function addform($array){
		global $webdb;
		//$array["ip"]=getip();
		$img=upload::img('pic1',false);
    $img2=upload::img('pic2',false);
		if (!empty($img)) {
			$array['imgurl']=$img['url'];
		}
    	if (!empty($img2)) {
			$array['img2url']=$img2['url'];
		}
		// if(isset($array['birthday_y']) && isset($array['birthday_m'])&&isset($array['birthday_d'])){
			// $array['birthday']=date("Y-m-d",strtotime($array['birthday_y'].'/'.$array['birthday_m'].'/'.$array['birthday_d']));
		// }
    //$array['imgurl']=$img['url'];
	//	$array["add_time"]=date("Y-m-d H:i:s");
		//$array['password']=md5($array['password']);
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
   
    //print_r($array);
   // exit;
		return $this->addData($array);
	}
  
  public function edit($array,$id){
		// if($array['remark'])$array['is_deal'] = 1;
		$img=upload::img('pic1',false);
   	$img2=upload::img('pic2',false);
		if (!empty($img)) {
			$oldInfo=$this->getInfo($id);
			if($oldInfo['imgurl'])unlink(ROOT_PATH.$oldInfo['imgurl']);
			$array['imgurl']=$img['url'];
		}
    if (!empty($img2)) {
			$oldInfo=$this->getInfo($id);
			if($oldInfo['img2url'])unlink(ROOT_PATH.$oldInfo['img2url']);
			$array['img2url']=$img2['url'];
		}
		// if($array["is_deal"]==1){
			// $array["deal_time"]=date("Y-m-d H:i:s");
		// }else{
			// $array["deal_time"]='null';
		// }
		
		$this->editData($array,$id);
	}
}
?>
