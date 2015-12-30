<?php
if($_GET['cn']=='news'){
if($_GET['ntype']){
	switch ($_GET['ntype']){
		case 1 :
			$classStr = '關於我們';
			break;
		case 2 :
			$classStr = '最新消息';
		   break;
	}
}
}
if($_GET['cn']=='news_category'){
	switch ($_GET['ntype']){
		case 2 :
		  $classStr = '最新消息分類';
		  break;
	}
}
if($_GET['cn']=='solution'){
	 $classStr = '投資人';	
}
if($_GET['cn']=='country'){
	$classStr = '區域';
}
if($_GET['cn']=='district'){
	$classStr = '城市';
}

if($_GET['cn']=='market_country'){
	$classStr = '行銷網絡國家';
}
if($_GET['cn']=='market_area'){
	$classStr = '行銷網絡區域';
}

if($_GET['cn']=='contact'){
	switch ($_GET['ntype']){
		case 2 :
		  $classStr = '留言';
		  break;
		case 1 :
		  $classStr = '樣品需求';
		  break;
	}
}
if($_GET['cn']=='slider'){
	switch ($_GET['ntype']){
		case 4 :
			$classStr = '轉寄郵箱';
			break;
		case 1 :
			$classStr = '首頁幻燈片';
			break;
		case 5 :
			$classStr = '首頁文字區塊';
			break;
		case 2 :
			$classStr = '內頁幻燈片';
			break;
		case 3 :
			$classStr = '首頁廣告圖';
			break;
	}
}
if($_GET['cn']=='solution_category'){
	 $classStr = '投資人類別';
}

if($_GET['cn']=='product'){
	$classStr = '產品';
}
if($_GET['cn']=='keyword'){
	$classStr = 'Page Title';
}
if($_GET['cn']=='product_category'){
	$classStr = '產品分類';
}

if($_GET['cn']=='users'){
	$classStr = '人員列表';
}
if($_GET['cn']=='registration'){
	$classStr = '人員';
}
if($_GET['cn']=='registration_group'){
	$classStr = '人員群組';
}
if($_GET['cn']=='epaper'){
	$classStr = '電子報訂閱';
}
?>