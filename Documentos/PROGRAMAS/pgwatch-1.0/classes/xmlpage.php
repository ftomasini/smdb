<?php

class xmlpage {

	public $pxml;
	public $list;
	public $pageset_pxml;

	public function __construct(&$xml, &$list){
		$this->pxml = new pgwatch_xml($xml, $list);
		$this->list  = &$list;
	}

	public function display($for=null){
		$time = microtime(1);
		$this->pxml->process_children();
		$time = round(1000*(microtime(1)-$time)*1000,3);
		env::$smarty->assign("list", $this->list);
		env::$smarty->assign("process_time", $time);
		env::$smarty->assign("title", $this->pxml->vars['title']);
		env::$smarty->assign("breadcrumb", $this->pxml->vars['breadcrumb']);
		env::$smarty->assign("rightmenu", $this->pxml->vars['rightmenu']);
		env::$smarty->assign("timeline", (string) $this->pxml->sxml['timeline'] == "true");
		$c = $b = $a = $this->list;
		unset($a['date_from'], $a['date_until'], $a['rnd']);
		env::$smarty->assign("url_fixed_params", http_build_query($a));
		unset($b['set'], $b['rnd']);
		env::$smarty->assign("url_inherit_params", http_build_query($b));
		unset($c['rnd']);
		env::$smarty->assign("url_self_params", http_build_query($c));
		env::$smarty->assign("page", $this);
		if (count($this->pxml->sxml->template) == 0)
			trigger_error("Page tag needs a 'template' tag as child.", E_USER_ERROR);
		$this->pxml->process_templates($for);
	}

	public function inherit_params(){
		$ret = array();
		if (func_num_args() == 0){
			$ret = $_GET;
		}else{
			for ($i=0; $i<func_num_args(); $i++){
				$key = func_get_arg($i);
				if (isset($_GET[$key])){
					$ret[$key] = $_GET[$key];
				}
			}
		}
		return http_build_query($ret);
	}

	public function xinherit_params(){
		$ret = array();
		if (func_num_args() == 0){
			$ret = $_GET;
		}else{
			$args = array_flip(func_get_args());
			$ret = array_diff($_GET, $args);
		}
		return http_build_query($ret);
	}

}

?>
