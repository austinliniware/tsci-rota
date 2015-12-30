<?php
/**
 * JS公用代碼輸出類
 * @author 李鵬飛
 * 2007-09-20 最後更新
 */
class string {
	
	/**
	 * 雙字節字符串截取函數
	 *
	 * @param string $str 要截取的字符串
	 * @param integer $start 開始截取的位置
	 * @param integer $len 要截取的長度
	 * @return string 返回截取的字符串
	 */
	public static function csubstr($str,$start,$len){
		$len=$len/2;
		preg_match_all("/[\\x80-\\xff]?./",$str,$arr);
		return  implode(array_slice($arr[0],$start,$len),"");
	}
	
	/**
	 * 將字符串轉換為SQL語句中的字符串
	 *
	 * @param string $str 如果PHP 指令 magic_quotes_gpc 為 off時，才處理
	 * @return string
	 */
	public static function quoted($str){
		if (get_magic_quotes_gpc()){
			return $str;
		}else{
			return self::qs($str);
		}
	}
	
	public static function qs($str){
		return addslashes($str);
	}
	
	/**
	 * 將指定數字轉為字符串，並加上指定長度的前導0
	 *
	 * @param unknown_type $int
	 * @param unknown_type $length
	 * @return unknown
	 */
	public static function inttostr($int,$length = 6){
		$int = $int.'';
		$length = $length - strlen($int);
		for ($i=1; $i<=$length; $i++){
			$int = '0'.$int;
		}
		return $int;
	}
	
	/**
     +----------------------------------------------------------
     * 生成UUID 单机使用
     +----------------------------------------------------------
     * @access public
     +----------------------------------------------------------
     * @return string
     +----------------------------------------------------------
     */
	 static public function uuid()
    {
        $charid = md5(uniqid(mt_rand(), true));
        $hyphen = chr(45);// "-"
        $uuid = chr(123)// "{"
               .substr($charid, 0, 8).$hyphen
               .substr($charid, 8, 4).$hyphen
               .substr($charid,12, 4).$hyphen
               .substr($charid,16, 4).$hyphen
               .substr($charid,20,12)
               .chr(125);// "}"
        return $uuid;
   }

	/**
	 +----------------------------------------------------------
	 * 生成Guid主键
	 +----------------------------------------------------------
	 * @return Boolean
	 +----------------------------------------------------------
	 */
	static public function keyGen() {
		return str_replace('-','',substr(String::uuid(),1,-1));
	}
}


?>