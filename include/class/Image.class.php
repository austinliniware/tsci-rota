<?php
/*
 * WEIP - Web-based Enterprise Information Platform
 * Copyright (C) 2005 WEIP Group (http://www.weip.cn/)
 * All rights reserved.
 * 未經許可，禁止用於商業用途！
 * 
 * 版本(Version):  0.1
 * 最後修改時間(Modified): 0000-00-00 00:00:00
 * 
 * 文件作者(File Authors):
 *      戴志君
 */

/**
 * WEIP Image 類
 *
 * @package weip.util
 * @author 戴志君
 */
class Image {

	/**
	 * @var string $fileName 文件名
	 * @access private
	 */
	private $fileName = '';
	
	/**
	 * @var gd resource $imageResource 原圖像
	 * @access private
	 */
	private $imageResource = NULL;
	
	/**
	 * @var int $imageWidth 原圖像寬
	 * @access private
	 */
	private $imageWidth = NULL;
	
	/**
	 * @var int $imageHeight 原圖像高
	 * @access private
	 */
	private $imageHeight = NULL;
	
	/**
	 * @var int $imageType 原圖像類型
	 * @access private
	 */
	private $imageType = NULL;
	
	/**
	 * @var int $imageWidth 原圖像寬
	 * @access private
	 */
	public $width = NULL;
	
	/**
	 * @var int $imageHeight 原圖像高
	 * @access private
	 */
	public $height = NULL;
	
	/**
	 * @var int $imageType 原圖像類型
	 * @access private
	 */
	public $type = NULL;
	
	/**
	 * @var int $newResource 新圖像
	 * @access private
	 */
	private $newResource = NULL;
	
	/**
	 * @var int $newResType 新圖像類型
	 * @access private
	 */
	private $newResType = NULL;
	
	/**
	 * 構造函數
	 * @param string $fileName 文件名
     */
	public function __construct($fileName = NULL) {
		$this->fileName = $fileName;
		if ($this->fileName) {
			$this->getSrcImageInfo();
		}
	}
	
	/**
	 * 取源圖像信息
	 * @access private
	 * @return void
	 */
	private function getSrcImageInfo() {
		$info = $this->getImageInfo();
		$this->imageWidth = $info[0];
		$this->imageHeight = $info[1];
		$this->imageType = $info[2];
		$this->width = $info[0];
		$this->height = $info[1];
		$this->type = $info[2];
	}

	/**
	 * 取圖像信息
	 * @param string $fileName 文件名
	 * @access private
	 * @return array
	 */
	private function getImageInfo($fileName = NULL) {
		if ($fileName==NULL) {
			$fileName = $this->fileName;
		}
		$info = getimagesize($fileName);
		return $info;
	}

	/**
	 * 創建源圖像GD 資源
	 * @access private
	 * @return void
	 */
	private function createSrcImage () {
		$this->imageResource = $this->createImageFromFile();
	}

	/**
	 * 跟據文件創建圖像GD 資源
	 * @param string $fileName 文件名
	 * @return gd resource
	 */
    public function createImageFromFile($fileName = NULL)
    {
		if (!$fileName) {
			$fileName = $this->fileName;
			$imgType = $this->imageType;
		}
        if (!is_readable($fileName) || !file_exists($fileName)) {
            throw new Exception('Unable to open file "' . $fileName . '"');
        }

		if (!$imgType) {
			$imageInfo = $this->getImageInfo($fileName);
			$imgType = $imageInfo[2];
		}
		
        switch ($imgType) {
		case IMAGETYPE_GIF:
			$tempResource = imagecreatefromgif($fileName);
			break;
		case IMAGETYPE_JPEG:
			$tempResource = imagecreatefromjpeg($fileName);
			break;
		case IMAGETYPE_PNG:
			$tempResource = imagecreatefrompng($fileName);
			break;
		case IMAGETYPE_WBMP:
			$tempResource = imagecreatefromwbmp($fileName);
			break;
		case IMAGETYPE_XBM:
			$tempResource = imagecreatefromxbm($fileName);
			break;
		default:
			throw new Exception('Unsupport image type');
        }
		return $tempResource;
    }
	/**
	 * 改變圖像大小
	 * @param int $width 寬
	 * @param int $height 高
	 * @param string $flag 一般而言,允許截圖則用4,不允許截圖則用1;  假設要求一個為4:3比例的圖像,則:4=如果太長則自動刪除一部分 0=長寬轉換成參數指定的 1=按比例縮放,自動判斷太長還是太寬,長寬約束在參數指定內 2=以寬為約束縮放 3=以高為約束縮放
	 * @param string $bgcolor 如果不為null,則用這個參數指定的顏色作為背景色,並且圖像擴充到指定高寬,該參數應該是一個數組;
	 * @return string
	 */
	public function resizeImage($width, $height, $flag=1, $bgcolor=null) {
		$widthRatio = $width/$this->imageWidth;
		$heightRatio = $height/$this->imageHeight;
		switch ($flag) {
		case 1:
			if ($this->imageHeight < $height && $this->imageWidth < $width) {
				$endWidth = $this->imageWidth;
				$endHeight = $this->imageHeight;
				//return;
			} elseif (($this->imageHeight * $widthRatio)>$height) {
				$endWidth = ceil($this->imageWidth * $heightRatio);
				$endHeight = $height;
			} else {
				$endWidth = $width;
				$endHeight = ceil($this->imageHeight * $widthRatio);
			}
			break;
		case 2:
			$endWidth = $width;
			$endHeight = ceil($this->imageHeight * $widthRatio);
			break;
		case 3:
			$endWidth = ceil($this->imageWidth * $heightRatio);
			$endHeight = $height;
			break;
		case 4:
			$endWidth2 = $width;
			$endHeight2 = $height;
			if ($this->imageHeight < $height && $this->imageWidth < $width) {
				$endWidth = $this->imageWidth;
				$endHeight = $this->imageHeight;
				//return;
			} elseif (($this->imageHeight * $widthRatio)<$height) {
				$endWidth = ceil($this->imageWidth * $heightRatio);
				$endHeight = $height;
			} else {
				$endWidth = $width;
				$endHeight = ceil($this->imageHeight * $widthRatio);
			}
			break;
		default:
			$endWidth = $width;
			$endHeight = $height;
			break;
		}
		if ($this->imageResource==NULL) {
			$this->createSrcImage();
		}
		if($bgcolor){
			$this->newResource = imagecreatetruecolor($width,$height);
			$bg=ImageColorAllocate($this->newResource,$bgcolor[0],$bgcolor[1],$bgcolor[2]);
			ImageFilledRectangle($this->newResource,0,0,$width,$height,$bg);
			$tox=ceil(($width-$endWidth)/2);
			$toy=ceil(($height-$endHeight)/2);
			if($tox<0) $tox=0;
			if($toy<0) $toy=0;
		}else if ($flag==4) {
			$this->newResource = imagecreatetruecolor($endWidth2,$endHeight2);
		}else {
			$this->newResource = imagecreatetruecolor($endWidth,$endHeight);
		}
		$this->newResType = $this->imageType;
		imagecopyresampled($this->newResource, $this->imageResource, $tox, $toy, 0, 0, $endWidth, $endHeight,$this->imageWidth,$this->imageHeight);
		
	}
	
	/**
	 * 給圖像加水印
	 * @param string $waterContent 水印內容可以是圖像文件名，也可以是文字
	 * @param int $pos 位置0-9可以是數組
	 * @param int $textFont 字體大字，當水印內容是文字時有效
	 * @param string $textColor 文字顏色，當水印內容是文字時有效
	 * @return string
	 */
	public function waterMark($waterContent, $pos = 0, $textFont=5, $textColor="#ffffff") {
		$isWaterImage = file_exists($waterContent);
		if ($isWaterImage) {
			$waterImgRes = $this->createImageFromFile($waterContent);
			$waterImgInfo = $this->getImageInfo($waterContent);
			$waterWidth = $waterImgInfo[0];
			$waterHeight = $waterImgInfo[1];
		} else {
			$waterText = $waterContent;
			//$temp = @imagettfbbox(ceil($textFont*2.5),0,"./cour.ttf",$waterContent);
			if ($temp) {
				$waterWidth = $temp[2]-$temp[6];
				$waterHeight = $temp[3]-$temp[7];
			} else {
				$waterWidth = 100;
				$waterHeight = 12;
			}
		}
		if ($this->imageResource==NULL) {
			$this->createSrcImage();
		}
		switch($pos) 
		{ 
		case 0://隨機 
			$posX = rand(0,($this->imageWidth - $waterWidth)); 
			$posY = rand(0,($this->imageHeight - $waterHeight)); 
			break; 
		case 1://1為頂端居左 
			$posX = 0; 
			$posY = 0; 
			break; 
		case 2://2為頂端居中 
			$posX = ($this->imageWidth - $waterWidth) / 2; 
			$posY = 0; 
			break; 
		case 3://3為頂端居右 
			$posX = $this->imageWidth - $waterWidth; 
			$posY = 0; 
			break; 
		case 4://4為中部居左 
			$posX = 0; 
			$posY = ($this->imageHeight - $waterHeight) / 2; 
			break; 
		case 5://5為中部居中 
			$posX = ($this->imageWidth - $waterWidth) / 2; 
			$posY = ($this->imageHeight - $waterHeight) / 2; 
			break; 
		case 6://6為中部居右 
			$posX = $this->imageWidth - $waterWidth; 
			$posY = ($this->imageHeight - $waterHeight) / 2; 
			break; 
		case 7://7為底端居左 
			$posX = 0; 
			$posY = $this->imageHeight - $waterHeight; 
			break; 
		case 8://8為底端居中 
			$posX = ($this->imageWidth - $waterWidth) / 2; 
			$posY = $this->imageHeight - $waterHeight; 
			break; 
		case 9://9為底端居右 
			$posX = $this->imageWidth - $waterWidth-20; 
			$posY = $this->imageHeight - $waterHeight-10; 
			break; 
		default://隨機 
			$posX = rand(0,($this->imageWidth - $waterWidth)); 
			$posY = rand(0,($this->imageHeight - $waterHeight)); 
			break;     
		}
		imagealphablending($this->imageResource, true);  
		if($isWaterImage) {
			imagecopy($this->imageResource, $waterImgRes, $posX, $posY, 0, 0, $waterWidth,$waterHeight);    
		} else { 
			$R = hexdec(substr($textColor,1,2)); 
			$G = hexdec(substr($textColor,3,2)); 
			$B = hexdec(substr($textColor,5)); 
			
			$textColor = imagecolorallocate($this->imageResource, $R, $G, $B);
			imagestring ($this->imageResource, $textFont, $posX, $posY, $waterText, $textColor);         
		}
		$this->newResource =  $this->imageResource;
		$this->newResType = $this->imageType;
	}
	
	/**
	 * 生成驗證碼圖片
	 * @param int $width 寬
	 * @param string $height 高
	 * @param int $length 長度
	 * @param int $validType 0=數字,1=字母,2=數字加字母
	 * @param string $textColor 文字顏色
	 * @param string $backgroundColor 背景顏色
	 * @return void
	 */
	public function imageValidate($width, $height, $length = 4, $validType = 1, $textColor = '#000000', $backgroundColor = '#ffffff') {
		if ($validType==1) {
			$validString = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
			$validLength = 52;
		} elseif ($validType==2) {
			$validString = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
			$validLength = 62;
		} else {
			$validString = '123456789';
			$validLength = 9;
		}
		
		srand((int)time());
		$valid = '';
		for ($i=0; $i<$length; $i++) {
			$valid .= $validString{rand(0, $validLength-1)};
		}
		$this->newResource = imagecreate($width,$height);
		$bgR = hexdec(substr($backgroundColor,1,2));
		$bgG = hexdec(substr($backgroundColor,3,2));
		$bgB = hexdec(substr($backgroundColor,5,2));
		$backgroundColor = imagecolorallocate($this->newResource, $bgR, $bgG, $bgB);
		$tR = hexdec(substr($textColor,1,2));
		$tG = hexdec(substr($textColor,3,2));
		$tB = hexdec(substr($textColor,5,2));
		$textColor = imagecolorallocate($this->newResource, $tR, $tG, $tB);
		for ($i=0;$i<strlen($valid);$i++){ 
			imagestring($this->newResource,5,$i*$width/$length+3,2, $valid[$i],$textColor); 
		}
		$this->newResType = IMAGETYPE_JPEG;
		return $valid;

	}
	
	/**
	 * 顯示輸出圖像
	 * @return void
	 */
	public function display($fileName='', $quality=100) {
	
		$imgType = $this->newResType;
		$imageSrc = $this->newResource;
        switch ($imgType) {
		case IMAGETYPE_GIF:
			if ($fileName=='') {
				header('Content-type: image/gif');
			}
			imagegif($imageSrc, $fileName, $quality);
			break;
		case IMAGETYPE_JPEG:
			if ($fileName=='') {
				header('Content-type: image/jpeg');
			}
			imagejpeg($imageSrc, $fileName, $quality);
			break;
		case IMAGETYPE_PNG:
			if ($fileName=='') {
				header('Content-type: image/png');
				imagepng($imageSrc);
			} else {
				imagepng($imageSrc, $fileName);
			}
			break;
		case IMAGETYPE_WBMP:
			if ($fileName=='') {
				header('Content-type: image/wbmp');
			}
			imagewbmp($imageSrc, $fileName, $quality);
			break;
		case IMAGETYPE_XBM:
			if ($fileName=='') {
				header('Content-type: image/xbm');
			}
			imagexbm($imageSrc, $fileName, $quality);
			break;
		default:
			throw new Exception('Unsupport image type');
        }
		imagedestroy($imageSrc);
	}
	
	/**
	 * 保存圖像
	 * @param int $fileNameType 文件名類型 0使用原文件名，1使用指定的文件名，2在原文件名加上後綴，3產生隨機文件名
	 * @param string $folder 文件夾路徑 為空為與原文件相同
	 * @param string $param 參數$fileNameType為1時為文件名2時為後綴
	 * @return void
	 */
	public function save($fileNameType = 0, $folder = NULL, $param = '_miniature') {
		if ($folder==NULL) {
			$folder = dirname($this->fileName).DIRECTORY_SEPARATOR;
			
		}
		$fileExtName = FileSystem::fileExt($this->fileName, true);
		$fileBesicName = FileSystem::getBasicName($this->fileName, false);
		switch ($fileNameType) {
		case 1:
			$newFileName = $folder.$param;
			break;
		case 2:
			$newFileName = $folder.$fileBesicName.$param.$fileExtName;
			break;
		case 3:
			$tmp = date('YmdHis');
			$fileBesicName = $tmp;
			$i = 0;
			while (file_exists($folder.$fileBesicName.$fileExtName)) {
				$fileBesicName = $tmp.$i;
				$i++;
			}
			$newFileName = $folder.$fileBesicName.$fileExtName;
			break;
		default:
			$newFileName = $this->fileName;
			break;
		}
		$this->display($newFileName);
		return $newFileName;
	}
}
?>