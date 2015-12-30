<?php
//涉及到本目錄下的cookie.class.php文件
class order {
	/**
	 * 獲取訂單
	 * @return array
	 */
	static function get(){
		$now=cookie::get('orderids');
		$ary=json_decode($now,true);
		!$ary && $ary=array();
		return $ary;
	}
	/**
	 * 設置訂單
	 * @param $ary
	 * @return void
	 */
	static function set($ary){
		cookie::set('orderids',json_encode($ary));
	}
	/**
	 * 清空訂單
	 * @return void
	 */
	static function clear(){
		cookie::del('orderids');
	}
	/**
	 * 添加一個訂單
	 * @param $id	商品ID
	 * @param $num	訂購數量
	 * @return void
	 */
	static function add($id,$num=1){
		$ary=self::get();
		!$num && $num=1;
		$ary[intval($id)]=intval($num);
		self::set($ary);
	}
	/**
	 * 刪除某個訂單
	 * @param $id 要刪除的訂單ID
	 * @return void
	 */
	static function del($id){
		$ary=self::get();
		unset($ary[intval($id)]);
		self::set($ary);
	}
	/**
	 * 設置或讀取訂單信息
	 * @param $ary
	 * @return array
	 */
	static function oInfo($ary=null){
		if($ary){
			cookie::set('order',json_encode($ary));
		}else{
			$oinfo=cookie::get('order');
			$ary=json_decode($oinfo,true);
			!$ary && $ary=array();
			return $ary;
		}
	}
}
?>