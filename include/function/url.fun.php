<?php
function urlkill($key,$fullurl=true){
         $url=preg_replace('/&('.$key.')\=[^&]*/','', '&' . $_SERVER['QUERY_STRING']);
         if($fullurl) $url = $_SERVER['SCRIPT_NAME'] . '?' .substr($url,1);
                 else $url=substr($url . $ext,1);
         return $url;
}

?>