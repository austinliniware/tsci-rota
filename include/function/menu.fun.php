<?php
/*
 * 菜單
 */
function getMenu($pid=0){
	global $webdb;
	if(isShopUser()) $where=" and (sid='0' or sid='".$_SESSION['shop_id']."')";
		else $where=" and sid=0 ";
	$sql="select * from web_prod_type where parent_id=".$pid." ".$where;
	$menuAry=$webdb->getList($sql);
	if(sizeof($menuAry)>0){
		foreach($menuAry as $key=>$val){
			$sub_menu=getMenu($val['id']);
			if($sub_menu) $menuAry[$key]['children']=$sub_menu;
		}
		return $menuAry;
	}else return false;
}
function menuJson($val,$autoDo=true){
	$return['text']=$val['name'];
	
	if($autoDo){
		$re_id_ary[]='do_tag=prodList';
		$re_id_ary[]='do_type=list';
		$re_id_ary[]='ptid='.$val['id'];
		if(is_array($re_id_ary)) $re_id=implode('&',$re_id_ary);
		$return['id']=($re_id)?$re_id:null;
	}else{
		$return['id']=$val['id'];
	}
	
	if(is_array($val['children'])){
		foreach($val['children'] as $sub)
			$return['children'][]=menuJson($sub,$autoDo);
	}else{
		$return['leaf']=true;
	}
	return $return;
}
function menuTreeToList($menuList,&$reary){
	if(is_array($menuList))
		foreach($menuList as $menu){
			if($menu['id'])
				$reary[]=array('id'=>$menu['id'],'text'=>$menu['text']);
			if(is_array($menu['children']))
				menuTreeToList($menu['children'],$reary);
		}
}

?>