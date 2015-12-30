<?php
class uploadFile {
	private $allowFileTypes = array('html','htm','doc','zip','rar','txt','jpg','jpeg','gif','bmp','png','xls','log');
	
	public $maxFileSize = 8388608;
	
	public function __construct() {
	
	}
	
	public function setAllowFileType($fileTypes) {
		if (!is_array($fileTypes)) {
			$this->allowFileTypes = explode(',', $fileTypes);
		} else {
			$this->allowFileTypes = $fileTypes;
		}
		return;
	} 
	
	public function upload($fileField, $destFolder = './', $fileNameType = 1) {
	
		switch ($fileField['error']) {
		case UPLOAD_ERR_OK :
			$upload_succeed = true;
			break;
		case UPLOAD_ERR_INI_SIZE :
		case UPLOAD_ERR_FORM_SIZE :
			$errorMsg = '';
			$errorCode = -103;
			$upload_succeed = false;
			break;
		case UPLOAD_ERR_PARTIAL : 
			$errorMsg = '';
			$errorCode = -101;
			$upload_succeed = false;
			break;
		case UPLOAD_ERR_NO_FILE : 
			$errorMsg = '';
			$errorCode = -102;
			$upload_succeed = false;
			break;
		case UPLOAD_ERR_NO_TMP_DIR :
			$errorMsg = '';
			$errorCode = -102;
			$upload_succeed = false;
			break;
		case UPLOAD_ERR_CANT_WRITE :
			$errorMsg = '';
			$errorCode = -102;
			$upload_succeed = false;
			break;
		default :
			$errorMsg = '';
			$errorCode = -100;
			$upload_succeed = false;
			break;
		}
		if ($upload_succeed) {
			if ($fileField['size']>$this->maxFileSize) {
				$errorMsg = '';
				$errorCode = -103;
				$upload_succeed = false;
			}
			if ($upload_succeed) {
				$fileExt = FileSystem::fileExt($fileField['name']);
				if (!in_array(strtolower($fileExt),$this->allowFileTypes)) {
					$errorMsg = '';
					$errorCode = -104;
					$upload_succeed = false;
				}
			}
		}
		if ($upload_succeed) {
			if (!is_dir($destFolder) && $destFolder!='./' && $destFolder!='../') {
				$dirname = '';
				$folders = explode('/',$destFolder);
				foreach ($folders as $folder) {
					$dirname .= $folder . '/';
					if ($folder!='' && $folder!='.' && $folder!='..' && !is_dir($dirname)) {
						mkdir($dirname);
						chmod($dirname,0777);
					}
				}
				chmod($destFolder,0777);
			}
			switch ($fileNameType) {  
			case 1:
				$fileName = date('YmdHis');
				$dot = '.';
				$fileFullName = $fileName . $dot . $fileExt;
				$i = 0;
				while (is_file($destFolder . $fileFullName)) {
					$fileFullName = $fileName . $i++ . $dot . $fileExt;
				}
				break;
			case 2:
				$fileFullName = date('YmdHis');
				$i = 0;
				while (is_file($destFolder . $fileFullName)) {
					$fileFullName = $fileFullName . $i++;
				}
				break;
			default:
				$fileFullName = $fileField['name'];
				break;
			}
			if (@move_uploaded_file($fileField['tmp_name'], $destFolder . $fileFullName))
			{
				return $fileFullName;
			}
			else {
				$errorMsg = '';
				$errorCode = -105;
				$upload_succeed = false;
			}
		}
		if (!$upload_succeed) {
//			throw new Exception($errorMsg,$errorCode);
		}

	}
}
?>