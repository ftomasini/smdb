<?php

include $root_wd . "smarty/Smarty.class.php";

class pgwatch_smarty extends Smarty {
	
	public function __construct(){
		parent::__construct();
		$env = env::instance();
		$this->config_dir = SMARTY_DIR;
		$this->template_dir = $env->root_wd . 'templates';
		$this->compile_dir  = $env->root_wd . 'templates_c';
		$this->compile_check = TRUE;
		$this->debugging = FALSE;
		$this->caching = FALSE;
		$this->left_delimiter = '{{';
		$this->right_delimiter = '}}';
		$this->assign("env", $env);
	}

	public static function get_sql($filename, $params=null){
		$obj = new self();
		if (!is_null($params)){
			$obj->assign($params);
		}
		return $obj->fetch($filename);
	}

}

?>
