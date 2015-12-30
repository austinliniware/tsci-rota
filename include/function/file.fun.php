<?php
/*
 * 文件寫入
 */
function fileWrite($path,$fileName,$str){
	if (!is_dir($path) && $path!='./' && $path!='../') {
		$dirname = '';
		$folders = explode('/',$path);
		foreach ($folders as $folder) {
			$dirname .= $folder . '/';
			if ($folder!='' && $folder!='.' && $folder!='..' && !is_dir($dirname)) {
				mkdir($dirname);
			}
		}
		@chmod($path,0777);
	}
	file_put_contents($path.$fileName, $str);
	return true;
}

?>