<?php
/*
 * 字符集轉換
 */
function toutf8($str){
	return iconv('GB18030','UTF-8',$str);
}
function togb2312($str){
	return iconv('UTF-8','GB18030',$str);
}
/*
 * 截取字符串
 */
function substrs($string, $length, $dot = '...',$delhtml=false){
	
	  $charset="utf-8";
	   
	  if($delhtml){
	  	$string=preg_replace("/<[\/\!]*?[^<>]*?>/i","",$string);
	  }
       if(strlen($string) <= $length) {
               return $string;
       }
		
       //$string = str_replace(array('&', '"', '&lt;', '&gt;'), array('&', '"', '<', '>'), $string);

       $strcut = '';
       if(strtolower($charset) == 'utf-8') {

               $n = $tn = $noc = 0;
               while($n < strlen($string)) {

                       $t = ord($string[$n]);
                       if($t == 9 || $t == 10 || (32 <= $t && $t <= 126)) {
                               $tn = 1; $n++; $noc++;
                       } elseif(194 <= $t && $t <= 223) {
                               $tn = 2; $n += 2; $noc += 2;
                       } elseif(224 <= $t && $t < 239) {
                               $tn = 3; $n += 3; $noc += 2;
                       } elseif(240 <= $t && $t <= 247) {
                               $tn = 4; $n += 4; $noc += 2;
                       } elseif(248 <= $t && $t <= 251) {
                               $tn = 5; $n += 5; $noc += 2;
                       } elseif($t == 252 || $t == 253) {
                               $tn = 6; $n += 6; $noc += 2;
                       } else {
                               $n++;
                       }

                       if($noc >= $length) {
                               break;
                       }

               }
               if($noc > $length) {
                       $n -= $tn;
               }
				
               $strcut = substr($string, 0, $n);
               

       } else {
               for($i = 0; $i < $length - strlen($dot) - 1; $i++) {
                       $strcut .= ord($string[$i]) > 127 ? $string[$i].$string[++$i] : $string[$i];
               }
               
       }

       //$strcut = str_replace(array('&', '"', '<', '>'), array('&', '"', '&lt;', '&gt;'), $strcut);
       if(strlen($string)>$length) $strcut=$strcut.$dot;
       return $strcut;
}

/*
 function substrs($content,$length){
	mb_internal_encoding('utf-8');
	$str=$content;
	$content=strip_tags($content);
	if($length && mb_strlen($content)>($length)){
	$newcontent=mb_substr($content,0,$length).'..';
	}
	$restr=str_replace($content,$newcontent,$str);
	return $restr;
	}
	*/
function subHtml($str,$num,$more=false){
	$leng=strlen($str);
	if($num>=$leng) return $str;
	$word=0;
	$i=0;                        /** 字符串指針 **/
	$stag=array(array());        /** 存放開始HTML的標誌 **/
	$etag=array(array());        /** 存放結束HTML的標誌 **/
	$sp = 0;
	$ep = 0;
	while($word!=$num){
		if(ord($str[$i])>128){
			//$re.=substr($str,$i,3);
			$i+=3;
			$word++;
		}else if ($str[$i]=='<'){
			if ($str[$i+1] == '!'){
				$i++;
				continue;
			}

			if ($str[$i+1]=='/'){
				$ptag=&$etag ;
				$k=&$ep;
				$i+=2;
			}else{
				$ptag=&$stag;
				$i+=1;
				$k=&$sp;
			}

			for(;$i<$leng;$i++){
				if ($str[$i] == ' '){
					$ptag[$k] = implode('',$ptag[$k]);
					$k++;
					break;
				}
				if ($str[$i] != '>'){
					$ptag[$k][]=$str[$i];
					continue;
				}else{
					$ptag[$k] = implode('',$ptag[$k]);
					$k++;
					break;
				}
			}
			$i++;
			continue;
		}else{
			//$re.=substr($str,$i,1);
			$word++;
			$i++;
		}
	}
	foreach ($etag as $val){
		$key1=array_search($val,$stag);
		if ($key1 !== false) unset($stag[$key]);
	}
	foreach ($stag as $key => $val){
		if (in_array($val,array('br','img'))) unset($stag[$key1]);
	}
	array_reverse($stag);
	$ends = '</'.implode('></',$stag).'>';
	$re = substr($str,0,$i).$ends;
	if($more) $re.='...';
	return $re;
}
?>