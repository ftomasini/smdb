<?php

function __autoload($class_name){
	global $root_wd;
	$folder_attempts = array("classes/", "smarty/");
	foreach ($folder_attempts as &$folder){
		$filename = $root_wd . $folder . $class_name . ".php";
		if (file_exists($filename) && is_readable($filename)){
			include_once $filename;
			return;
		}
	}
}

?>
