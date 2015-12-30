<?php
session_start();//將隨機數存入session中
//生成驗證碼圖片 
Header("Content-type: image/PNG"); 
srand((double)microtime()*1000000);//播下一個生成隨機數字的種子，以方便下面隨機數生成的使用

$_SESSION['authnum']="";
$im = imagecreate(62,22); //制定圖片背景大小

$black = ImageColorAllocate($im, 0,0,0); //設定三種顏色
$white = ImageColorAllocate($im, 255,255,255); 
$gray = ImageColorAllocate($im, 200,200,200);

imagefill($im,0,0,$gray); //採用區域填充法，設定（0,0）

while(($authnum=rand()%100000)<10000);
//將四位整數驗證碼繪入圖片 
$_SESSION['authnum']=$authnum;
imagestring($im, 5, 10, 3, $authnum, $black);
// 用 col 顏色將字符串 s 畫到 image 所代表的圖像的 x，y 座標處（圖像的左上角為 0, 0）。
//如果 font 是 1，2，3，4 或 5，則使用內置字體

for($i=0;$i<200;$i++)   //加入干擾象素 
{ 
    $randcolor = ImageColorallocate($im,rand(0,255),rand(0,255),rand(0,255));
    imagesetpixel($im, rand()%70 , rand()%30 , $randcolor); 
}

ImagePNG($im); 
ImageDestroy($im); 
?>