<?php include 'common.inc.php';

$output = empty($_GET['out'])?'google':$_GET['out'];
$type = empty($_GET['type'])?'file':$_GET['type'];
$file_name = $_GET['name'];

$site_rooturl = 'http://www.rat.com.tw/';

$write_str = '';
if($output=='google'){ 
$write_str .="<?xml version=\"1.0\" encoding=\"UTF-8\"?>
<urlset
      xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\"
      xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\"
      xsi:schemaLocation=\"http://www.sitemaps.org/schemas/sitemap/0.9
            http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd\">
<!-- created with Free Online Sitemap Generator www.xml-sitemaps.com -->
";
}

function output($str,$type='txt'){
	global $write_str;
	if($type=='txt'){
		$write_str .=$str."\n";
	}elseif($type=='google'){
		$write_str .="<url>\n<loc>".str_replace("\n","",str_replace("\r","",$str))."</loc>\n</url>\n";
	}
}
if($output=='txt'){
	$write_str .= file_get_contents('common-url.txt');
	$write_str .= "\n";
}else{
	$fp = fopen('common-url.txt','r');
	while($txt = fgets($fp,2048)){
		output(str_replace("\n","",$txt),$output);
	}
	fclose($fp);
}
foreach (getList("_web_news","ntype=1 and is_show=1 and lang='".$lang."' order by descno asc,id desc",'id') as $rs){
	output($site_rooturl.'?action=about&amp;id='.$rs['id']."",$output);
}

foreach (getList("_web_news","ntype=5 and is_show=1 and lang='".$lang."' order by descno asc,id desc","id,title") as $rs){
	output($site_rooturl.'?action=solution&amp;id='.$rs['id']."",$output);
}

foreach (getList("_web_news","ntype=4 and is_show=1 and lang='".$lang."' order by descno asc,id desc","id,title") as $rs){
	output($site_rooturl.'?action=engineering&amp;id='.$rs['id']."",$output);
}

foreach (getList("_web_news_category","ntype=2 and is_show=1 and ".$db_lang."name!='' order by sort asc,id desc") as $rs){
	output($site_rooturl.'?action=news_view&amp;cid='.$rs['id']."",$output);
    $news_sql = 'select count(*) as num from _web_news n left join _web_category c on c.tab_id=n.id where n.is_show=1 and n.ntype=2 and c.tab=\'news\' and  lang=\''.$lang.'\' and category_id='.$rs['id'];
	$news_count = $webdb->getValue($news_sql,'num');
$page_count = ceil($news_count/10);
for($i=1;$i<=$page_count;$i++){
	output($site_rooturl.'?action=news_view&amp;cid='.$rs['id'].'&amp;p='.$i.'',$output);
}
}

foreach (getList("_web_news_category","ntype=3 and is_show=1 and ".$db_lang."name!='' order by sort asc,id desc") as $rs){
	output($site_rooturl.'?action=event&amp;cid='.$rs['id']."",$output);
}

foreach (getList("_web_story_category","parent_id=0 and is_show=1 and ".$db_lang."name!='' order by sort asc,id desc") as $rs){
	output($site_rooturl.'?action=industry&amp;cid='.$rs['id']."",$output);
	foreach (getList("_web_story_category","parent_id=".$rs["id"]." and is_show=1 and ".$db_lang."name!='' order by sort asc,id desc") as $rs1){
		output($site_rooturl.'?action=industry&amp;cid='.$rs['id']."&amp;ccid=".$rs1['id'],$output);
		$story_sql = 'select count(*) as num from _web_story n left join _web_category c on c.tab_id=n.id where n.is_show=1 and c.tab=\'story\' and  lang=\''.$lang.'\' and c.category_id='.$rs1['id'];
		$story_count = $webdb->getValue($story_sql,'num');
		$page_count = ceil($story_count/10);
		for($i=1;$i<=$page_count;$i++){
			output($site_rooturl.'?action=industry&amp;cid='.$rs['id']."&amp;ccid=".$rs1['id'].'&amp;p='.$i.'',$output);
		}
      foreach ($webdb->getList('select id from _web_story n left join _web_category c on c.tab_id=n.id where n.is_show=1 and c.tab=\'story\' and  lang=\''.$lang.'\' and c.category_id='.$rs1['id']) as $rs2){
      	output($site_rooturl.'?action=industry_view&amp;id='.$rs2['id']."&amp;cid=".$rs['id']."&amp;ccid=".$rs1['id'],$output);
      }
	}
}

foreach ($webdb->getList("select id from _web_product p left join _web_category c on c.tab_id=p.id and c.tab='solution' and is_show=1 and lang='".$lang."'") as $rs){
	output($site_rooturl.'?action=products_view&amp;id='.$rs['id']."",$output);
}

foreach (getList("_web_news","ntype=2 and is_show=1",'id') as $rs){
	output($site_rooturl.'?action=news_view&amp;id='.$rs['id']."",$output);
}

foreach (getList("_web_news","ntype=3 and is_show=1",'id') as $rs){
	output($site_rooturl.'?action=event_view&amp;id='.$rs['id']."",$output);
}

foreach (getList("_web_product_category","is_show=1 and ".$db_lang."name!='' order by sort asc,id desc") as $rs){
	output($site_rooturl.'?action=products&amp;cid='.$rs['id']."",$output);
	foreach ($webdb->getList("select id from _web_product p left join _web_category c on c.tab_id=p.id and c.tab='product' and is_show=1 and lang='".$lang."' and category_id=".$rs['id']) as $rs1) {
		output($site_rooturl.'?action=products_view&amp;id='.$rs1['id'].'&amp;cid='.$rs['id']."",$output);
		foreach (getList("_web_product_page","product_id='".$rs1['id']."' and is_show=1 order by descno asc,id desc") as $rs2) {
			output($site_rooturl.'?action=products_viewpage&amp;id='.$rs2['id'].'&amp;pid='.$rs1['id'].'&amp;cid='.$rs['id']."",$output);
		}
	}
}




if($output=='google') $write_str .= "</urlset>";
if($type=='print'){
	if($output=='google'){
		header('Content-Type: text/xml; charset=UTF-8');
	}elseif($output=='txt'){
		header("Content-type:text/plain");
	    header("Content-Disposition:attachment;filename=urllist.txt");
	}
	echo $write_str;
}elseif($type=='file'){
	if($output=='txt'){
		$filename = empty($file_name) ? 'urllist.txt': addslashes($file_name);
		file_put_contents($filename,$write_str);
	}elseif($output=='google'){
		$filename = empty($file_name) ? 'sitemap.xml' : addslashes($file_name);
		file_put_contents($filename,$write_str);
	}
	echo $filename. '  Write File Success!';
}
?>