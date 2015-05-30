<?php

session_start();
$_SESSION['chartcount'] = 0;
$_SESSION['username'] = 'johndoe';
$_SESSION['password'] = 'johndoe';

include "lib/common.php";

$page = isset($env->list['page']) ?$env->list['page'] :"";
if (isset($env->list['set'])) {
	$obj = new xmlpageset($env->list);
	$obj->display();
}else{

switch ($page){
	case "":
	case "index":
		$smarty->display("index.tpl");
		break;
	case "dashboard":
		$obj = new dashboard($env->list);
		$op = isset($env->list['op']) ?$env->list['op'] :"";
		switch ($op){
			case "add":
				$obj->add();break;
			case "del":
				$obj->del();break;
			default:
				$obj->display();break;
		}
		break;
	case "sql_worksheet":
		$obj = new sql_worksheet($env->list);
		$obj->display();
		break;
	case "configure":
		$obj = new configure($env->list);
		$obj->display();
		break;
	default:
		if (env::sget_conf('allow_response_template') && is_readable($root_wd . "templates/" . $page . ".tpl")){
			$smarty->display($page . ".tpl");
		}
		break;
}
}


?>
