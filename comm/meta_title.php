<?php
/**
 * 獲取頁面TITLE和META的KEYWORDS和DSCRIPTION
 */

$_keywords="";
$_description="";
$_title="";
switch ($_GET["action"]){
	case 'home':
	case '':
		$mt=getMetaInfo(2);
		$_keywords=$mt["keywords"];
		$_description=$mt["description"];
		$_title=$mt["title"];
		break;
	case 'about':
		if($_GET["id"]){
			$mt=getMetaAndTitle("_web_news",$_GET["id"]);
		}/*else{
			$mt=getNewsDetailIdMeta(1);
		}*/else{
			$mt=getMetaInfo(3);
		}
		$_keywords=$mt["meta_keywords"];
		$_description=$mt["meta_description"];
		$_title=$mt["page_title"];
		break;
	case 'engineering':
		if($_GET["id"]){
			$mt=getMetaAndTitle("_web_news",$_GET["id"]);
		}else{
			// $mt=getNewsDetailIdMeta(4);
			$mt=getMetaInfo(9);
		}
		$_keywords=$mt["meta_keywords"];
		$_description=$mt["meta_description"];
		$_title=$mt["page_title"];
		break;
	case 'solution':
		if($_GET["id"]){
			$mt=getMetaAndTitle("_web_news",$_GET["id"]);
		}else{
			// $mt=getNewsDetailIdMeta(5);
			$mt=getMetaInfo(7);
		}
		$_keywords=$mt["meta_keywords"];
		$_description=$mt["meta_description"];
		$_title=$mt["page_title"];
		break;
	case 'news':
	case 'news_view':
		if($_GET["id"]){
			$mt=getMetaAndTitle("_web_news",$_GET["id"]);
		}elseif($_GET["cid"]){
			$mt=getNewsCategoryMeta($_GET["cid"]);
		}else{
			$mt=getMetaInfo(4);
		}
		$_keywords=$mt["meta_keywords"];
		$_description=$mt["meta_description"];
		$_title=$mt["page_title"];
		break;
	case 'event':
	case 'event_view':
		if($_GET["id"]){
			$mt=getMetaAndTitle("_web_news",$_GET["id"]);
		}elseif($_GET["cid"]){
			$mt=getNewsCategoryMeta($_GET["cid"]);
		}else{
			$mt=getMetaInfo(5);
		}
		$_keywords=$mt["meta_keywords"];
		$_description=$mt["meta_description"];
		$_title=$mt["page_title"];
		break;
	case 'industry':
	case 'industry_view':
		if($_GET["id"]){
			$mt=getMetaAndTitle("_web_story",$_GET["id"]);
		}elseif($_GET["ccid"]){
			$mt=getMetaAndTitle("_web_story_category",$_GET["ccid"]);
		}elseif($_GET["cid"]){
			$mt=getMetaAndTitle("_web_story_category",$_GET["cid"]);
		}else{
			$mt=getMetaInfo(8);
		}
		$_keywords=$mt["meta_keywords"];
		$_description=$mt["meta_description"];
		$_title=$mt["page_title"];
		break;
	case 'products':
	case 'products_view':
		if($_GET["id"]){
			$mt=getMetaAndTitle("_web_product",$_GET["id"]);
			$_keywords=$mt["meta_keywords"];
			$_description=$mt["meta_description"];
			$_title=$mt["page_title"];
		}elseif($_GET["cid"]){
			$mt=getMetaAndTitle("_web_product_category",$_GET["cid"],$db_lang);
			$_keywords=$mt["meta_keywords"];
			$_description=$mt["meta_description"];
			$_title=$mt["page_title"];
		}else{
			$mt=getMetaInfo(6);
			$_keywords=$mt["keywords"];
			$_description=$mt["description"];
			$_title=$mt["title"];
		}
		break;
	case 'products_viewpage':
		if($_GET["id"]){
			$mt=getMetaAndTitle("_web_product_page",$_GET["id"]);
			$_keywords=$mt["meta_keywords"];
			$_description=$mt["meta_description"];
			$_title=$mt["page_title"];
		}elseif($_GET["cid"]){
			$mt=getMetaAndTitle("_web_product_category",$_GET["cid"],$db_lang);
			$_keywords=$mt["meta_keywords"];
			$_description=$mt["meta_description"];
			$_title=$mt["page_title"];
		}else{
			$mt=getMetaInfo(6);
			$_keywords=$mt["keywords"];
			$_description=$mt["description"];
			$_title=$mt["title"];
		}
		break;
	/*case 'web':
		if(empty($_GET["cid"])){
			$mt=getMetaInfo(4);
			$_keywords=$mt["keywords"];
			$_description=$mt["description"];
			$_title=$mt["title"];
		}else{
			$mt=getMetaAndTitle("_web_news_category",$_GET["cid"]);
			$_keywords=$mt["meta_keywords"];
			$_description=$mt["meta_description"];
			$_title=$mt["page_title"];
		}
		break;
	case 'web_view':
	case 'art_view':
	case 'keywords_view':
		if($_GET["id"]){
			$mt=getMetaAndTitle("_web_news",$_GET["id"]);
			$_keywords=$mt["meta_keywords"];
			$_description=$mt["meta_description"];
			$_title=$mt["page_title"];
		}
		break;
	case 'keywords':
		if($_GET["kid"]){
			$mt=getMetaAndTitle("_web_keyword",$_GET["kid"]);
			$_keywords=$mt["meta_keywords"];
			$_description=$mt["meta_description"];
			$_title=$mt["page_title"];
		}
		break;*/
	case 'contact':
	case 'contact_site':
		$mt=getMetaInfo(10);
		$_keywords=$mt["keywords"];
		$_description=$mt["description"];
		$_title=$mt["title"];
		break;
	case 'careers':
	case 'careers_weal':
		$mt=getMetaInfo(11);
		$_keywords=$mt["keywords"];
		$_description=$mt["description"];
		$_title=$mt["title"];
		break;
}
// print_r($mt);
/**
 * 獲取Meta和Title
 *
 * @param string $table 表
 */
function getMetaAndTitle($table,$id,$db_lang=""){
	if(empty($table)||empty($id))return false;
	global $webdb;
	return $webdb->getValue("select ".$db_lang."page_title,".$db_lang."meta_keywords,".$db_lang."meta_description from ".$table." where id=".$id);
}

function getMetaInfo($category_id){
	if(empty($category_id))return false;
	global $webdb,$lang;
	$list=$webdb->getList("select * from _web_meta where category_id=".$category_id." and lang='".$lang."'");
	if(empty($list))return false;
	$result=array();
	foreach ($list as $rs){
		$result[$rs["name"]]=$rs["content"];
	}
	return $result;
}
/**
 * 獲取默認ID的META和Title
 *@param int $ntype
 */
function getNewsDetailIdMeta($ntype){
	if(empty($ntype))return false;
	global $webdb;
	return $webdb->getValue("select page_title,meta_keywords,meta_description from _web_news where ntype=".$ntype." and is_show=1 order by descno asc,id desc");
}
function getNewsCategoryMeta($id){
	if(empty($id))return false;
	global $webdb,$db_lang;
	return $webdb->getValue("select page_title,meta_keywords,meta_description from _web_news_category where id=".$id." and is_show=1 order by sort asc,id desc");
}
?>