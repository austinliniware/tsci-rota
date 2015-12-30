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
class FileSystem {
	/**
	 * 構造函數
	 */
	public function __construct() {
	
	}
	/**
	 * 移動文件
	 * @param array $fileList
	 * @param string $from
	 * @param string $to
	 * @param string $option
	 * @return void
	 */
	public static function mv($fileList, $from, $to, $option='f') {
		if ( is_dir($from) && is_dir($to) ) {
			if (!is_array($fileList)) {
				$fileList = explode('|', $fileList);
			}
			foreach ($fileList as $file) {
				$file_src = $from . '/' . $file;
				if (is_file($file_src))	{
					$file_dest = $to . '/' . $file;
					if (!is_file($file_dest) || strpos($option, 'f')!==false) {
						copy($file_src, $file_dest);
						if (strpos($option, 'k')===false) {
							unlink($file_src);
						}
					}
				}
			}
		}
	}
	/**
	 * 刪除文件或文件夾（遞歸）
	 * @param array $fileList
	 * @param string $option
	 * @param string $fileExt 要刪除的文件擴展名 格式:'html'
	 * @return void
	 */
	public static function rm($fileList, $option='r', $fileExt = NULL, $if_rmdir = false) {
		if (!is_array($fileList)) {
			$fileList = explode('|', $fileList);
		}
		foreach ($fileList as $filename) {
			if (is_file($filename))	{
				if (empty($fileExt)) {
					unlink($filename);
				} else {
					if (substr(strrchr($filename, '.'), 1 ) == $fileExt){
						unlink($filename);
					}
				}
			} elseif (is_dir($filename)) {
				if (strpos($option, 'r')!==false) {
					$file_list_ = FileSystem::ls($filename);
					foreach ($file_list_ as $fi => $file) {
						$file_list_[$fi] = $filename . $file;
					}
					FileSystem::rm($file_list_, $option, $fileExt);
				}
				if ($if_rmdir) {
					rmdir($filename);
				}
			}
		}
	}
	/**
	 * 取文件擴展名  
	 * @param string $fileName
	 * @param bool $withDot
	 * @return string
	 */
	public static function fileExt($fileName, $withDot=false)
	{
		$fileName = basename($fileName);
		$pos = strrpos($fileName, '.');
		if ($pos===false) {
			$result = '';
		} else {
			$result = ($withDot) ? substr($fileName, $pos) : substr($fileName, $pos+1);
		}
		return $result;
	}
	public static function getBasicName($fileName, $withDot=false) {
		$ext = FileSystem::fileExt($fileName, true);
		return basename($fileName,$ext);
	}
	/**
	 * 返回指定路徑中符合條件的文件和文件夾列表  
	 * @param string $path
	 * @param array $condition
	 * @param string $sort
	 * @return array
	 */
	public static function ls($path, $condition=array(), $sort='ASC', $withPath=false)
	{
		$result = array();
		if (is_dir($path) && $handle=opendir($path)) {
			while (false !== ($file = readdir($handle))) {
				if ($file!='.' && $file!='..') {
					$valid = true;
					if ($condition)	{
						foreach ($condition as $name => $value)	{
							switch ($name) {
								case 'fileext':
									if (!$fileext_list) {
										$fileext_list = explode('|', $value);
									}
									if ($fileext_list) {
										$valid = in_array(FileSystem::fileExt($file), $fileext_list);
									}
									break;
								case 'fileonly':
									$valid = ( !$value || is_file($path . '/' . $file) );
									break;
								case 'folderonly':
									$valid = ( !$value || is_dir($path . '/' . $file) );
									break;
								case 'filename':
									if ($value) {
										$valid = ( $value == basename($file, FileSystem::fileExt($file, true)) );
									}
									break;
							}
							if (!valid) {
								break;
							}
						}
					}
					if ($valid) {
						$result[] = ($withPath) ? ($path . '/' . $file) : $file;
					}
				}
			}
			closedir($handle); 
		}
		switch ($sort) {
			case 'ASC':
				sort($result);
				break;
			case 'DESC':
				rsort($result);
				break;
		}
		return $result;
	}
	/**
	 * 返回指定路徑中符合條件的文件列表  
	 * @param string $path
	 * @param string $fileext
	 * @param string $sort
	 * @return array
	 */
	public static function fileList($path, $fileext='', $sort='ASC', $withPath=false) {
		$condition = array(
				'fileonly'		=> true,
				'fileext'		=> $fileext,
				);
		$result = FileSystem::ls($path, $condition, $sort, $withPath);
		return $result;
	}
	/**
	 * 返回指定路徑中符合條件的文件夾列表  
	 * @param string $path
	 * @param string $sort
	 * @return array
	 */
	public static function folderList($path, $sort='ASC', $withPath=false)
	{
		$condition = array(
				'folderonly'		=> true,
				);
		$result = FileSystem::ls($path, $condition, $sort, $withPath);
		return $result;
	}

}
?>