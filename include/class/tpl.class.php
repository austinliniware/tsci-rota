<?php
class tpl extends Smarty {
	function __construct(){
		global $rooturl;
		$this->template_dir = ROOT_PATH. "/template/";
		$this->compile_dir = ROOT_PATH. "/cache/templates/";
		$this->config_dir = ROOT_PATH. "/configs/";
		$this->cache_dir = ROOT_PATH. "/cache/";
		$this->left_delimiter = '{{';
		$this->right_delimiter = '}}'; 
		$this->assign('rooturl',$rooturl);
		$this->assign('roottpl',$rooturl.'template/');
		$this->assign('now',date('Y-m-d H:i'));
	}
}
?>