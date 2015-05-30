<?php

define('DEBUG', true);

# working dir root with slash at the end
$root_wd = dirname(dirname(__FILE__)) . "/";

include_once $root_wd . "lib/autoload.php";
include_once $root_wd . "lib/translator.php";
include_once $root_wd . "lib/error_handler.php";

$env = env::instance($root_wd);

if (!file_exists("config/config.ini")){
	header("Location: setup.php");
	exit;
}
env::load_ini($root_wd . "config/config.ini");

env::$username = $_SESSION['username'];
env::$password = $_SESSION['password'];

env::$db = new database(database::connect(env::sget_conf('pgwatch_connect_string')));

$smarty = new pgwatch_smarty();
env::$smarty = $smarty;
parse_str(@$_SERVER['QUERY_STRING'], $arr);
$env->list = array_merge($arr, $_GET, $_POST);
unset($arr);
validator::remove_illegal($env->list);

?>
