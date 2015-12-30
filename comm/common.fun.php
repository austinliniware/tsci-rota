<?php
/*
 * 自動加載類
 */
function __autoload($class_name){
	$file = ROOT_PATH.'include/table/'.$class_name.'.class.php';
	if(!file_exists($file)){
		$file = ROOT_PATH.'include/class/'.$class_name.'.class.php';
		if(!file_exists($file)){
			$file = ROOT_PATH.'include/class/'.$class_name.'/'.$class_name.'.class.php';
			if(!file_exists($file)){
				$str=file_get_contents(ROOT_PATH.'include/template.php');
				$str=str_replace('CLASSNAME',$class_name,$str);
				fileWrite(ROOT_PATH.'cache/','tmpclass.php',$str);
				$file=ROOT_PATH.'cache/'.'tmpclass.php';
			}
		}
	}
	include_once ($file);
}
function format_string($tag) {
	//計算asc碼
	$part1=65+$tag;
	$str=chr($part1);
	return $str;
}
/*
 * 
 */
function getProcessedData($class,$info,$special=array('','')){
	$field_ary=$class->getTableField(1);
	foreach($field_ary as $field){
		$fname=$field['name'];
		if ($field['type']=="datetime") {
			if ($info[$fname]!="") {
			$data[$fname]['date']=date("Y-m-d",strtotime($info[$fname]));
			$data[$fname]['h']=date("H",strtotime($info[$fname]));
			$data[$fname]['m']=date("i",strtotime($info[$fname]));
			} else {
			$data[$fname]['date']='';
			$data[$fname]['h']='';
			$data[$fname]['m']='';
			}
		}
		else if (is_array($special['multi']) && in_array($fname,$special['multi'])) $data[$fname]=explode(",", $info[$fname]);
		else if (is_array($special['dcimal']) && in_array($fname,$special['dcimal'])) $data[$fname]=explode(".", $info[$fname]);
		else $data[$fname]=$info[$fname];
	}
	return $data;
}
/*
 * 遞歸某個表,獲得Html
 */
function dgHtml($tab,$html,$ex='&nbsp;&nbsp;',$pid=0,$pf='pid',$kf='id',$where='',$nowex=''){
	global $webdb;
	$sql="select * from ".$tab." where ".$pf."='".$pid."' ";
	if($where) $sql.=$where;
	$res=$webdb->getList($sql);
	!$res && $res=array();
	foreach($res as $val){
		$str=$html;
		$str=str_replace('%ex',$nowex,$str);
		foreach($val as $k=>$v){
			$str=str_replace('%'.$k,$v,$str);
		}
		$reAry[]=$str;
		$reAry=array_merge($reAry,dgHtml($tab,$html,$ex,$val[$kf],$pf,$kf,$where,$nowex.$ex));
	}
	!$reAry && $reAry=array();
	return $reAry;
}
/*
 * 遞歸某個表,獲得下拉菜單
 */
function dgAry($tab,$where='',$pid=0,$ex='',$nf='name',$pf='pid',$kf='id'){
	global $webdb;
	$sql="select ".$kf.",".$nf." from ".$tab." where ".$pf."='".$pid."' ";
	if($where) $sql.=$where;
	$res=$webdb->getList($sql);
	!$res && $res=array();
	foreach($res as $val){
		$val['dicval']=$val[$kf];
		$val['name']=$ex.$val[$nf];
		$reAry[]=$val;
		$reAry=array_merge($reAry,dgAry($tab,$where,$val[$kf],$ex.'&nbsp;&nbsp;',$nf,$pf,$kf));
	}
	!$reAry && $reAry=array();
	return $reAry;
}
/*
 * 從html中獲得Images,還沒有整理
 */
function find_html_images($images_dir) {
	while (list($key, ) = each($this->image_types)) {
		$extensions[] = $key;
	}
	preg_match_all('/"([^"]+\.(' . implode('|', $extensions).'))"/Ui', $this->html, $images);
	for ($i=0; $i<count($images[1]); $i++) {
		if (file_exists($images_dir . $images[1][$i])) {
			$html_images[] = $images[1][$i];
			$this->html = str_replace($images[1][$i], basename($images[1][$i]), $this->html);
		}
	}
	if (tep_not_null($html_images)) {
		$html_images = array_unique($html_images);
		sort($html_images);
		for ($i=0; $i<count($html_images); $i++) {
			if ($image = $this->get_file($images_dir . $html_images[$i])) {
				$content_type = $this->image_types[substr($html_images[$i], strrpos($html_images[$i], '.') + 1)];
				$this->add_html_image($image, basename($html_images[$i]), $content_type);
			}
		}
	}
}
/*
 * 時間計算
 */
function dateAdd($format='Y-m-d',$d=0,$m=0,$y=0){
	return date($format, mktime(0,0,0,date("m")+$m,date("d")+$d,date("Y")+$y));
}
/*
 * JavaScript 的 escape() 的反函式
 */
function unescape($str){
    $str = rawurldecode($str);
    preg_match_all("/%u.{4}|&#x.{4};|&#\d+;|.+/U",$str,$r);
    $ar = $r[0];

    foreach($ar as $k=>$v){
        /* 下面的 UTF-8 可針對你的網頁編碼方式作變更 */
        if(substr($v,0,2)=="%u"){
            $ar[$k]=iconv("UCS-2","UTF-8//IGNORE",pack("H4",substr($v,-4)));}
        elseif(substr($v,0,3)=="&#x"){
            $ar[$k]=iconv("UCS-2","UTF-8//IGNORE",pack("H4",substr($v,3,-1)));}
        elseif(substr($v,0,2)=="&#"){
            $ar[$k]=iconv("UCS-2","UTF-8//IGNORE",pack("n",substr($v,2,-1)));}
    }
    return join("",$ar);
}
/*
 * 數組排序
 */
function aryDesc($array,$key){
	if(is_array($array)){
		$order=$ary=array();
		foreach($array as $k=>$v){
			$ary[]=$v;
			$order[]=$v[$key];
		}
		array_multisort($order,$ary);
		return $ary;
	}else return false;
}
/*
 * 安全跳轉到指定的路徑
 */
function go($url){
	@header('Location :'.$url);
	jsCtrl::Location($url);
	exit;
}
function redirect($url){
	@header('Location :'.$url);
	jsCtrl::Location($url);
	exit;
}
/**
 * @param $name 隱藏字段名
 * @param $value 隱藏字段值
 * @param $toolbar 工具欄樣式,可選值 Basic Default
 * @param $skin 面板,可選值 default office2003 silver
 * @param $Lang 語言,可選值 zh-cn zh e
 *
 */
include_once ROOT_PATH.'ckeditor/ckeditor.php';
include_once ROOT_PATH.'ckeditor/ckfinder/ckfinder.php';
function htmlEdit($name,$value,$height=700,$width=null,$toolbar=array(),$skin='office2003',$Lang='zh'){
	global $rooturl;
	$CKEditor = new CKEditor();
	$CKEditor->basePath = $rooturl.'ckeditor/';
	$config = array();
	if($height)$config["height"]=$height;
	if($width)$config["width"]=$width;
	if($skin)$config["skin"]=$skin;
	if($Lang)$config["language"]=$Lang;
	$config['filebrowserBrowseUrl'] = $rooturl.'ckeditor/ckfinder/ckfinder.html';
	$config['filebrowserImageBrowseUrl'] = $rooturl.'ckeditor/ckfinder/ckfinder.html?type=Images';
	$config['filebrowserFlashBrowseUrl'] = $rooturl.'ckeditor/ckfinder/ckfinder.html?type=Flash';
	$config['filebrowserUploadUrl'] = $rooturl.'ckeditor/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files';
	$config['filebrowserImageUploadUrl'] = $rooturl.'ckeditor/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images';
	$config['filebrowserFlashUploadUrl'] = $rooturl.'ckeditor/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash';
	$config['toolbar'] = array(
		 array('Source','Preview', '-','Undo','Redo','Cut','Copy','Paste','PasteText','SelectAll'),
	     array('JustifyLeft','JustifyCenter','JustifyRight','JustifyFull'),
		 array('Link', 'Unlink', 'Anchor','-','Image','Flash','flvPlayer' ),
		 array('Table','HorizontalRule','SpecialChar' ),
	     // array('OrderedList','UnorderedList','-','Outdent','Indent','Blockquote'),
	     array('Bold', 'Italic', 'Underline', 'Strike','NumberedList','BulletedList'),
	     // array('Image','Flash','Table','Rule','SpecialChar','PageBreak'),
	     array('Format','Font','FontSize','TextColor','BGColor')
         );
	// $config['extraPlugins'] = 'flvPlayer';
	return   $CKEditor->editor($name, $value, $config);
}
/*
 * 顯示有或無
 */
function haveYN($val){
	return ($val==1)?'<span style="color: blue;">有</span>':'<span style="color: red;">無</span>';
}
function getCartNum(){
	if(!isset($_SESSION['cart'])){
		return 0;
	}
	$num=0;
	foreach ($_SESSION['cart'] as $key=>$val){
		if($val){
			$num +=(int)$val['num'];
		}
	}
	return $num;
}
function getCartTotal(){
	if(!isset($_SESSION['cart'])){
		return 0;
	}
	global $webdb;
	
	$subTotal=0;
	foreach ($_SESSION['cart'] as $key=>$val){
		if($val){
			$info=array();
			$info=$webdb->getValue("select * from _web_".$val['type'].' where id='.$val['id']);
			$subTotal +=$info['price']*$val['num'];
		}
	}
	return $subTotal;
}
function getYunFee($payment_method){
	if(!isset($payment_method))return false;
	$total=getCartTotal();
	$yunfee=0;
	switch ($payment_method){
		case 'store_cod':
			if($total<1000){
				$yunfee +=50;
			}
			break;
		case 'cod':
			if($total<1000){
				$yunfee +=50;
			}
			break;
		case 'buysafe':
			if($total<1000){
				$yunfee +=50;
			}
			break;
	}
	return $yunfee;
}
function getCustomerGroupName($group_id){
	global $webdb;
	$sql="select * from _web_registration_group where id=".$group_id;
	$info=$webdb->getValue($sql,'name');
	return $info;
}
function getHospitalName($id){
	global $webdb;
	$sql="select * from _web_hospital where id=".$id;
	$info=$webdb->getValue($sql,'name');
	return $info;
}
function getCustomerName($id){
	global $webdb;
	$sql="select * from _web_registration where id=".$id;
	$info=$webdb->getValue($sql,'name');
	return $info;
}
function getOrderStatus($order_status_id){
	global $webdb;
	$sql="select * from _web_order_status where order_status_id=".$order_status_id;
	$info=$webdb->getValue($sql,'name');
	return $info;
}
/*
 * 廣告顯示
 */
function adshow($name,$page=null){
	global $webdb,$rooturl;
	!$page && $page=$_SERVER['PHP_SELF'];
	$sql="select * from _web_ad where page_tag='".$page."' and name_tag='".$name."'";
	$ad_data=$webdb->getValue($sql);
	/*
	 * 用於自動添加
	 */
	if(!$ad_data) $webdb->query("insert into _web_ad (page_tag,name_tag,adtype,img,title) values ('".$page."','".$name."','img','/images/index/ad1.jpg','');");
	//自動添加over
	if(substr($ad_data['img'],0,7)!='http://') $ad_data['img']=$rooturl.$ad_data['img'];
	$htmlstr='';
	if($ad_data['adtype']=='swf'){
		$htmlstr='<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,19,0" width="968" height="118">
					<param name="movie" value="'.$ad_data['img'].'" />
					<param name="quality" value="high" />
					<embed src="'.$ad_data['img'].'" quality="high" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash" width="'.$ad_data['img_w'].'" height="'.$ad_data['img_h'].'"></embed>
					</object>';
	}else if($ad_data['adtype']=='img'){
		if($ad_data['img_w']) $wStr='width="'.$ad_data['img_w'].'"';
		if($ad_data['img_w']) $hStr='height="'.$ad_data['img_h'].'"';
		if($ad_data['link']){
			$htmlstr='<a href="'.$ad_data['link'].'"><img src="'.$ad_data['img'].'" '.$wStr.' '.$hStr.' border="0" /></a>';
		}else{
			$htmlstr='<img src="'.$ad_data['img'].'" '.$wStr.' '.$hStr.' border="0" />';
		}
	}else{
		$htmlstr=$ad_data['html'];
	}
	return $htmlstr;
}
function topAry($local,$limit=5,$page=null){
	!$page && $page=$_SERVER['PHP_SELF'];
	$class=new swf_news();
	$class->setPage($page,$local);
	$class->setLimit(0,$limit);
	return $class->getList();
}
/*
 * 添加一個點擊
 */
function addHit($type,$id){
	if(class_exists($type)){
		$class=new $type();
		$tab=$class->tableName;
	}else $tab=$type;
	$sql="update ".$tab." set hit=hit+1 where id='".$id."';";
	global $webdb;
	$webdb->query($sql);
}

function getTbName($table,$id,$find='title'){
	global $webdb;
	if(strtolower($id)=='root'){
		return '主目錄';
	}
	$result = $webdb->getValue("select * from ".$table." where id='".$id."'",$find);	
	return $result;
}

function getOptPType($sId='',$parent='root'){
	$ct = new product_type();
	$ct->setWhere("parent='".$parent."'");
	$temp = $ct->getList();
	if($sId=='root'){
		$str = '<option value="root" selected >主目錄</option>';
	}else{
		$str = '<option value="root">主目錄</option>';
	}
	
	foreach ($temp as $key => $val){
		if($val['id']==$sId){
			$str.='<option value="'.$val['id'].'" selected >&nbsp;&nbsp;'.$val['title'].'</option>';
		}else{
			$str.='<option value="'.$val['id'].'" >&nbsp;&nbsp;'.$val['title'].'</option>';
		}
	}
	return $str;
}

/*
 * 獲取列表
 */
function getList($table,$where="",$field="*"){
	if(empty($table))return false;
	global $webdb;
	$where=$where?" and ".$where:"";
	$sql = "select ".$field." from ".$table." where 1".$where;
	//echo $sql;
	return $webdb->getList($sql);
}

function getNewsCategoryList($fid=0,$level=0,$ntype=2){
	global $webdb;
	$level++;
	//$data_array=array();
	$sql = "select * from _web_news_category where lang=1 and ntype=".$ntype." and fid=".$fid.' order by sort,id';
	
	$result=$webdb->getList($sql);
	foreach ($result as $key=>$val){
		$str='';
		for($i=1;$i<=($level-1)*8;$i++){
			$str.='&nbsp;';
		}
		$val['name']=$str.$val['name'];
		$data_array[]=$val;
		if($webdb->getList("select * from _web_news_category where lang=1 and ntype=".$ntype." and fid=".$val['id'].' order by sort,id')){
			//$data_array[$key][$val['id']]=$webdb->getList("select * from _web_news_category where is_show=1 and lang=1 and fid=".$val['id'].' order by sort,id');
			$child_array=getNewsCategoryList($val['id'],$level,$val['ntype']);
			foreach ($child_array as $vo){
				$data_array[]=$vo;
			}
		}
		
		
	}
	//echo $sql;
	return $data_array;
}
/*
 * 獵取數據條數
 */
function getCount($table,$where="",$field="*"){
	if(empty($table))return false;
	global $webdb;
	$where=$where?" and ".$where:"";
	return $webdb->getValue("select count(".$field.") as num from ".$table." where 1".$where,'num'); 
}

/*
 * 獲取關鍵字ID
 */
function getKeywordId($news_id){
	if(empty($news_id))return false;
	global $webdb;
	$list=$webdb->getList("select keyword_id from _web_news_keyword where news_id=".$news_id);
	if(empty($list))return false;
	$result=array();
	foreach ($list as $rs){
		$result[]=$rs["keyword_id"];
	}
	return $result;
}
/**
 * 獲取關鍵字ID及名稱
 */
function getKeywordList($news_id){
	if(empty($news_id))return false;
	global $webdb;
	return $webdb->getList("select k.id,k.name from _web_keyword k left join _web_news_keyword nk on nk.keyword_id=k.id where nk.news_id=".$news_id);
}
function getSubject($id){
	global $webdb;
	$sql="select sc.*,n.title as className from _web_solution_category sc left join _web_news n on (n.id=sc.fid) where sc.id=".$id;
	//echo $sql;
	return $webdb->getValue($sql);
}
/*
 * 重寫in_array();
 */
function inArray($need,$ary){
	if(empty($need)||empty($ary))return false;
	return in_array($need,$ary);
}

/**
 * 添加多選分類
 * @param string $tab
 * @param int $tab_id
 * @param array $category_id_ary
 */
function addMultiCategory($tab,$tab_id,$category_id_ary){
	global $webdb;
	// if(empty($tab)||empty($tab_id))return false;
	if(empty($tab)||empty($tab_id)||empty($category_id_ary))return false;
	$webdb->query("delete from _web_category where tab='".$tab."' and tab_id='".$tab_id."'");
	if (!empty($category_id_ary)) {foreach ($category_id_ary as $val){
		$webdb->query("insert into _web_category (tab,tab_id,category_id) values ('".$tab."','".$tab_id."',".$val.")");
	}}
}
/**
 * 獲取多選分類ID
 */
function getMultiCategoryID($tab,$tab_id){
	global $webdb;
	if(empty($tab)||empty($tab_id))return false;
	$list=getList("_web_category","tab='".$tab."' and tab_id=".$tab_id);
	$result=array();
	foreach ($list as $rs){
		$result[]=$rs["category_id"];
	}
	unset($rs,$list);
	return $result;
}
/**
 * 獲取多選分類名稱列表
 */
function getMultiCategoryNameList($tab,$tab_id,$category_table_name){
	if(empty($tab)||empty($tab_id)||empty($category_table_name))return false;
	global $webdb;
	$list=$webdb->getList("select c.name from ".$category_table_name." as c left join _web_category as wc on c.id=wc.category_id and wc.tab='".$tab."' where wc.tab_id=".$tab_id." group by c.id");
	$result=array();
	if(!empty($list)){
		foreach ($list as $rs){
			$result[]=$rs["name"];
		}
		unset($rs,$list);
	}
	return implode("，",$result);
}
function getProductCategoryName($tab_id){
	if(empty($tab_id))return false;
	global $webdb;
	$list=$webdb->getList("select * from _web_product_category where id=".$tab_id);
	//echo "select * from _web_news_category where id=".$tab_id;
	return $list[0]['name'];
}
function getServiceCategoryName($tab_id){
	if(empty($tab_id))return false;
	global $webdb;
	$list=$webdb->getList("select * from _web_service_category where id=".$tab_id);
	return $list[0]['name'];
}
function getProductItem($tab_id,$ntype=1){
	if(empty($tab_id))return false;
	global $webdb;
	global $language_id;
	$nsql='';
	if($ntype==2){
		$nsql=' and ntype='.$ntype.' and language_id like \'%'.$language_id.'%\'';
	}else{
		$nsql=' and ntype='.$ntype;
	}
	$list=$webdb->getList("select * from _web_product_item where product_id=".$tab_id.$nsql);
	//echo "select * from _web_news_category where id=".$tab_id;
	return $list;
}
function getServiceItem($tab_id,$ntype=1){
	if(empty($tab_id))return false;
	global $webdb;
	global $language_id;
	$nsql='';
	if($ntype==2){
		$nsql=' and ntype='.$ntype.' and language_id like \'%'.$language_id.'%\'';
	}else{
		$nsql=' and ntype='.$ntype;
	}
	$list=$webdb->getList("select * from _web_service_item where service_id=".$tab_id.$nsql);
	return $list;
}
function getSolutionItem($tab_id,$ntype=1){
	if(empty($tab_id))return false;
	global $webdb;
	global $language_id;
	$nsql='';
	if($ntype==2){
		$nsql=' and ntype='.$ntype.' and language_id like \'%'.$language_id.'%\'';
	}else{
		$nsql=' and ntype='.$ntype;
	}
	$list=$webdb->getList("select * from _web_solution_item where solution_id=".$tab_id.$nsql);
	return $list;
}
function getDateDifference($dateStart,$dateEnd){
	$dateStart=strtotime($dateStart);
	$dateEnd=strtotime($dateEnd);
	if($dateEnd<$dateStart){
		return 0;
	}else{
		return ceil(($dateEnd-$dateStart)/(60*60*24));
	}
}
function getSaleNumByProductId($product_id,$type='product'){
	if(empty($product_id))return false;
	global $webdb;
	$sql="select SUM(quantity) as total from _web_order_product where product_id=".$product_id." and type='".$type."'";
	
	$list=$webdb->getValue($sql,'total');
	
	return ($list!=NULL &&$list!=0)?$list:0;
}
function getSolutionCategoryImg($tab_id){
	if(empty($tab_id))return false;
	global $webdb;
	$list=$webdb->getList("select * from _web_solution_category where id=".$tab_id);
	//echo "select * from _web_news_category where id=".$tab_id;
	return $list[0]['imgurl1'];
}
function getNextPatientNo(){
	global $webdb;
	$res=$webdb->getValue("SHOW TABLE STATUS LIKE '_web_patient'");
	return $res['Auto_increment'];
}
function getSolutionCategoryName($tab_id){
	if(empty($tab_id))return false;
	global $webdb;
	$list=$webdb->getList("select * from _web_solution_category where id=".$tab_id);
	//echo "select * from _web_news_category where id=".$tab_id;
	return $list[0]['name'];
}
function getNewsCategoryName($tab_id){
	if(empty($tab_id))return false;
	global $webdb;
	$list=$webdb->getList("select * from _web_news_category where id=".$tab_id);
	//echo "select * from _web_news_category where id=".$tab_id;
	return $list[0]['name'];
}
/**
 * 獲取分類名稱列表
 */
function getCategoryNameList($tab,$tab_id){
	if(empty($tab)||empty($tab_id))return false;
	global $webdb;
	$list=$webdb->getList("select c.parent_id,c.name from ".$tab." as c where c.id=".$tab_id);
	$result=array();
	if(!empty($list)){
		foreach ($list as $rs){
			if ($rs['parent_id']>0) $result[] = getCategoryNameList($tab,$rs['parent_id']); //開始遞迴
			$result[] = $rs['name'];
		}
		unset($rs,$list);
	}
	return implode(" / ",$result);
}
function getCountry($lang){
	global $webdb;
	$list=array();
	$list=$webdb->getList("select * from `_web_country` where is_show=1 and lang=".$lang." order by sort");
	return $list;
}

function getDistrict($lang,$country_id=0){
	global $webdb;
	$list=array();
	$str='';
	if($country_id){
		$str=' and parent_id='.$country_id;
	}

	$list=$webdb->getList("select * from `_web_district` where is_show=1 and lang=".$lang.$str." order by sort");
	return $list;
}
function getDistrictsByCountry($country_id){
	global $webdb;
	global $language_id;
	$list=array();
	$list=$webdb->getList("select * from `_web_district` where is_show=1 and lang='".(int)$language_id."' and parent_id='".(int)$country_id."' order by sort");
	return $list;
}
function getCountryName($id){
	global $webdb;
	$list=array();
	$list=$webdb->getList("select * from `_web_country` where id=".$id);
	$name=$list[0]['name'];
	return $name;
}
function getNewsName($id){
	global $webdb;
	$list=array();
	$list=$webdb->getList("select * from `_web_news` where id=".$id);
	$name=$list[0]['title'];
	return $name;
}
function getFloatValue($array){
	$value=is_array($array)?implode('.', $array):$array;
	return floatval($value);
}
function getSearchList($country=0,$district_id=0){
	global $webdb;
	global $language_id;
	$list=array();
	$sql="select n.*,c.name as country,d.name as district from `_web_news` n left join `_web_country` c on (c.id=n.country_id) left join `_web_district` d on (d.id=n.district_id) where n.ntype=7 and n.lang=".$language_id." and n.is_show=1";
	if($country){
		$sql.=" and c.id=".$country;
	}
	if((int)$district_id){
		$sql.=" and d.id=".$district_id;
	}
	//echo $sql;
	$list=$webdb->getList($sql);
	return $list;
}
/**
 * 獲取IP
 */
function getip(){
	global $_SERVER;
	if(getenv('HTTP_CLIENT_IP') && strcasecmp(getenv('HTTP_CLIENT_IP'), 'unknown')) {
	   $onlineip = getenv('HTTP_CLIENT_IP');
	} elseif(getenv('HTTP_X_FORWARDED_FOR') && strcasecmp(getenv('HTTP_X_FORWARDED_FOR'), 'unknown')) {
	   $onlineip = getenv('HTTP_X_FORWARDED_FOR');
	} elseif(getenv('REMOTE_ADDR') && strcasecmp(getenv('REMOTE_ADDR'), 'unknown')) {
	   $onlineip = getenv('REMOTE_ADDR');
	} elseif(isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], 'unknown')) {
	   $onlineip = $_SERVER['REMOTE_ADDR'];
	}
	$onlineip = preg_match("/[\d\.]{7,15}/", $onlineip, $onlineipmatches);
	return $onlineipmatches[0] ? $onlineipmatches[0] : 'unknown';
}
/**
 * 加stong
 *
 * @param string $str
 * @param bool $yes
 * @return string
 */
function strong($str,$yes=false){
	if($yes)$str='<strong>'.$str.'</strong>';
	return $str;
}

function getFirstPhoto($id,$small=true,$db=true){
	if(empty($id))return false;
	global $webdb;
	$result='';
	$p=$small?'img':'img';
	if(!$db){
		$info=$id;
	}else{
		$info = $webdb->getValue("select * from _web_product where id='".$id."'");
	}
	if($info[$p.'1']){
		$result = $info[$p.'1'];
	}
	elseif($info[$p.'2']){
		$result = $info[$p.'2'];
	}elseif($info[$p.'3']){
		$result = $info[$p.'3'];
	}elseif($info[$p.'4']){
		$result = $info[$p.'4'];
	}elseif($info[$p.'5']){
		$result = $info[$p.'5'];
	}
	return $result;
}
function getAllPhoto($id){
	if(empty($id))return false;
	global $webdb;
	$result=array();
	$info = $webdb->getValue("select * from _web_product where id='".$id."'");
	if($info['small_img1']){
		$result[] = array('img'=>$info['img1'],'small_img'=>$info['small_img1']);
	}
	if($info['small_img2']){
		$result[] = array('img'=>$info['img2'],'small_img'=>$info['small_img2']);
	}
	if($info['small_img3']){
		$result[] = array('img'=>$info['img3'],'small_img'=>$info['small_img3']);
	}
        if($info['small_img4']){
		$result[] = array('img'=>$info['img4'],'small_img'=>$info['small_img4']);
	}
        if($info['small_img5']){
		$result[] = array('img'=>$info['img5'],'small_img'=>$info['small_img5']);
	}
	return $result;
}
function getLatestNews(){
	global $webdb;
	$result=array();
	return $webdb->getValue("select id,title,newsdt from _web_news where is_show=1 order by newsdt desc limit 0,1");
}

function pr($var, $echo=true, $label=null, $strict=true) {
    $label = ($label === null) ? '' : rtrim($label) . ' ';
    if (!$strict) {
        if (ini_get('html_errors')) {
            $output = print_r($var, true);
            $output = "<pre>" . $label . htmlspecialchars($output, ENT_QUOTES) . "</pre>";
        } else {
            $output = $label . print_r($var, true);
        }
    } else {
        ob_start();
        var_dump($var);
        $output = ob_get_clean();
        if (!extension_loaded('xdebug')) {
            $output = preg_replace("/\]\=\>\n(\s+)/m", "] => ", $output);
            $output = '<pre>' . $label . htmlspecialchars($output, ENT_QUOTES) . '</pre>';
        }
    }
    if ($echo) {
        echo($output);
        return null;
    }else
        return $output;
}

function getClientIP($safe = true) {
	if (!$safe && env('HTTP_X_FORWARDED_FOR') != null) {
		$ipaddr = preg_replace('/(?:,.*)/', '', env('HTTP_X_FORWARDED_FOR'));
	} else {
		if (env('HTTP_CLIENT_IP') != null) {
			$ipaddr = env('HTTP_CLIENT_IP');
		} else {
			$ipaddr = env('REMOTE_ADDR');
		}
	}

	if (env('HTTP_CLIENTADDRESS') != null) {
		$tmpipaddr = env('HTTP_CLIENTADDRESS');

		if (!empty($tmpipaddr)) {
			$ipaddr = preg_replace('/(?:,.*)/', '', $tmpipaddr);
		}
	}
	return trim($ipaddr);
}
function check_input($value){
	// 去除斜杠
	if (get_magic_quotes_gpc()){
		$value = stripslashes($value);
	}
	return mysql_real_escape_string($value);
}
function check_number($value,$type=0){ //0-int; 1-float
	if (trim($value)=="") return "";
	else if ($type==0) return (int)$value;
	else return floatval($value);
}
function alert($msg){
	echo "<script>";
	echo "alert('".$msg."');";
	echo "</script>";
}
function env($key) {
		if ($key == 'HTTPS') {
			if (isset($_SERVER['HTTPS'])) {
				return (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off');
			}
			return (strpos(env('SCRIPT_URI'), 'https://') === 0);
		}

		if ($key == 'SCRIPT_NAME') {
			if (env('CGI_MODE') && isset($_ENV['SCRIPT_URL'])) {
				$key = 'SCRIPT_URL';
			}
		}

		$val = null;
		if (isset($_SERVER[$key])) {
			$val = $_SERVER[$key];
		} elseif (isset($_ENV[$key])) {
			$val = $_ENV[$key];
		} elseif (getenv($key) !== false) {
			$val = getenv($key);
		}

		if ($key === 'REMOTE_ADDR' && $val === env('SERVER_ADDR')) {
			$addr = env('HTTP_PC_REMOTE_ADDR');
			if ($addr !== null) {
				$val = $addr;
			}
		}

		if ($val !== null) {
			return $val;
		}

		switch ($key) {
			case 'SCRIPT_FILENAME':
				if (defined('SERVER_IIS') && SERVER_IIS === true) {
					return str_replace('\\\\', '\\', env('PATH_TRANSLATED'));
				}
				break;
			case 'DOCUMENT_ROOT':
				$name = env('SCRIPT_NAME');
				$filename = env('SCRIPT_FILENAME');
				$offset = 0;
				if (!strpos($name, '.php')) {
					$offset = 4;
				}
				return substr($filename, 0, strlen($filename) - (strlen($name) + $offset));
				break;
			case 'PHP_SELF':
				return str_replace(env('DOCUMENT_ROOT'), '', env('SCRIPT_FILENAME'));
				break;
			case 'CGI_MODE':
				return (PHP_SAPI === 'cgi');
				break;
			case 'HTTP_BASE':
				$host = env('HTTP_HOST');
				$parts = explode('.', $host);
				$count = count($parts);

				if ($count === 1) {
					return '.' . $host;
				} elseif ($count === 2) {
					return '.' . $host;
				} elseif ($count === 3) {
					$gTLD = array('aero', 'asia', 'biz', 'cat', 'com', 'coop', 'edu', 'gov', 'info', 'int', 'jobs', 'mil', 'mobi', 'museum', 'name', 'net', 'org', 'pro', 'tel', 'travel', 'xxx');
					if (in_array($parts[1], $gTLD)) {
						return '.' . $host;
					}
				}
				array_shift($parts);
				return '.' . implode('.', $parts);
				break;
		}
		return null;
	}
function saddslashes($string) {
	if(is_array($string)) {
		foreach($string as $key => $val) {
			$string[$key] = saddslashes($val);
		}
	} else {
		$string = addslashes($string);
	}
	return $string;
}

/*
 * 字典處理函數
 */
include(ROOT_PATH.'include/function/dic.fun.php');
/*
 * 圖像函數，包括 從html截取圖像，顯示默認圖像
 */
include(ROOT_PATH.'include/function/img.fun.php');
/*
 * 截取本頁URL
 */
include(ROOT_PATH.'include/function/url.fun.php');
/*
 * 用戶函數，包括 判斷是否是商家，是否是管理員等，返回userID、shopID等
 */
include(ROOT_PATH.'include/function/user.fun.php');
/*
 * 文件寫入函數
 */
include(ROOT_PATH.'include/function/file.fun.php');
/*
 * EXT服務器端用到的函數
 */
include(ROOT_PATH.'include/function/json.fun.php');
/*
 * 後台菜單用到的函數
 */
include(ROOT_PATH.'include/function/menu.fun.php');
/*
 * 字符串處理函數
 */
include(ROOT_PATH.'include/function/string.fun.php');
/*
 * 拼音處理函數
 */
include(ROOT_PATH.'include/function/pinyin.fun.php');
/*
 * 郵件處理函數
 */
include(ROOT_PATH.'include/function/email.fun.php');
/*
 * 樹形菜單函數
 */
include(ROOT_PATH.'include/function/tree.function.php');
?>
