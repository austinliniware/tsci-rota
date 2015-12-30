<?php
include_once(ROOT_PATH."include/class/json.class.php");
function jsonEncode($ary){
	$json = new json();
	return $json->encode($ary);
}
function jsonDecode($str){
	$json = new json();
	return $json->decode($str);
}
function disJsonVal($value){
	switch(true){
		case is_object($value)	:
								if($value->url) return $value->url;
		case is_array($value)	:
								foreach($value as $k=>$v){
									$value[$k]=$v;
								}
								return implode(',',$value);
		default					:
								return $value;
	}
}
/*
 * 顯示JSON
 */
function dis($ary){
	echo jsonEncode($ary);
}
/*
 * 返回EXT OK信息
 */
function disOk($msg=null){
	$re['success']=true;
	if($msg) $re['msg']=$msg;
	dis($re);
	exit;
}
/*
 * 返回EXT ERROR信息
 */
function disError($msg,$web=false){
	if($web){
		jsCtrl::Alert($msg);
		jsCtrl::back();
	}else{
		$re['success']=false;
		$re['errors']['msg']=$msg;
		dis($re);
	}
	exit;
}
/*
 * 返回EXT表單數據
 */
function disInfoJson($ary){
	echo '['.jsonEncode($ary).']';
}
/*
 * 返回EXT列表數據
 */
function disListJson($count,$data,$model){
	$re['totalProperty']=$count;
	$data_ary=array();
	foreach($data as $val){
		unset($tmp_ary);
		foreach($model as $field){
			$tmp_ary[$field['dataIndex']]=$val[$field['dataIndex']];
		}
		$data_ary[]=$tmp_ary;
	}
	$re['root']=$data_ary;
	dis($re);
}
function debugExtREQUEST($type='post'){
	switch ($type){
		case 'post'	:$data=$_POST;
					break;
		case 'get'	:$data=$_GET;
					break;
		case 'file'	:$data=$_FILES;
					break;
		default	:	$data=$_REQUEST;
	}
	disError(var_export($data,true));
}

?>
