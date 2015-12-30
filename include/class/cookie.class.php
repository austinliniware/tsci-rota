<?php
class cookie {
	static function set($key,$val){
		global $cookiedomain;
		setcookie($key,$val,time()+(60*60*24*30*12),'/',$cookiedomain);
	}
	static function get($key){
		$str=str_replace('\"','"',$_COOKIE[$key]);
		$str=str_replace('\\\\','\\',$str);
		return $str;
	}
	static function del($key){
		global $cookiedomain;
		setcookie($key,'',1,'/',$cookiedomain);
	}
}
?>