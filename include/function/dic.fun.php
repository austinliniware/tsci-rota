<?php
/*
 * 反解析數據字典
 */
function undic($dc,$dcval){
	global $webdb;
	$file=ROOT_PATH.'admin/model/dictionary/'.$dc.'.php';
	$sql="select dt.name as dtname from _sys_dic_item as dt, _sys_dic as d where d.dickey='".$dc."' and dt.dicval='".$dcval."' and dt.dicid=d.id";
	return $webdb->getValue($sql,'dtname');
}

function dicAry($dc,$l=null){
	$class=new dic_item();
	$class->permCheck=false;
	if($l) $class->setLimit(0,$l);
	if(intval($dc)===$dc){
		$class->setDic($dc);
		$list=$class->getArray();
	}else{
		$list=$class->getListByDic($dc);
	}
	return $list;
}

function dicOption($dc,$def=null){
	$list=dicAry($dc);
	foreach($list as $k=>$val){
		$py=pinyin($val['name']);
		$list[$k]['py']=strtoupper($py{0});
	}
	$list=aryDesc($list,'py');
	foreach($list as $val){
		if($val['dicval']==$def) $selected='selected';
		else $selected='';
		$str.='<option value="'.$val['dicval'].'" '.$selected.'>'.$val['py'].' '.$val['name'].'</option>';
	}
	return $str;
}

function aryOption($ary,$def=null,$py=true,$desc=true){
	if($py){
		foreach($ary as $k=>$val){
			$py=pinyin($val['name']);
			$ary[$k]['py']=strtoupper($py{0});
		}
	}
	if($desc){
		$ary=aryDesc($ary,'py');
	}
	foreach($ary as $val){
		if($val['id']==$def) $selected='selected';
		else $selected='';
		$str.='<option value="'.$val['id'].'" '.$selected.'>'.$val['py'].' '.$val['name'].'</option>';
	}
	return $str;
}
?>