<?php
class upload
{
	public static $allow_img_type=array('jpg','jpeg','gif','png');
	
	public static $allow_attach_type=array('jpg','jpeg','gif','png','pdf','ppt','pptx','pps','doc','docx','zip','rar','txt','xls','xlsx','exe');
	
	public function __construct(){
		
	}
	/**
	 * 上傳單個圖片
	 *
	 * @param String $fileName Input名稱
	 * @param boolean $big 是否要上傳大圖片
	 * @param boolean $small 是否要上傳小圖片
	 * @param int $small_width 小圖片的最大寬度
	 * @param int $small_height 小圖片的最大高度
	 * @return array(name,size,surl,url); name原圖片名稱 ,size原圖片大小, surl已上傳的小圖片路徑和名稱, url已上傳的大圖片路徑和名稱
	 */
	public function img($fileName,$small=true,$small_width=300,$small_height=240,$big=true){
		$up_file=$_FILES[$fileName];
		unset($file_data);
		$file_data['name']=$up_file['name'];
		$file_data['size']=$up_file['size'];
		$upload=new uploadFile();
		$path='upload/'.date('Y/m/');
		$upload->setAllowFileType(self::$allow_img_type);
		$file=$upload->upload($up_file,ROOT_PATH.$path);
		if(!$file){
			$file_ary=false;
		}else{
			if($small){
				$imgclass=new Image(ROOT_PATH.$path.$file);
				$imgclass->resizeImage($small_width,$small_height,1,NULL);
				$isurl=$imgclass->save(2,null,'_small');
				$file_data['surl']=$path.basename($isurl);
			}
			
			if(!$big){
				unlink(ROOT_PATH.$path.$file);
			}else{
				$file_data['url']=$path.$file;
			}
			
			$file_ary=$file_data;
		}
		return $file_ary;
	}
	/**
	 * 上傳多圖片
	 *
	 * @param String $fileName Input名稱
	 * @param boolean $big 是否要上傳大圖片
	 * @param boolean $small 是否要上傳小圖片
	 * @param int $small_width 小圖片的最大寬度
	 * @param int $small_height 小圖片的最大高度
	 * @return 二維數組array(array(name,size,surl,url),.....); name原圖片名稱 ,size原圖片大小, surl已上傳的小圖片路徑和名稱 ,url已上傳的大圖片路徑和名稱
	 */
	public function multi_img($fileName,$small=true,$small_width=300,$small_height=240,$big=true){
		if($_FILES[$fileName]['name']){
		foreach($_FILES[$fileName]['name'] as $k => $v){
			$up_file['name']=$_FILES[$fileName]['name'][$k];
			$up_file['type']=$_FILES[$fileName]['type'][$k];
			$up_file['tmp_name']=$_FILES[$fileName]['tmp_name'][$k];
			$up_file['error']=$_FILES[$fileName]['error'][$k];
			$up_file['size']=$_FILES[$fileName]['size'][$k];
			
			unset($file_data);
			$file_data['name']=$up_file['name'];
			$file_data['size']=$up_file['size'];
			$upload=new uploadFile();
			$path='upload/'.date('Y/m/');
			$upload->setAllowFileType(self::$allow_img_type);
			$file=$upload->upload($up_file,ROOT_PATH.$path);
			if($file){
				if($small){
					$imgclass=new Image(ROOT_PATH.$path.$file);
					$imgclass->resizeImage($small_width,$small_height,1,array(255,255,255));
					$isurl=$imgclass->save(2,null,'_small');
					$file_data['surl']=$path.basename($isurl);
				}
				if(!$big){
					unlink(ROOT_PATH.$path.$file);
				}else{
					$file_data['url']=$path.$file;
				}
				$file_ary[$k]=$file_data;
				
			}else{
				$file_ary[$k]=false;
			}
		}
		}
		return $file_ary;
		
	}
	/**
	 * 上傳單個文件
	 *
	 * @param String $fileName Input名稱
	 * @param array $allow_type 允許上傳的文件類型
	 * @return array(name,size,url) name原文件名稱,size原文件大小,url保存的路徑名稱
	 */
	public function attach($fileName,$allow_type=null){
		$up_file=$_FILES[$fileName];
		unset($file_data);
		$file_data['name']=$up_file['name'];
		$file_data['size']=$up_file['size'];
		$upload=new uploadFile();
		
		$type=self::$allow_attach_type;
		if($allow_type)$type=$allow_type;
		$upload->setAllowFileType($type);

		$path='upload/'.date('Y/m/');
		$file=$upload->upload($up_file,ROOT_PATH.$path);
		if(!$file){
			$file_ary=false;
		}else{
			$file_data['url']=$path.$file;
			$file_ary=$file_data;
		}
		return $file_ary;
	}
	/**
	 * 上傳多個文件
	 *
	 * @param String $fileName Input名稱
	 * @param array $allow_type 允許上傳的文件類型
	 * @return array(name,size,url) name原文件名稱,size原文件大小,url保存的路徑名稱
	 */
	public function multi_attach($fileName,$allow_type=null){
		if($_FILES[$fileName]['name']){
			foreach($_FILES[$fileName]['name'] as $k => $v){
				$up_file['name']=$_FILES[$fileName]['name'][$k];
				$up_file['type']=$_FILES[$fileName]['type'][$k];
				$up_file['tmp_name']=$_FILES[$fileName]['tmp_name'][$k];
				$up_file['error']=$_FILES[$fileName]['error'][$k];
				$up_file['size']=$_FILES[$fileName]['size'][$k];
				unset($file_data);
				$file_data['name']=$up_file['name'];
				$file_data['size']=$up_file['size'];
				$upload=new uploadFile();
					
				$type=self::$allow_attach_type;
				if($allow_type)$type=$allow_type;
				$upload->setAllowFileType($type);
					
				$path='upload/'.date('Y/m/');
				$file=$upload->upload($up_file,ROOT_PATH.$path);
				if($file){
					$file_data['url']=$path.$file;
					$file_ary[$k]=$file_data;
				}else{
					$file_ary[$k]=false;
				}
			}
			return $file_ary;
		}
		
	}
}
?>