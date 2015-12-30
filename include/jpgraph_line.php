<?php
mb_internal_encoding('utf-8');
header("Content-Type:text/html; charset=utf-8");
define('ROOT_PATH',dirname(dirname(__FILE__)).'/');
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
if (PHP_SAPI == 'cli'){
	die('This action should only be run from a Web Browser');
}

include ROOT_PATH.'/comm/common.inc.php';

// define('TTF_DIR', ROOT_PATH.'/include/jpgraph/fonts');
require_once ('jpgraph/jpgraph.php');
require_once ('jpgraph/jpgraph_bar.php');

$sql="
SELECT `hospital_id` , count( * ) AS total, sum( IF( P.ACS_24 =1 && P.2dm =1 & P.20years =1 && P.consent_given =1 && P.ACS_comorbidity =0 && P.study =0, 1, 0 ) ) AS enroll, sum( IF( P.ACS_24 =1 && P.2dm =1 & P.20years =1 && P.consent_given =1 && P.ACS_comorbidity =0 && P.study =0, 0, 1 ) ) AS fail
FROM _web_patient AS P
GROUP BY `hospital_id` order by `hospital_id`";
$list=$webdb->getList($sql);

if (count($list)>0) {
foreach ($list as $rs) {
$datay['enroll'][$rs['hospital_id']] = (int)$rs['enroll'];
$datay['fail'][$rs['hospital_id']] = (int)$rs['fail'];
$datay['total'][$rs['hospital_id']] = (int)$rs['total'];
}

$sql="SELECT * FROM `_web_hospital` where is_show=1 order by `site_no`";
$list=$webdb->getList($sql);
foreach ($list as $rs) {
$dateTitle[]=$rs['site_no'];
if ($datay['total'][$rs['id']]>0) {
$datay1[] = $datay['enroll'][$rs['id']];
$datay2[] = $datay['fail'][$rs['id']];
$datay3[] = $datay['total'][$rs['id']];
} else {
$datay1[] = 0;
$datay2[] = 0;
$datay3[] = 0;
}
}

// Create the graph. These two calls are always required
$graph = new Graph(800,550,'auto');
$graph->SetScale("textlin");

$theme_class=new UniversalTheme;
$graph->SetTheme($theme_class);

$graph->img->SetAntiAliasing(false);
$graph->SetBox(false);

$graph->title->SetFont(FF_CHINESE,FS_NORMAL,12);
$graph->title->Set('總收案人數:'.array_sum($datay3).'人');

// Create the bar plots
$b1plot = new BarPlot($datay1);
$b2plot = new BarPlot($datay2);
// Create the grouped bar plot
$gbbplot = new AccBarPlot(array($b1plot,$b2plot));
$gbplot = new GroupBarPlot(array($gbbplot));
// ...and add it to the graPH
$graph->Add($gbplot);

$b1plot->SetColor("#0000CD");
$b1plot->SetFillColor("#0000CD");
$b1plot->SetLegend("Enroll");

$b2plot->SetColor("#B0C4DE");
$b2plot->SetFillColor("#B0C4DE");
$b2plot->SetLegend("Failure");


$graph->yaxis->HideZeroLabel();
$graph->yaxis->HideLine(false);
$graph->yaxis->HideTicks(true,false);
// $graph->yaxis->SetFont(FF_FONT0,FS_NORMAL);

$graph->xaxis->SetTickLabels($dateTitle);
$graph->xaxis->title->Set("SiteNo.");
$graph->xaxis->title->SetMargin(20);
$graph->ygrid->SetFill(false);

// 說明
$graph->legend->SetFrameWeight(1); // 框粗
$graph->legend->SetColor('#4E4E4E','#00A78A'); //字顏色,框顏色
$graph->legend->SetMarkAbsSize(8); // 字大小
$graph->img->SetExpired(true);
// Output
$graph->Stroke();
} else {
Header("Content-type: image/PNG"); 
$im = imagecreate(90,60); //制定圖片背景大小

$black = ImageColorAllocate($im, 0,0,0); //設定三種顏色
$white = ImageColorAllocate($im, 255,255,255); 
$gray = ImageColorAllocate($im, 238,238,238);

imagefill($im,0,0,$gray); //採用區域填充法，設定（0,0）

imagestring($im, 5, 14, 22, "No Data", $black);
// 用 col 顏色將字符串 s 畫到 image 所代表的圖像的 x，y 座標處（圖像的左上角為 0, 0）。
//如果 font 是 1，2，3，4 或 5，則使用內置字體

ImagePNG($im); 
ImageDestroy($im); 
}

?>